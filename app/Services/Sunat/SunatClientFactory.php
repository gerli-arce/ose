<?php

namespace App\Services\Sunat;

use App\Models\Company;
use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

/**
 * Fabrica un cliente Greenter configurado para un contribuyente.
 */
class SunatClientFactory
{
    public function make(Company $company): See
    {
        $ruc = $this->getRequired(
            $company->tax_id ?? env('SUNAT_RUC'),
            'SUNAT_RUC / tax_id de la empresa'
        );

        $solUser = $this->getRequired(
            $company->sunat_sol_user ?? env('SUNAT_SOL_USER'),
            'SUNAT_SOL_USER'
        );

        $solPass = $this->getRequired(
            $company->sunat_sol_password_decrypted ?? env('SUNAT_SOL_PASS'),
            'SUNAT_SOL_PASS'
        );

        $certPath = $this->getRequired(
            $company->sunat_cert_path ?? env('SUNAT_CERT_PATH'),
            'SUNAT_CERT_PATH (ruta o contenido PEM/PFX)'
        );

        $certPass = $company->sunat_cert_password_decrypted ?? env('SUNAT_CERT_PASS');
        $certificate = $this->loadCertificate($certPath, $certPass);
        $endpoint = $this->resolveEndpoint($company->sunat_env ?? env('SUNAT_ENV', 'beta'));

        $see = new See();
        $see->setService($endpoint);
        $see->setCertificate($certificate);
        $see->setCredentials($ruc . $solUser, $solPass);
        $see->setCachePath(storage_path('framework/cache'));

        return $see;
    }

    private function resolveEndpoint(string $env): string
    {
        return $env === 'production'
            ? SunatEndpoints::FE_PRODUCCION
            : SunatEndpoints::FE_BETA;
    }

    private function loadCertificate(string $pathOrContent, ?string $password): string
    {
        $content = $this->readContent($pathOrContent);

        // Si ya viene en PEM (cert + key), se usa directo.
        if ($this->isPem($content)) {
            return $content;
        }

        // Intentar convertir PFX/P12 a PEM.
        if (!function_exists('openssl_pkcs12_read')) {
            throw new InvalidArgumentException('No se puede leer el certificado: extensión OpenSSL no disponible.');
        }

        if ($password === null) {
            throw new InvalidArgumentException('El certificado PFX requiere SUNAT_CERT_PASS.');
        }

        $certs = [];
        if (!openssl_pkcs12_read($content, $certs, $password)) {
            throw new InvalidArgumentException('No se pudo leer el certificado PFX/P12. Verifica password y archivo.');
        }

        return ($certs['pkey'] ?? '') . ($certs['cert'] ?? '');
    }

    private function readContent(string $pathOrContent): string
    {
        // Si es un camino en Storage.
        if (Storage::exists($pathOrContent)) {
            return Storage::get($pathOrContent);
        }

        // Si es una ruta absoluta/relativa en el filesystem.
        if (is_file($pathOrContent)) {
            return file_get_contents($pathOrContent);
        }

        // Fallback: se asume que ya es el contenido en bruto (PEM o PFX).
        return $pathOrContent;
    }

    private function isPem(string $content): bool
    {
        return str_contains($content, 'BEGIN CERTIFICATE')
            || str_contains($content, 'BEGIN PRIVATE KEY')
            || str_contains($content, 'BEGIN RSA PRIVATE KEY');
    }

    private function getRequired(?string $value, string $label): string
    {
        $trimmed = trim((string) $value);
        if ($trimmed === '') {
            throw new InvalidArgumentException("Falta configurar {$label}.");
        }

        return $trimmed;
    }
}
