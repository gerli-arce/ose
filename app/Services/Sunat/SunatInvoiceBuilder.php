<?php

namespace App\Services\Sunat;

use App\Models\SalesDocument;
use Carbon\Carbon;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company as GreenterCompany;
use Greenter\Model\Sale\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use InvalidArgumentException;

/**
 * Convierte un SalesDocument e items a un Invoice/Boleta UBL (Greenter).
 * Asume operación gravada (IGV 18%) y pago al contado.
 */
class SunatInvoiceBuilder
{
    public function build(SalesDocument $document): Invoice
    {
        $document->loadMissing(['company', 'customer', 'series', 'items.product', 'currency']);

        $serie = $document->series?->prefix;
        $tipoDoc = $document->documentType?->code;

        if (!$serie || !$tipoDoc) {
            throw new InvalidArgumentException('El documento no tiene serie o tipo de documento configurado.');
        }

        $numero = $this->formatNumber($document->number);
        $moneda = $document->currency?->code ?: 'PEN';
        $fecha = Carbon::parse($document->issue_date)->setTimeFromTimeString('00:00:00');

        $company = $this->mapCompany($document);
        $client = $this->mapClient($document);
        $items = $this->mapItems($document);

        $mtoOperGravadas = $document->subtotal;
        $mtoIgv = $document->tax_total;
        $total = $document->total;

        $invoice = (new Invoice())
            ->setUblVersion('2.1')
            ->setTipoOperacion('0101') // Venta interna
            ->setTipoDoc($tipoDoc) // 01 Factura, 03 Boleta
            ->setSerie($serie)
            ->setCorrelativo($numero)
            ->setFechaEmision($fecha)
            ->setTipoMoneda($moneda)
            ->setCompany($company)
            ->setClient($client)
            ->setMtoOperGravadas(round($mtoOperGravadas, 2))
            ->setMtoIGV(round($mtoIgv, 2))
            ->setTotalImpuestos(round($mtoIgv, 2))
            ->setValorVenta(round($mtoOperGravadas, 2))
            ->setSubTotal(round($mtoOperGravadas + $mtoIgv, 2))
            ->setMtoImpVenta(round($total, 2))
            ->setDetails($items)
            ->setLegends($this->buildLegends($total))
            ->setFormaPago(new FormaPagoContado());

        return $invoice;
    }

    private function mapCompany(SalesDocument $document): GreenterCompany
    {
        $company = $document->company;
        $address = new Address();
        $address->setPais('PE');

        // Campos opcionales, rellenar si existen.
        if ($company?->address) {
            $address->setDireccion($company->address->line1 ?? $company->address->address ?? '');
            $address->setDepartamento($company->address->state ?? '');
            $address->setProvincia($company->address->province ?? '');
            $address->setDistrito($company->address->district ?? '');
            $address->setUbigueo($company->address->ubigeo ?? '');
        } else {
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
            ->setTipoDoc($customer->sunat_doc_type_code) // Usar atributo del modelo
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
        // Greenter genera la leyenda "Monto en letras" con código 1000.
        return [
            (new Legend())
                ->setCode('1000')
                ->setValue($this->numberToWords($total)),
        ];
    }

    private function numberToWords(float $amount): string
    {
        // Placeholder simple. Greenter puede generar leyenda, pero mantenemos fallback en español básico.
        $formatted = number_format($amount, 2, '.', '');
        return "SON {$formatted} SOLES";
    }

    private function resolveCustomerDocType(?string $taxId): string
    {
        $len = strlen((string) $taxId);
        return match ($len) {
            11 => '6', // RUC
            8 => '1', // DNI
            default => '0', // Doc. trib. no dom.
        };
    }

    private function formatNumber($number): string
    {
        return str_pad((string) $number, 8, '0', STR_PAD_LEFT);
    }
}
