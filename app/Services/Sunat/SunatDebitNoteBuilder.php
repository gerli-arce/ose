<?php

namespace App\Services\Sunat;

use App\Models\SalesDocument;
use Carbon\Carbon;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Document;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use InvalidArgumentException;

/**
 * Construye el objeto Note (Nota de Débito) de Greenter
 * Documento SUNAT código 08
 */
class SunatDebitNoteBuilder
{
    /**
     * Construir objeto Note para Nota de Débito
     */
    public function build(SalesDocument $debitNote): Note
    {
        $debitNote->loadMissing([
            'company',
            'customer',
            'series',
            'documentType',
            'items.product.unitOfMeasure',
            'relatedDocument.series',
            'relatedDocument.documentType',
            'debitNoteType'
        ]);

        $this->validate($debitNote);

        $company = $this->mapCompany($debitNote);
        $client = $this->mapClient($debitNote);
        $items = $this->mapItems($debitNote);
        $relatedDoc = $this->mapRelatedDocument($debitNote);

        $note = (new Note())
            ->setUblVersion('2.1')
            ->setTipoDoc('08') // Nota de Débito
            ->setSerie($debitNote->series->prefix)
            ->setCorrelativo((string) $debitNote->number)
            ->setFechaEmision(Carbon::parse($debitNote->issue_date))
            ->setTipDocAfectado($debitNote->relatedDocument->documentType->code)
            ->setNumDocfectado(
                $debitNote->relatedDocument->series->prefix . '-' .
                str_pad($debitNote->relatedDocument->number, 8, '0', STR_PAD_LEFT)
            )
            ->setCodMotivo($debitNote->debitNoteType->code)
            ->setDesMotivo($debitNote->note_reason ?? $debitNote->debitNoteType->name)
            ->setTipoMoneda($debitNote->currency ?? 'PEN')
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas($debitNote->subtotal)
            ->setMtoIGV($debitNote->tax_total ?? $debitNote->total_igv ?? 0)
            ->setTotalImpuestos($debitNote->tax_total ?? $debitNote->total_igv ?? 0)
            ->setMtoImpVenta($debitNote->total)
            ->setDetails($items);

        return $note;
    }

    private function validate(SalesDocument $debitNote): void
    {
        if (!$debitNote->company) {
            throw new InvalidArgumentException('La nota de débito no tiene empresa asociada.');
        }

        if (!$debitNote->relatedDocument) {
            throw new InvalidArgumentException('La nota de débito debe estar asociada a un documento.');
        }

        if (!$debitNote->debitNoteType) {
            throw new InvalidArgumentException('La nota de débito debe tener un tipo asignado (Catálogo 10).');
        }

        if ($debitNote->items->isEmpty()) {
            throw new InvalidArgumentException('La nota de débito debe tener al menos un ítem.');
        }
    }

    private function mapCompany(SalesDocument $debitNote): Company
    {
        $company = $debitNote->company;
        
        $address = new Address();
        $address->setUbigueo($company->ubigeo ?? '150101');
        $address->setDepartamento($company->department ?? 'LIMA');
        $address->setProvincia($company->province ?? 'LIMA');
        $address->setDistrito($company->district ?? 'LIMA');
        $address->setUrbanizacion($company->urbanization ?? '-');
        $address->setDireccion($company->address ?? '');
        $address->setCodLocal('0000');

        return (new Company())
            ->setRuc($company->tax_id)
            ->setRazonSocial($company->business_name ?? $company->name)
            ->setNombreComercial($company->trade_name ?? $company->name)
            ->setAddress($address);
    }

    private function mapClient(SalesDocument $debitNote): Client
    {
        $customer = $debitNote->customer;
        
        if (!$customer) {
            // Cliente genérico para boletas
            return (new Client())
                ->setTipoDoc('1')
                ->setNumDoc('00000000')
                ->setRznSocial('CLIENTE VARIOS');
        }

        return (new Client())
            ->setTipoDoc($customer->sunat_doc_type_code ?? '6')
            ->setNumDoc($customer->tax_id ?? '00000000')
            ->setRznSocial($customer->name ?? 'CLIENTE');
    }

    private function mapItems(SalesDocument $debitNote): array
    {
        $items = [];
        
        foreach ($debitNote->items as $index => $item) {
            $detail = (new SaleDetail())
                ->setCodProducto($item->product?->code ?? 'PROD' . ($index + 1))
                ->setUnidad($item->product?->unitOfMeasure?->sunat_code ?? 'NIU')
                ->setCantidad($item->quantity)
                ->setMtoValorUnitario(round($item->unit_price / 1.18, 2))
                ->setDescripcion($item->description ?? $item->product?->name ?? 'Producto')
                ->setMtoBaseIgv(round($item->total / 1.18, 2))
                ->setPorcentajeIgv(18.00)
                ->setIgv(round($item->total - ($item->total / 1.18), 2))
                ->setTipAfeIgv('10') // Gravado - Operación Onerosa
                ->setTotalImpuestos(round($item->total - ($item->total / 1.18), 2))
                ->setMtoValorVenta(round($item->total / 1.18, 2))
                ->setMtoPrecioUnitario($item->unit_price);

            $items[] = $detail;
        }

        return $items;
    }

    private function mapRelatedDocument(SalesDocument $debitNote): Document
    {
        $related = $debitNote->relatedDocument;
        
        return (new Document())
            ->setTipoDoc($related->documentType->code)
            ->setNroDoc(
                $related->series->prefix . '-' .
                str_pad($related->number, 8, '0', STR_PAD_LEFT)
            );
    }
}
