<?php

namespace App\Http\Controllers;

use App\Models\SalesDocument;
use App\Models\SunatSendAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EDocViewerController extends Controller
{
    /**
     * Vista principal del visor de documentos electrónicos
     */
    public function show(SalesDocument $document)
    {
        $companyId = session('current_company_id');

        if ($document->company_id != $companyId) {
            abort(403);
        }

        $document->load([
            'company',
            'series',
            'documentType',
            'eDocument',
        ]);

        // Obtener histórico de intentos de envío
        $attempts = SunatSendAttempt::where('sales_document_id', $document->id)
            ->orderBy('attempted_at', 'desc')
            ->get();

        return view('sales.edoc-viewer.show', compact('document', 'attempts'));
    }

    /**
     * Ver contenido del XML con resaltado de sintaxis
     */
    public function viewXml(SalesDocument $document)
    {
        $companyId = session('current_company_id');

        if ($document->company_id != $companyId) {
            abort(403);
        }

        $document->load('eDocument');

        $xmlPath = $document->eDocument?->xml_path;
        
        if (!$xmlPath || !Storage::exists($xmlPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Archivo XML no encontrado.',
            ], 404);
        }

        $xmlContent = Storage::get($xmlPath);
        
        // Formatear XML para mejor visualización
        $formattedXml = $this->formatXml($xmlContent);

        return response()->json([
            'success' => true,
            'content' => $formattedXml,
            'filename' => basename($xmlPath),
            'size' => Storage::size($xmlPath),
        ]);
    }

    /**
     * Descargar XML
     */
    public function downloadXml(SalesDocument $document)
    {
        $companyId = session('current_company_id');

        if ($document->company_id != $companyId) {
            abort(403);
        }

        $document->load('eDocument');

        $xmlPath = $document->eDocument?->xml_path;
        
        if (!$xmlPath || !Storage::exists($xmlPath)) {
            return back()->with('error', 'Archivo XML no encontrado.');
        }

        return Storage::download($xmlPath, basename($xmlPath));
    }

    /**
     * Descargar CDR (ZIP)
     */
    public function downloadCdr(SalesDocument $document)
    {
        $companyId = session('current_company_id');

        if ($document->company_id != $companyId) {
            abort(403);
        }

        $document->load('eDocument');

        $cdrPath = $document->eDocument?->cdr_path;
        
        if (!$cdrPath || !Storage::exists($cdrPath)) {
            return back()->with('error', 'Archivo CDR no encontrado.');
        }

        return Storage::download($cdrPath, basename($cdrPath));
    }

    /**
     * Extraer y ver contenido del CDR
     */
    public function viewCdr(SalesDocument $document)
    {
        $companyId = session('current_company_id');

        if ($document->company_id != $companyId) {
            abort(403);
        }

        $document->load('eDocument');

        $cdrPath = $document->eDocument?->cdr_path;
        
        if (!$cdrPath || !Storage::exists($cdrPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Archivo CDR no encontrado.',
            ], 404);
        }

        try {
            // Leer el ZIP y extraer el XML de respuesta
            $zipContent = Storage::get($cdrPath);
            $tempFile = tempnam(sys_get_temp_dir(), 'cdr');
            file_put_contents($tempFile, $zipContent);

            $zip = new \ZipArchive();
            if ($zip->open($tempFile) === true) {
                // Buscar el archivo XML dentro del ZIP
                $xmlContent = null;
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    if (str_ends_with(strtolower($filename), '.xml')) {
                        $xmlContent = $zip->getFromIndex($i);
                        break;
                    }
                }
                $zip->close();
                unlink($tempFile);

                if ($xmlContent) {
                    return response()->json([
                        'success' => true,
                        'content' => $this->formatXml($xmlContent),
                        'filename' => basename($cdrPath),
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'No se pudo extraer el contenido del CDR.',
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar CDR: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ver detalle de un intento de envío
     */
    public function viewAttempt(SunatSendAttempt $attempt)
    {
        $companyId = session('current_company_id');

        if ($attempt->salesDocument->company_id != $companyId) {
            abort(403);
        }

        return response()->json([
            'success' => true,
            'attempt' => [
                'id' => $attempt->id,
                'type' => $attempt->attempt_type_name,
                'status' => $attempt->status,
                'status_name' => $attempt->status_name,
                'response_code' => $attempt->response_code,
                'response_message' => $attempt->response_message,
                'ticket' => $attempt->ticket,
                'error_details' => $attempt->error_details,
                'attempted_at' => $attempt->attempted_at->format('d/m/Y H:i:s'),
                'user' => $attempt->user?->name,
            ],
        ]);
    }

    /**
     * Formatear XML para mejor visualización
     */
    private function formatXml(string $xml): string
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        
        // Suprimir errores de parseo
        libxml_use_internal_errors(true);
        $dom->loadXML($xml);
        libxml_clear_errors();
        
        $formatted = $dom->saveXML();
        
        // Si falló el formateo, retornar original
        return $formatted ?: $xml;
    }
}
