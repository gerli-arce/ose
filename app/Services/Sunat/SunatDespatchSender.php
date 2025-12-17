<?php

namespace App\Services\Sunat;

use App\Models\DespatchAdvice;
use App\Models\EDocument;
use Greenter\See;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Servicio para enviar Guías de Remisión a SUNAT
 */
class SunatDespatchSender
{
    public function __construct(
        private SunatClientFactory $clientFactory,
        private SunatDespatchBuilder $builder
    ) {
    }

    /**
     * Enviar guía de remisión a SUNAT
     */
    public function send(DespatchAdvice $despatch): array
    {
        try {
            // Construir objeto Despatch
            $greenterDespatch = $this->builder->build($despatch);

            // Obtener cliente See configurado
            $see = $this->clientFactory->createForCompany($despatch->company);

            // Generar XML firmado
            $xml = $see->getXmlSigned($greenterDespatch);
            
            // Guardar XML
            $xmlPath = $this->saveXml($despatch, $xml);

            // Extraer hash del XML
            $hash = $this->extractHash($xml);

            // Enviar a SUNAT
            $result = $see->send($greenterDespatch);

            if ($result->isSuccess()) {
                // Guardar CDR
                $cdrPath = $this->saveCdr($despatch, $result->getCdrZip());

                // Actualizar estado de la guía
                $despatch->update([
                    'sunat_status' => DespatchAdvice::SUNAT_ACCEPTED,
                    'status' => DespatchAdvice::STATUS_ISSUED,
                ]);

                // Crear/actualizar EDocument
                $this->createEDocument($despatch, $xmlPath, $cdrPath, $hash, $result);

                Log::info("Guía {$despatch->full_number} enviada exitosamente a SUNAT");

                return [
                    'success' => true,
                    'message' => 'Guía enviada exitosamente a SUNAT.',
                    'code' => $result->getCdrResponse()?->getCode(),
                    'description' => $result->getCdrResponse()?->getDescription(),
                ];
            }

            // Error en el envío
            $despatch->update([
                'sunat_status' => DespatchAdvice::SUNAT_REJECTED,
            ]);

            $errorMessage = $result->getError()?->getMessage() ?? 'Error desconocido';
            
            Log::error("Error al enviar guía {$despatch->full_number}: {$errorMessage}");

            return [
                'success' => false,
                'message' => $errorMessage,
                'code' => $result->getError()?->getCode(),
            ];

        } catch (\Exception $e) {
            Log::error("Excepción al enviar guía {$despatch->full_number}: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error al procesar la guía: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Guardar XML firmado
     */
    private function saveXml(DespatchAdvice $despatch, string $xml): string
    {
        $filename = $this->getFilename($despatch);
        $path = "edocs/xml/09/{$filename}.xml";
        
        Storage::put($path, $xml);
        
        return $path;
    }

    /**
     * Guardar CDR
     */
    private function saveCdr(DespatchAdvice $despatch, string $cdrZip): string
    {
        $filename = $this->getFilename($despatch);
        $path = "edocs/cdr/09/R-{$filename}.zip";
        
        Storage::put($path, $cdrZip);
        
        return $path;
    }

    /**
     * Obtener nombre de archivo
     */
    private function getFilename(DespatchAdvice $despatch): string
    {
        $ruc = $despatch->company?->tax_id ?? '00000000000';
        $serie = $despatch->series?->prefix ?? 'T001';
        $numero = str_pad($despatch->number, 8, '0', STR_PAD_LEFT);
        
        return "{$ruc}-09-{$serie}-{$numero}";
    }

    /**
     * Extraer hash del XML
     */
    private function extractHash(string $xml): ?string
    {
        preg_match('/<ds:DigestValue>([^<]+)<\/ds:DigestValue>/', $xml, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Crear o actualizar EDocument
     */
    private function createEDocument(
        DespatchAdvice $despatch,
        string $xmlPath,
        ?string $cdrPath,
        ?string $hash,
        $result
    ): void {
        EDocument::updateOrCreate(
            [
                'documentable_type' => DespatchAdvice::class,
                'documentable_id' => $despatch->id,
            ],
            [
                'company_id' => $despatch->company_id,
                'document_type' => '09',
                'series' => $despatch->series?->prefix,
                'number' => $despatch->number,
                'xml_path' => $xmlPath,
                'cdr_path' => $cdrPath,
                'hash' => $hash,
                'response_code' => $result->getCdrResponse()?->getCode(),
                'response_message' => $result->getCdrResponse()?->getDescription(),
                'status' => 'accepted',
            ]
        );
    }
}
