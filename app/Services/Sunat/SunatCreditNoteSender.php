<?php

namespace App\Services\Sunat;

use App\Models\EDocument;
use App\Models\EDocumentLog;
use App\Models\SalesDocument;
use Greenter\Model\Response\BillResult;
use Greenter\Model\Response\StatusResult;
use Greenter\Model\Response\SummaryResult;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Servicio para firmar y enviar Notas de Crédito a SUNAT
 */
class SunatCreditNoteSender
{
    public function __construct(
        private SunatClientFactory $clientFactory,
        private SunatCreditNoteBuilder $creditNoteBuilder
    ) {
    }

    /**
     * Firma y envía una Nota de Crédito a SUNAT.
     */
    public function send(SalesDocument $creditNote): array
    {
        $creditNote->loadMissing(['company', 'eDocument']);

        $see = $this->clientFactory->make($creditNote->company);
        $note = $this->creditNoteBuilder->build($creditNote);

        // Generar XML firmado
        $xml = $see->getXmlSigned($note);

        // Enviar a SUNAT
        $result = $see->send($note);

        // Persistir en transacción
        return DB::transaction(function () use ($creditNote, $xml, $result) {
            $eDoc = $this->persistEDocument($creditNote, $xml, $result);
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

    private function persistEDocument(SalesDocument $creditNote, string $xml, BillResult|SummaryResult|StatusResult|null $result): EDocument
    {
        $eDoc = $creditNote->eDocument ?: new EDocument(['sales_document_id' => $creditNote->id]);

        $xmlPath = $this->storeXml($creditNote, $xml);
        $cdrPath = $this->storeCdr($creditNote, $result);

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

        // Actualizar estado en documento
        $creditNote->sunat_status = $status['sunat_status'];
        $creditNote->save();

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

    private function storeXml(SalesDocument $creditNote, string $xml): string
    {
        $name = $this->xmlName($creditNote);
        $path = "edocs/xml/{$name}.xml";
        Storage::put($path, $xml);
        return $path;
    }

    private function storeCdr(SalesDocument $creditNote, BillResult|SummaryResult|StatusResult|null $result): ?string
    {
        $cdr = $result?->getCdrZip();
        if (!$cdr) {
            return null;
        }

        $name = $this->xmlName($creditNote);
        $path = "edocs/cdr/R-{$name}.zip";
        Storage::put($path, $cdr);
        return $path;
    }

    private function mapStatus(BillResult|SummaryResult|StatusResult|null $result): array
    {
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
            return [
                'sunat_status' => 'accepted',
                'status' => 'accepted',
                'code' => $cdr?->getCode(),
                'message' => $cdr?->getDescription(),
                'hash' => $result->getCdrZipHash() ?? null,
            ];
        }

        return [
            'sunat_status' => 'rejected',
            'status' => 'rejected',
            'code' => $result->getError()?->getCode(),
            'message' => $result->getError()?->getMessage(),
        ];
    }

    private function xmlName(SalesDocument $creditNote): string
    {
        $companyRuc = $creditNote->company?->tax_id ?? '00000000000';
        $tipoDoc = '07'; // Nota de crédito
        $serie = $creditNote->series?->prefix ?? 'NC01';
        $correl = str_pad((string) $creditNote->number, 8, '0', STR_PAD_LEFT);

        return "{$companyRuc}-{$tipoDoc}-{$serie}-{$correl}";
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
            ];
        }

        return $arr;
    }
}
