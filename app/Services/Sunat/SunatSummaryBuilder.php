<?php

namespace App\Services\Sunat;

use App\Models\DailySummary;
use App\Models\DailySummaryItem;
use App\Models\SalesDocument;
use Carbon\Carbon;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company as GreenterCompany;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Greenter\Model\Summary\SummaryPerception;
use InvalidArgumentException;

/**
 * Construye el objeto Summary de Greenter para Resumen Diario de Boletas
 */
class SunatSummaryBuilder
{
    /**
     * Construir objeto Summary para Greenter
     */
    public function build(DailySummary $summary): Summary
    {
        $summary->loadMissing(['company', 'items.salesDocument.series', 'items.salesDocument.customer']);

        $this->validate($summary);

        $company = $this->mapCompany($summary);
        $details = $this->mapDetails($summary);

        // Extraer partes del identificador: RC-YYYYMMDD-#####
        $parts = explode('-', $summary->identifier);
        $correlativo = $parts[2] ?? '00001';

        $summaryObj = (new Summary())
            ->setCorrelativo($correlativo)
            ->setFecGeneracion(Carbon::parse($summary->summary_date))
            ->setFecResumen(Carbon::parse($summary->reference_date))
            ->setCompany($company)
            ->setDetails($details);

        return $summaryObj;
    }

    private function validate(DailySummary $summary): void
    {
        if (!$summary->company) {
            throw new InvalidArgumentException('El resumen diario no tiene empresa asociada.');
        }

        if ($summary->items->isEmpty()) {
            throw new InvalidArgumentException('El resumen diario debe tener al menos un documento.');
        }
    }

    private function mapCompany(DailySummary $summary): GreenterCompany
    {
        $company = $summary->company;
        
        $address = new Address();
        $address->setPais('PE');
        $address->setDireccion($company->address ?? '');

        return (new GreenterCompany())
            ->setRuc($company->tax_id)
            ->setRazonSocial($company->business_name ?? $company->name)
            ->setNombreComercial($company->trade_name ?? $company->name)
            ->setAddress($address);
    }

    private function mapDetails(DailySummary $summary): array
    {
        $details = [];

        foreach ($summary->items as $item) {
            $document = $item->salesDocument;
            $customer = $document?->customer;

            $detail = (new SummaryDetail())
                ->setTipoDoc($item->document_type_code)
                ->setSerieNro("{$item->series}-" . str_pad($item->start_number, 8, '0', STR_PAD_LEFT))
                ->setEstado($item->status_code)
                ->setClienteTipo($customer?->sunat_doc_type_code ?? '1')
                ->setClienteNro($customer?->tax_id ?? '00000000')
                ->setTotal(round($item->total, 2))
                ->setMtoOperGravadas(round($item->total_gravadas, 2))
                ->setMtoOperExoneradas(round($item->total_exoneradas, 2))
                ->setMtoOperInafectas(round($item->total_inafectas, 2))
                ->setMtoOperExportacion(round($item->total_exportacion, 2))
                ->setMtoOtrosCargos(round($item->total_otros, 2))
                ->setMtoIGV(round($item->total_igv, 2))
                ->setMtoISC(round($item->total_isc, 2));

            // Si es anulación, los montos van en 0
            if ($item->status_code === DailySummaryItem::STATUS_ANNUL) {
                $detail->setTotal(0)
                       ->setMtoOperGravadas(0)
                       ->setMtoOperExoneradas(0)
                       ->setMtoOperInafectas(0)
                       ->setMtoIGV(0);
            }

            $details[] = $detail;
        }

        return $details;
    }

    /**
     * Crear un resumen diario a partir de boletas pendientes
     */
    public function createFromPendingBoletas(int $companyId, \DateTime $referenceDate): ?DailySummary
    {
        $boletas = DailySummary::getPendingBoletas($companyId, $referenceDate);

        if ($boletas->isEmpty()) {
            return null;
        }

        $today = now();
        $identifier = DailySummary::generateIdentifier($companyId, $today);

        $summary = DailySummary::create([
            'company_id' => $companyId,
            'identifier' => $identifier,
            'summary_date' => $today,
            'reference_date' => $referenceDate,
            'status' => 'pending',
            'total_documents' => $boletas->count(),
            'total_amount' => $boletas->sum('total'),
        ]);

        foreach ($boletas as $boleta) {
            $this->createSummaryItem($summary, $boleta, DailySummaryItem::STATUS_ADD);
        }

        return $summary;
    }

    /**
     * Crear item de resumen para una boleta
     */
    private function createSummaryItem(DailySummary $summary, SalesDocument $document, string $statusCode): DailySummaryItem
    {
        $item = DailySummaryItem::create([
            'daily_summary_id' => $summary->id,
            'sales_document_id' => $document->id,
            'document_type_code' => $document->documentType?->code ?? '03',
            'series' => $document->series?->prefix ?? 'B001',
            'start_number' => $document->number,
            'end_number' => $document->number,
            'status_code' => $statusCode,
            'total_gravadas' => $document->subtotal ?? 0,
            'total_exoneradas' => 0,
            'total_inafectas' => 0,
            'total_exportacion' => 0,
            'total_gratuitas' => 0,
            'total_igv' => $document->tax_total ?? 0,
            'total_isc' => 0,
            'total_otros' => 0,
            'total' => $document->total ?? 0,
        ]);

        // Vincular documento con resumen
        $document->daily_summary_id = $summary->id;
        $document->save();

        return $item;
    }

    /**
     * Agregar boleta para anulación en resumen
     */
    public function addBoletaForAnnulment(DailySummary $summary, SalesDocument $document): DailySummaryItem
    {
        return $this->createSummaryItem($summary, $document, DailySummaryItem::STATUS_ANNUL);
    }
}
