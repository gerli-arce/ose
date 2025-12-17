<?php

namespace App\Services\Sunat;

use App\Models\SalesDocument;
use App\Models\VoidedDocument;
use Greenter\Model\Response\SummaryResult;
use Greenter\See;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Servicio para enviar Comunicación de Baja a SUNAT y consultar estado
 */
class SunatVoidedSender
{
    public function __construct(
        private SunatClientFactory $clientFactory,
        private SunatVoidedBuilder $voidedBuilder
    ) {
    }

    /**
     * Enviar Comunicación de Baja a SUNAT
     * Retorna el ticket para consulta posterior
     */
    public function send(VoidedDocument $voidedDocument): array
    {
        $voidedDocument->loadMissing(['company', 'items']);

        $see = $this->clientFactory->make($voidedDocument->company);
        $voided = $this->voidedBuilder->build($voidedDocument);

        // Generar XML firmado
        $xml = $see->getXmlSigned($voided);

        // Enviar a SUNAT (retorna ticket)
        /** @var SummaryResult $result */
        $result = $see->send($voided);

        return DB::transaction(function () use ($voidedDocument, $xml, $result) {
            // Guardar XML
            $xmlPath = $this->storeXml($voidedDocument, $xml);

            if ($result->isSuccess()) {
                $ticket = $result->getTicket();
                
                $voidedDocument->update([
                    'status' => 'sent',
                    'ticket' => $ticket,
                    'sent_at' => now(),
                ]);

                Log::info('Comunicación de Baja enviada', [
                    'id' => $voidedDocument->id,
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
            $voidedDocument->update([
                'status' => 'rejected',
                'response_code' => $error?->getCode(),
                'response_message' => $error?->getMessage(),
            ]);

            Log::error('Error en Comunicación de Baja', [
                'id' => $voidedDocument->id,
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
    public function checkStatus(VoidedDocument $voidedDocument): array
    {
        if (!$voidedDocument->ticket) {
            throw new \InvalidArgumentException('La comunicación no tiene ticket asignado.');
        }

        $voidedDocument->loadMissing(['company', 'items']);

        $see = $this->clientFactory->make($voidedDocument->company);

        // Consultar estado del ticket
        $result = $see->getStatus($voidedDocument->ticket);

        return DB::transaction(function () use ($voidedDocument, $result) {
            $voidedDocument->status_checked_at = now();

            if ($result->isSuccess()) {
                $cdr = $result->getCdrResponse();
                $cdrPath = $this->storeCdr($voidedDocument, $result->getCdrZip());

                $voidedDocument->fill([
                    'status' => 'accepted',
                    'response_code' => $cdr?->getCode(),
                    'response_message' => $cdr?->getDescription(),
                    'cdr_path' => $cdrPath,
                ]);
                $voidedDocument->save();

                // Marcar documentos como anulados
                $this->markDocumentsAsVoided($voidedDocument);

                Log::info('Comunicación de Baja aceptada', [
                    'id' => $voidedDocument->id,
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
                $voidedDocument->save();
                
                return [
                    'success' => true,
                    'status' => 'processing',
                    'message' => 'La comunicación aún está siendo procesada por SUNAT.',
                ];
            }

            // Error definitivo
            $voidedDocument->fill([
                'status' => 'rejected',
                'response_code' => $errorCode,
                'response_message' => $error?->getMessage(),
            ]);
            $voidedDocument->save();

            Log::error('Comunicación de Baja rechazada', [
                'id' => $voidedDocument->id,
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
     * Marcar los documentos como anulados
     */
    private function markDocumentsAsVoided(VoidedDocument $voidedDocument): void
    {
        foreach ($voidedDocument->items as $item) {
            SalesDocument::where('id', $item->sales_document_id)
                ->update([
                    'status' => 'annulled',
                    'sunat_status' => 'voided',
                    'voided_document_id' => $voidedDocument->id,
                    'voided_at' => now(),
                ]);
        }
    }

    private function storeXml(VoidedDocument $voidedDocument, string $xml): string
    {
        $name = $this->fileName($voidedDocument);
        $path = "edocs/voided/{$name}.xml";
        Storage::put($path, $xml);
        return $path;
    }

    private function storeCdr(VoidedDocument $voidedDocument, ?string $cdr): ?string
    {
        if (!$cdr) {
            return null;
        }

        $name = $this->fileName($voidedDocument);
        $path = "edocs/voided/R-{$name}.zip";
        Storage::put($path, $cdr);
        return $path;
    }

    private function fileName(VoidedDocument $voidedDocument): string
    {
        $ruc = $voidedDocument->company?->tax_id ?? '00000000000';
        return "{$ruc}-{$voidedDocument->identifier}";
    }
}
