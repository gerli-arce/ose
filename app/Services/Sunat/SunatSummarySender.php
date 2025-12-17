<?php

namespace App\Services\Sunat;

use App\Models\DailySummary;
use App\Models\SalesDocument;
use Greenter\Model\Response\SummaryResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Servicio para enviar Resumen Diario a SUNAT y consultar estado
 */
class SunatSummarySender
{
    public function __construct(
        private SunatClientFactory $clientFactory,
        private SunatSummaryBuilder $summaryBuilder
    ) {
    }

    /**
     * Enviar Resumen Diario a SUNAT
     * Retorna el ticket para consulta posterior
     */
    public function send(DailySummary $dailySummary): array
    {
        $dailySummary->loadMissing(['company', 'items.salesDocument']);

        $see = $this->clientFactory->make($dailySummary->company);
        $summary = $this->summaryBuilder->build($dailySummary);

        // Generar XML firmado
        $xml = $see->getXmlSigned($summary);

        // Enviar a SUNAT (retorna ticket)
        /** @var SummaryResult $result */
        $result = $see->send($summary);

        return DB::transaction(function () use ($dailySummary, $xml, $result) {
            // Guardar XML
            $xmlPath = $this->storeXml($dailySummary, $xml);

            if ($result->isSuccess()) {
                $ticket = $result->getTicket();
                
                $dailySummary->update([
                    'status' => 'sent',
                    'ticket' => $ticket,
                    'xml_path' => $xmlPath,
                    'sent_at' => now(),
                ]);

                Log::info('Resumen Diario enviado', [
                    'id' => $dailySummary->id,
                    'ticket' => $ticket,
                ]);

                return [
                    'success' => true,
                    'ticket' => $ticket,
                    'xml_path' => $xmlPath,
                ];
            }

            // Error en el envío
            $error = $result->getError();
            $dailySummary->update([
                'status' => 'rejected',
                'xml_path' => $xmlPath,
                'response_code' => $error?->getCode(),
                'response_message' => $error?->getMessage(),
            ]);

            Log::error('Error en Resumen Diario', [
                'id' => $dailySummary->id,
                'error' => $error?->getMessage(),
            ]);

            return [
                'success' => false,
                'error_code' => $error?->getCode(),
                'error_message' => $error?->getMessage(),
            ];
        });
    }

    /**
     * Consultar estado del ticket en SUNAT
     */
    public function checkStatus(DailySummary $dailySummary): array
    {
        if (!$dailySummary->ticket) {
            throw new \InvalidArgumentException('El resumen no tiene ticket asignado.');
        }

        $dailySummary->loadMissing(['company', 'items.salesDocument']);

        $see = $this->clientFactory->make($dailySummary->company);

        // Consultar estado del ticket
        $result = $see->getStatus($dailySummary->ticket);

        return DB::transaction(function () use ($dailySummary, $result) {
            $dailySummary->status_checked_at = now();

            if ($result->isSuccess()) {
                $cdr = $result->getCdrResponse();
                $cdrPath = $this->storeCdr($dailySummary, $result->getCdrZip());

                $dailySummary->fill([
                    'status' => 'accepted',
                    'response_code' => $cdr?->getCode(),
                    'response_message' => $cdr?->getDescription(),
                    'cdr_path' => $cdrPath,
                ]);
                $dailySummary->save();

                // Marcar documentos como procesados en SUNAT
                $this->markDocumentsAsProcessed($dailySummary);

                Log::info('Resumen Diario aceptado', [
                    'id' => $dailySummary->id,
                    'code' => $cdr?->getCode(),
                ]);

                return [
                    'success' => true,
                    'status' => 'accepted',
                    'code' => $cdr?->getCode(),
                    'message' => $cdr?->getDescription(),
                ];
            }

            // Verificar si todavía está en proceso
            $error = $result->getError();
            $errorCode = $error?->getCode();

            // Código 0 o null puede significar que aún está procesando
            if ($errorCode === null || $errorCode === '0' || $errorCode === '98') {
                $dailySummary->save();
                
                return [
                    'success' => true,
                    'status' => 'processing',
                    'message' => 'El resumen aún está siendo procesado por SUNAT.',
                ];
            }

            // Error definitivo
            $dailySummary->fill([
                'status' => 'rejected',
                'response_code' => $errorCode,
                'response_message' => $error?->getMessage(),
            ]);
            $dailySummary->save();

            Log::error('Resumen Diario rechazado', [
                'id' => $dailySummary->id,
                'error' => $error?->getMessage(),
            ]);

            return [
                'success' => false,
                'status' => 'rejected',
                'code' => $errorCode,
                'message' => $error?->getMessage(),
            ];
        });
    }

    /**
     * Marcar los documentos como procesados en SUNAT
     */
    private function markDocumentsAsProcessed(DailySummary $dailySummary): void
    {
        foreach ($dailySummary->items as $item) {
            $statusToSet = 'accepted';
            
            // Si es anulación, marcar como anulado
            if ($item->status_code === '3') {
                $statusToSet = 'voided';
                SalesDocument::where('id', $item->sales_document_id)
                    ->update([
                        'sunat_status' => $statusToSet,
                        'status' => 'annulled',
                        'voided_at' => now(),
                    ]);
            } else {
                SalesDocument::where('id', $item->sales_document_id)
                    ->update(['sunat_status' => $statusToSet]);
            }
        }
    }

    private function storeXml(DailySummary $dailySummary, string $xml): string
    {
        $name = $this->fileName($dailySummary);
        $path = "edocs/summary/{$name}.xml";
        Storage::put($path, $xml);
        return $path;
    }

    private function storeCdr(DailySummary $dailySummary, ?string $cdr): ?string
    {
        if (!$cdr) {
            return null;
        }

        $name = $this->fileName($dailySummary);
        $path = "edocs/summary/R-{$name}.zip";
        Storage::put($path, $cdr);
        return $path;
    }

    private function fileName(DailySummary $dailySummary): string
    {
        $ruc = $dailySummary->company?->tax_id ?? '00000000000';
        return "{$ruc}-{$dailySummary->identifier}";
    }
}
