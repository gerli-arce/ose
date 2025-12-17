<?php

namespace App\Services\Sunat;

use App\Models\EDocument;
use App\Models\EDocumentLog;
use App\Models\SalesDocument;
use Greenter\Model\Response\BillResult;
use Greenter\Model\Response\StatusResult;
use Greenter\Model\Response\SummaryResult;
use Greenter\See;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class SunatSender
{
    public function __construct(
        private SunatClientFactory $clientFactory,
        private SunatInvoiceBuilder $invoiceBuilder
    ) {
    }

    /**
     * Firma y envía un SalesDocument a SUNAT.
     */
    public function send(SalesDocument $document): array
    {
        $document->loadMissing(['company', 'eDocument']);

        $see = $this->clientFactory->make($document->company);
        $invoice = $this->invoiceBuilder->build($document);

        // Generar XML firmado
        $xml = $see->getXmlSigned($invoice);

        // Enviar a SUNAT
        $result = $see->send($invoice);

        // Persistir en transacción
        return DB::transaction(function () use ($document, $xml, $result) {
            $eDoc = $this->persistEDocument($document, $xml, $result);
            $this->logResult($eDoc, $result);

            return [
                'status' => $eDoc->response_status,
                'code' => $eDoc->response_code,
                'message' => $eDoc->response_message,
                'hash' => $eDoc->hash ?? null,
                'cdr_path' => $eDoc->cdr_path ?? null,
            ];
        });
    }

    private function persistEDocument(SalesDocument $document, string $xml, BillResult|SummaryResult|StatusResult|null $result): EDocument
    {
        $eDoc = $document->eDocument ?: new EDocument(['sales_document_id' => $document->id]);

        $xmlPath = $this->storeXml($document, $xml);
        $cdrPath = $this->storeCdr($document, $result);

        $status = $this->mapStatus($result);

        $eDoc->fill([
            'provider' => 'sunat',
            'xml_path' => $xmlPath,
            'cdr_path' => $cdrPath,
            'signed_at' => now(),
            'sent_at' => now(),
            'response_status' => $status['status'],
            'response_code' => $status['code'],
            'response_message' => $status['message'],
            'hash' => $status['hash'] ?? null,
        ]);

        $eDoc->save();

        // Actualizar estado en documento de venta
        $document->sunat_status = $status['sunat_status'];
        $document->save();

        return $eDoc;
    }

    private function logResult(EDocument $eDoc, BillResult|SummaryResult|StatusResult|null $result): void
    {
        $message = $result?->getCdrResponse()?->getDescription() ?? $result?->getMessage() ?? 'SIN RESPUESTA';
        $code = $result?->getCdrResponse()?->getCode() ?? $result?->getError()?->getCode() ?? null;
        $status = $result?->isSuccess() ? 'success' : 'error';

        EDocumentLog::create([
            'e_document_id' => $eDoc->id,
            'message' => $message,
            'details' => json_encode($this->resultToArray($result)),
            'status' => $status,
        ]);
    }

    private function storeXml(SalesDocument $document, string $xml): string
    {
        $name = $this->xmlName($document);
        $path = "edocs/xml/{$name}.xml";
        Storage::put($path, $xml);
        return $path;
    }

    private function storeCdr(SalesDocument $document, BillResult|SummaryResult|StatusResult|null $result): ?string
    {
        $cdr = $result?->getCdrZip();
        if (!$cdr) {
            return null;
        }

        $name = $this->xmlName($document);
        $path = "edocs/cdr/R-{$name}.zip";
        Storage::put($path, $cdr);
        return $path;
    }

    private function mapStatus(BillResult|SummaryResult|StatusResult|null $result): array
    {
        $sunatStatus = 'rejected';
        $respStatus = 'rejected';
        $code = null;
        $message = null;
        $hash = null;

        if (!$result) {
            return [
                'sunat_status' => 'rejected',
                'status' => 'rejected',
                'code' => null,
                'message' => 'Sin respuesta de SUNAT',
            ];
        }

        if ($result->isSuccess()) {
            $cdr = $result->getCdrResponse();
            $sunatStatus = 'accepted';
            $respStatus = 'accepted';
            $code = $cdr?->getCode();
            $message = $cdr?->getDescription();
            $hash = $result->getCdrZipHash() ?? $result->getHash() ?? null;
        } else {
            $sunatStatus = 'rejected';
            $respStatus = 'rejected';
            $code = $result->getError()?->getCode();
            $message = $result->getError()?->getMessage();
        }

        return [
            'sunat_status' => $sunatStatus,
            'status' => $respStatus,
            'code' => $code,
            'message' => $message,
            'hash' => $hash,
        ];
    }

    private function xmlName(SalesDocument $document): string
    {
        $companyRuc = $document->company?->tax_id ?? '00000000000';
        $serie = $document->series?->prefix ?? 'SER';
        $correl = str_pad((string) $document->number, 8, '0', STR_PAD_LEFT);

        return "{$companyRuc}-{$serie}-{$correl}";
    }

    private function resultToArray(BillResult|SummaryResult|StatusResult|null $result): array
    {
        if (!$result) {
            return ['error' => 'sin_respuesta'];
        }

        $arr = [
            'success' => $result->isSuccess(),
            'cdr' => null,
            'error' => null,
        ];

        if ($cdr = $result->getCdrResponse()) {
            $arr['cdr'] = [
                'code' => $cdr->getCode(),
                'description' => $cdr->getDescription(),
                'notes' => $cdr->getNotes(),
            ];
        }

        if ($err = $result->getError()) {
            $arr['error'] = [
                'code' => $err->getCode(),
                'message' => $err->getMessage(),
                'trace' => $err->getTrace(),
            ];
        }

        return $arr;
    }
}
