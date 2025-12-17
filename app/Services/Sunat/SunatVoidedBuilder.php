<?php

namespace App\Services\Sunat;

use App\Models\VoidedDocument;
use Carbon\Carbon;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company as GreenterCompany;
use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;
use InvalidArgumentException;

/**
 * Construye el objeto Voided de Greenter para Comunicación de Baja
 */
class SunatVoidedBuilder
{
    /**
     * Construir objeto Voided para Greenter
     */
    public function build(VoidedDocument $voidedDocument): Voided
    {
        $voidedDocument->loadMissing(['company', 'items.salesDocument.series', 'items.salesDocument.documentType']);

        $this->validate($voidedDocument);

        $company = $this->mapCompany($voidedDocument);
        $details = $this->mapDetails($voidedDocument);

        // Extraer partes del identificador: RA-YYYYMMDD-#####
        $parts = explode('-', $voidedDocument->identifier);
        $correlativo = $parts[2] ?? '00001';

        $voided = (new Voided())
            ->setCorrelativo($correlativo)
            ->setFecVoided(Carbon::parse($voidedDocument->voided_date))
            ->setFecComunicacion(Carbon::parse($voidedDocument->voided_date))
            ->setCompany($company)
            ->setDetails($details);

        return $voided;
    }

    private function validate(VoidedDocument $voidedDocument): void
    {
        if (!$voidedDocument->company) {
            throw new InvalidArgumentException('La comunicación de baja no tiene empresa asociada.');
        }

        if ($voidedDocument->items->isEmpty()) {
            throw new InvalidArgumentException('La comunicación de baja debe tener al menos un documento.');
        }

        // Validar que solo haya facturas (01), notas de crédito (07) o notas de débito (08)
        // Las boletas (03) se anulan mediante Resumen Diario
        foreach ($voidedDocument->items as $item) {
            if ($item->document_type_code === '03') {
                throw new InvalidArgumentException(
                    'Las boletas de venta (03) no pueden anularse por Comunicación de Baja. Use Resumen Diario.'
                );
            }
        }
    }

    private function mapCompany(VoidedDocument $voidedDocument): GreenterCompany
    {
        $company = $voidedDocument->company;
        
        $address = new Address();
        $address->setPais('PE');
        $address->setDireccion($company->address ?? '');

        return (new GreenterCompany())
            ->setRuc($company->tax_id)
            ->setRazonSocial($company->business_name ?? $company->name)
            ->setNombreComercial($company->trade_name ?? $company->name)
            ->setAddress($address);
    }

    private function mapDetails(VoidedDocument $voidedDocument): array
    {
        return $voidedDocument->items->map(function ($item) {
            return (new VoidedDetail())
                ->setTipoDoc($item->document_type_code)
                ->setSerie($item->series)
                ->setCorrelativo((string) $item->number)
                ->setDesMotivoBaja($item->reason);
        })->all();
    }
}
