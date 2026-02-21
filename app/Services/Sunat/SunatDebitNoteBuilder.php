<?php

namespace App\Services\Sunat;

use App\Models\SalesDocument;
use Carbon\Carbon;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use InvalidArgumentException;

/**
 * Construye el objeto Note (Nota de Debito) de Greenter.
 * Documento SUNAT codigo 08.
 */
class SunatDebitNoteBuilder
{
    /**
     * Construir objeto Note para Nota de Debito.
     */
    public function build(SalesDocument $debitNote): Note
    {
        $debitNote->loadMissing([
            'company',
            'customer',
            'series',
            'documentType',
            'currency',
            'items.unit',
            'items.product.unit',
            'items.product.unitOfMeasure',
            'relatedDocument.series',
            'relatedDocument.documentType',
            'debitNoteType',
        ]);

        $this->validate($debitNote);

        $company = $this->mapCompany($debitNote);
        $client = $this->mapClient($debitNote);
        $items = $this->mapItems($debitNote);

        return (new Note())
            ->setUblVersion('2.1')
            ->setTipoDoc('08')
            ->setSerie($debitNote->series->prefix)
            ->setCorrelativo((string) $debitNote->number)
            ->setFechaEmision(Carbon::parse($debitNote->issue_date))
            ->setTipDocAfectado($debitNote->relatedDocument->documentType->code)
            ->setNumDocfectado($this->formatRelatedDocumentNumber($debitNote->relatedDocument))
            ->setCodMotivo($debitNote->debitNoteType->code)
            ->setDesMotivo($debitNote->note_reason ?? $debitNote->debitNoteType->name)
            ->setTipoMoneda($debitNote->currency?->code ?: 'PEN')
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas((float) $debitNote->subtotal)
            ->setMtoIGV((float) ($debitNote->tax_total ?? 0))
            ->setTotalImpuestos((float) ($debitNote->tax_total ?? 0))
            ->setMtoImpVenta((float) $debitNote->total)
            ->setDetails($items);
    }

    private function validate(SalesDocument $debitNote): void
    {
        if (!$debitNote->company) {
            throw new InvalidArgumentException('La nota de debito no tiene empresa asociada.');
        }

        if (!$debitNote->relatedDocument) {
            throw new InvalidArgumentException('La nota de debito debe estar asociada a un documento.');
        }

        if (!$debitNote->debitNoteType) {
            throw new InvalidArgumentException('La nota de debito debe tener un tipo asignado (Catalogo 10).');
        }

        if ($debitNote->items->isEmpty()) {
            throw new InvalidArgumentException('La nota de debito debe tener al menos un item.');
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

        $companyAddress = $company?->address;
        $direccion = is_object($companyAddress)
            ? ($companyAddress->line1 ?? $companyAddress->address ?? '')
            : (string) ($companyAddress ?? '');
        $address->setDireccion($direccion);
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
            $quantity = (float) ($item->quantity ?? 0);
            $lineTotal = (float) ($item->line_total ?? $item->total ?? 0);
            $lineTax = (float) ($item->line_tax_total ?? $item->igv_amount ?? 0);
            $lineNet = max($lineTotal - $lineTax, 0);
            $unitNet = $quantity > 0 ? $lineNet / $quantity : 0;
            $unitGross = $quantity > 0 ? $lineTotal / $quantity : (float) ($item->unit_price ?? 0);

            $unitSunatCode = $item->unit?->sunat_code
                ?? $item->product?->unit?->sunat_code
                ?? $item->product?->unitOfMeasure?->sunat_code
                ?? 'NIU';

            $items[] = (new SaleDetail())
                ->setCodProducto($item->code ?: ('PROD' . ($index + 1)))
                ->setUnidad($unitSunatCode)
                ->setCantidad($quantity)
                ->setMtoValorUnitario(round($unitNet, 6))
                ->setDescripcion($item->description ?? $item->product?->name ?? 'Producto')
                ->setMtoBaseIgv(round($lineNet, 2))
                ->setPorcentajeIgv(18.00)
                ->setIgv(round($lineTax, 2))
                ->setTipAfeIgv('10')
                ->setTotalImpuestos(round($lineTax, 2))
                ->setMtoValorVenta(round($lineNet, 2))
                ->setMtoPrecioUnitario(round($unitGross, 6));
        }

        return $items;
    }

    private function formatRelatedDocumentNumber(SalesDocument $related): string
    {
        $series = $related->series?->prefix ?? '';
        $number = str_pad((string) $related->number, 8, '0', STR_PAD_LEFT);

        return "{$series}-{$number}";
    }
}
