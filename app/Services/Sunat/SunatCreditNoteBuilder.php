<?php

namespace App\Services\Sunat;

use App\Models\SalesDocument;
use Carbon\Carbon;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company as GreenterCompany;
use Greenter\Model\Sale\Document;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use InvalidArgumentException;

/**
 * Convierte un SalesDocument de tipo Nota de Crédito a UBL 2.1 (Greenter).
 */
class SunatCreditNoteBuilder
{
    /**
     * Construir objeto Note (Nota de Crédito) para Greenter
     */
    public function build(SalesDocument $document): Note
    {
        $document->loadMissing([
            'company', 
            'customer', 
            'series', 
            'items.product', 
            'currency',
            'relatedDocument.series',
            'relatedDocument.documentType',
            'creditNoteType'
        ]);

        // Validaciones
        $this->validateDocument($document);

        $serie = $document->series?->prefix;
        $numero = $this->formatNumber($document->number);
        $moneda = $document->currency?->code ?: 'PEN';
        $fecha = Carbon::parse($document->issue_date)->setTimeFromTimeString('00:00:00');

        $company = $this->mapCompany($document);
        $client = $this->mapClient($document);
        $items = $this->mapItems($document);

        $mtoOperGravadas = $document->subtotal;
        $mtoIgv = $document->tax_total;
        $total = $document->total;

        // Documento relacionado (factura/boleta original)
        $relatedDoc = $this->buildRelatedDocument($document);

        $note = (new Note())
            ->setUblVersion('2.1')
            ->setTipoDoc('07') // Nota de Crédito
            ->setSerie($serie)
            ->setCorrelativo($numero)
            ->setFechaEmision($fecha)
            ->setTipDocAfectado($document->relatedDocument->documentType->code) // 01 o 03
            ->setNumDocfectado($relatedDoc) // Serie-Correlativo del doc afectado
            ->setCodMotivo($document->creditNoteType?->code ?? '01') // Catálogo 09
            ->setDesMotivo($document->note_reason ?? $document->creditNoteType?->name ?? 'Anulación de la operación')
            ->setTipoMoneda($moneda)
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas(round($mtoOperGravadas, 2))
            ->setMtoIGV(round($mtoIgv, 2))
            ->setTotalImpuestos(round($mtoIgv, 2))
            ->setMtoImpVenta(round($total, 2))
            ->setDetails($items)
            ->setLegends($this->buildLegends($total));

        return $note;
    }

    private function validateDocument(SalesDocument $document): void
    {
        if (!$document->isCreditNote()) {
            throw new InvalidArgumentException('El documento no es una Nota de Crédito (código 07).');
        }

        if (!$document->relatedDocument) {
            throw new InvalidArgumentException('La Nota de Crédito debe tener un documento relacionado.');
        }

        if (!$document->series?->prefix) {
            throw new InvalidArgumentException('El documento no tiene serie configurada.');
        }

        if (!$document->creditNoteType) {
            throw new InvalidArgumentException('Debe especificar el tipo de nota de crédito (Catálogo 09).');
        }
    }

    private function buildRelatedDocument(SalesDocument $document): string
    {
        $related = $document->relatedDocument;
        $serie = $related->series?->prefix ?? '';
        $numero = str_pad((string) $related->number, 8, '0', STR_PAD_LEFT);
        return "{$serie}-{$numero}";
    }

    private function mapCompany(SalesDocument $document): GreenterCompany
    {
        $company = $document->company;
        $address = new Address();
        $address->setPais('PE');

        if ($company?->address) {
            $address->setDireccion($company->address ?? '');
        }

        return (new GreenterCompany())
            ->setRuc($company->tax_id)
            ->setRazonSocial($company->business_name ?? $company->name)
            ->setNombreComercial($company->trade_name ?? $company->name)
            ->setAddress($address);
    }

    private function mapClient(SalesDocument $document): Client
    {
        $customer = $document->customer;

        return (new Client())
            ->setTipoDoc($customer->sunat_doc_type_code)
            ->setNumDoc($customer->tax_id)
            ->setRznSocial($customer->business_name ?? $customer->name ?? 'CLIENTE')
            ->setAddress(
                (new Address())
                    ->setPais('PE')
                    ->setDireccion($customer->address ?? '')
            );
    }

    private function mapItems(SalesDocument $document): array
    {
        return $document->items->map(function ($item) {
            $cantidad = (float) $item->quantity;
            $totalLinea = (float) $item->total;
            $igv = (float) ($item->igv_amount ?? 0);
            $valorVenta = $totalLinea - $igv;
            $valorUnitario = $cantidad > 0 ? $valorVenta / $cantidad : 0;
            $precioUnitario = $cantidad > 0 ? $totalLinea / $cantidad : 0;

            $detail = (new SaleDetail())
                ->setCodProducto($item->code ?? $item->product?->code ?? '')
                ->setUnidad($item->product?->unit?->code ?? 'NIU')
                ->setDescripcion($item->description ?? $item->product?->name ?? 'ITEM')
                ->setCantidad(round($cantidad, 2))
                ->setMtoValorUnitario(round($valorUnitario, 6))
                ->setMtoValorVenta(round($valorVenta, 2))
                ->setMtoBaseIgv(round($valorVenta, 2))
                ->setPorcentajeIgv(18)
                ->setIgv(round($igv, 2))
                ->setTipAfeIgv('10') // Gravado - Operación onerosa
                ->setTotalImpuestos(round($igv, 2))
                ->setMtoPrecioUnitario(round($precioUnitario, 6));

            return $detail;
        })->all();
    }

    private function buildLegends(float $total): array
    {
        return [
            (new Legend())
                ->setCode('1000')
                ->setValue($this->numberToWords($total)),
        ];
    }

    private function numberToWords(float $amount): string
    {
        $formatted = number_format($amount, 2, '.', '');
        return "SON {$formatted} SOLES";
    }

    private function formatNumber($number): string
    {
        return str_pad((string) $number, 8, '0', STR_PAD_LEFT);
    }
}
