<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\Sunat\SunatClientFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SunatConfigController extends Controller
{
    /**
     * Mostrar configuración SUNAT
     */
    public function index()
    {
        $companyId = session('current_company_id');
        $company = Company::findOrFail($companyId);

        return view('settings.sunat', compact('company'));
    }

    /**
     * Guardar configuración SUNAT
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'sunat_sol_user' => 'required|string|max:50',
            'sunat_sol_password' => 'nullable|string|max:100',
            'sunat_cert_password' => 'nullable|string|max:100',
            'sunat_env' => 'required|in:beta,production',
            'certificate' => 'nullable|file|mimes:pfx,pem,p12|max:5120', // 5MB max
        ]);

        $companyId = session('current_company_id');
        $company = Company::findOrFail($companyId);

        $updateData = [
            'sunat_sol_user' => $validated['sunat_sol_user'],
            'sunat_env' => $validated['sunat_env'],
        ];

        // Encriptar contraseña SOL si se proporcionó
        if ($request->filled('sunat_sol_password')) {
            $updateData['sunat_sol_password'] = Crypt::encryptString($validated['sunat_sol_password']);
        }

        // Encriptar contraseña del certificado si se proporcionó
        if ($request->filled('sunat_cert_password')) {
            $updateData['sunat_cert_password'] = Crypt::encryptString($validated['sunat_cert_password']);
        }

        // Subir certificado si se proporcionó
        if ($request->hasFile('certificate')) {
            $certificate = $request->file('certificate');
            
            // Eliminar certificado anterior
            if ($company->sunat_cert_path && Storage::exists($company->sunat_cert_path)) {
                Storage::delete($company->sunat_cert_path);
            }

            // Guardar nuevo certificado
            $path = $certificate->store("certificates/{$companyId}", 'local');
            $updateData['sunat_cert_path'] = $path;
        }

        $company->update($updateData);

        return back()->with('success', 'Configuración SUNAT guardada correctamente.');
    }

    /**
     * Probar conexión con SUNAT
     */
    public function testConnection(Request $request)
    {
        $companyId = session('current_company_id');
        $company = Company::findOrFail($companyId);

        try {
            // Validar que tenga la configuración mínima
            if (!$company->sunat_sol_user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se ha configurado el usuario SOL.',
                ], 400);
            }

            if (!$company->sunat_sol_password) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se ha configurado la contraseña SOL.',
                ], 400);
            }

            if (!$company->sunat_cert_path || !Storage::exists($company->sunat_cert_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se ha subido el certificado digital.',
                ], 400);
            }

            // Obtener las credenciales desencriptadas
            $solPassword = $company->sunat_sol_password_decrypted;
            $certPassword = $company->sunat_cert_password_decrypted;

            // Verificar que el certificado sea válido
            $certPath = Storage::path($company->sunat_cert_path);
            
            if (!file_exists($certPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El archivo del certificado no existe.',
                ], 400);
            }

            // Intentar leer el certificado
            $certContent = file_get_contents($certPath);
            $certInfo = null;

            // Verificar si es PFX
            if (pathinfo($certPath, PATHINFO_EXTENSION) === 'pfx' || 
                pathinfo($certPath, PATHINFO_EXTENSION) === 'p12') {
                
                $certs = [];
                $result = openssl_pkcs12_read($certContent, $certs, $certPassword ?? '');
                
                if (!$result) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se pudo leer el certificado. Verifique la contraseña del certificado.',
                        'error' => openssl_error_string(),
                    ], 400);
                }

                // Obtener información del certificado
                $certInfo = openssl_x509_parse($certs['cert']);
            } else {
                // Es PEM
                $certInfo = openssl_x509_parse($certContent);
            }

            if (!$certInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo analizar el certificado.',
                ], 400);
            }

            // Verificar vigencia del certificado
            $validFrom = date('d/m/Y H:i', $certInfo['validFrom_time_t']);
            $validTo = date('d/m/Y H:i', $certInfo['validTo_time_t']);
            $now = time();

            if ($now < $certInfo['validFrom_time_t']) {
                return response()->json([
                    'success' => false,
                    'message' => "El certificado aún no es válido. Válido desde: {$validFrom}",
                ], 400);
            }

            if ($now > $certInfo['validTo_time_t']) {
                return response()->json([
                    'success' => false,
                    'message' => "El certificado ha expirado. Válido hasta: {$validTo}",
                ], 400);
            }

            // Extraer información del certificado
            $subject = $certInfo['subject'] ?? [];
            $issuer = $certInfo['issuer'] ?? [];
            $serialNumber = $certInfo['serialNumber'] ?? 'N/A';

            // Días restantes
            $daysRemaining = round(($certInfo['validTo_time_t'] - $now) / 86400);

            return response()->json([
                'success' => true,
                'message' => 'Configuración verificada correctamente.',
                'certificate' => [
                    'subject' => $subject['CN'] ?? ($subject['O'] ?? 'N/A'),
                    'issuer' => $issuer['CN'] ?? ($issuer['O'] ?? 'N/A'),
                    'serial' => $serialNumber,
                    'valid_from' => $validFrom,
                    'valid_to' => $validTo,
                    'days_remaining' => $daysRemaining,
                ],
                'config' => [
                    'environment' => $company->sunat_env === 'production' ? 'Producción' : 'Beta/Pruebas',
                    'sol_user' => $company->sunat_sol_user,
                    'ruc' => $company->tax_id,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Error al verificar conexión SUNAT: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al verificar la configuración: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Eliminar certificado
     */
    public function deleteCertificate()
    {
        $companyId = session('current_company_id');
        $company = Company::findOrFail($companyId);

        if ($company->sunat_cert_path && Storage::exists($company->sunat_cert_path)) {
            Storage::delete($company->sunat_cert_path);
        }

        $company->update([
            'sunat_cert_path' => null,
            'sunat_cert_password' => null,
        ]);

        return back()->with('success', 'Certificado eliminado correctamente.');
    }
}
