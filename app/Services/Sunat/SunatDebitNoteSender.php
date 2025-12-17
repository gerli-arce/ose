<?php

namespace App\Services\Sunat;

use App\Models\EDocument;
use App\Models\SalesDocument;
use Greenter\Model\Response\BillResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Servicio para enviar Notas de Débito Electrónicas a SUNAT
 */
class SunatDebitNoteSender
{
    public function __construct(
        private SunatClientFactory $clientFactory,
        private SunatDebitNoteBuilder $debitNoteBuilder
    ) {
    }

    /**
     * Enviar Nota de Débito a SUNAT
     */
    public function send(SalesDocument $debitNote): array
    {
        $debitNote->loadMissing([
            'company',
            'customer',
            'series',
            'documentType',
            'items.product.unitOfMeasure',
            'relatedDocument.series',
            'relatedDocument.documentType',
            'debitNoteType',
            'eDocument'
        ]);

        $see = $this->clientFactory->make($debitNote->company);
        $note = $this->debitNoteBuilder->build($debitNote);

        // Generar XML firmado
        $xml = $see->getXmlSigned($note);

        // Enviar a SUNAT
        /** @var BillResult $result */
        $result = $see->send($note);

        return DB::transaction(function () use ($debitNote, $xml, $result, $see, $note) {
            // Guardar XML
            $xmlPath = $this->storeXml($debitNote, $xml);
            $hash = $this->extractHash($xml);

            if ($result->isSuccess()) {
                $cdr = $result->getCdrResponse();
                $cdrPath = $this->storeCdr($debitNote, $result->getCdrZip());

                // Actualizar o crear EDocument
                $this->updateEDocument($debitNote, [
                    'xml_path' => $xmlPath,
                    'cdr_path' => $cdrPath,
                    'hash' => $hash,
                    'response_status' => 'accepted',
                    'response_code' => $cdr?->getCode(),
                    'response_message' => $cdr?->getDescription(),
                ]);

                // Actualizar estado del documento
                $debitNote->update(['sunat_status' => 'accepted']);

                Log::info('Nota de Débito enviada exitosamente', [
                    'id' => $debitNote->id,
                    'code' => $cdr?->getCode(),
                ]);

                return [
                    'success' => true,
                    'code' => $cdr?->getCode(),
                    'message' => $cdr?->getDescription(),
                    'hash' => $hash,
                ];
            }

            // Error en el envío
            $error = $result->getError();
            
            $this->updateEDocument($debitNote, [
                'xml_path' => $xmlPath,
                'hash' => $hash,
                'response_status' => 'rejected',
                'response_code' => $error?->getCode(),
                'response_message' => $error?->getMessage(),
            ]);

            $debitNote->update(['sunat_status' => 'rejected']);

            Log::error('Error al enviar Nota de Débito', [
                'id' => $debitNote->id,
                'error' => $error?->getMessage(),
            ]);

            return [
                'success' => false,
                'code' => $error?->getCode(),
                'message' => $error?->getMessage(),
            ];
        });
    }

    private function updateEDocument(SalesDocument $debitNote, array $data): void
    {
        if ($debitNote->eDocument) {
            $debitNote->eDocument->update($data);
        } else {
            EDocument::create(array_merge($data, [
                'sales_document_id' => $debitNote->id,
            ]));
        }
    }

    private function storeXml(SalesDocument $debitNote, string $xml): string
    {
        $name = $this->fileName($debitNote);
        $path = "edocs/debit-notes/{$name}.xml";
        Storage::put($path, $xml);
        return $path;
    }

    private function storeCdr(SalesDocument $debitNote, ?string $cdr): ?string
    {
        if (!$cdr) {
            return null;
        }

        $name = $this->fileName($debitNote);
        $path = "edocs/debit-notes/R-{$name}.zip";
        Storage::put($path, $cdr);
        return $path;
    }

    private function fileName(SalesDocument $debitNote): string
    {
        $ruc = $debitNote->company?->tax_id ?? '00000000000';
        $tipo = '08'; // Nota de Débito
        $serie = $debitNote->series?->prefix ?? 'FD01';
        $numero = str_pad($debitNote->number ?? 0, 8, '0', STR_PAD_LEFT);
        return "{$ruc}-{$tipo}-{$serie}-{$numero}";
    }

    private function extractHash(string $xml): ?string
    {
        if (preg_match('/<ds:DigestValue>([^<]+)<\/ds:DigestValue>/', $xml, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
