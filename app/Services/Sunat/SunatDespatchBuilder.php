<?php

namespace App\Services\Sunat;

use App\Models\DespatchAdvice;
use Greenter\Model\Despatch\Despatch;
use Greenter\Model\Despatch\DespatchDetail;
use Greenter\Model\Despatch\Direction;
use Greenter\Model\Despatch\Driver;
use Greenter\Model\Despatch\Shipment;
use Greenter\Model\Despatch\Transportist;
use Greenter\Model\Despatch\Vehicle;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;

/**
 * Builder para construir Guías de Remisión para Greenter
 */
class SunatDespatchBuilder
{
    /**
     * Construir objeto Despatch de Greenter
     */
    public function build(DespatchAdvice $despatch): Despatch
    {
        $this->validate($despatch);

        $greenterDespatch = new Despatch();
        
        // Datos básicos
        $greenterDespatch
            ->setTipoDoc('09')
            ->setSerie($despatch->series?->prefix ?? 'T001')
            ->setCorrelativo((string) $despatch->number)
            ->setFechaEmision($despatch->issue_date)
            ->setCompany($this->mapCompany($despatch))
            ->setDestinatario($this->mapDestinatario($despatch))
            ->setEnvio($this->mapShipment($despatch))
            ->setDetails($this->mapItems($despatch));

        // Observación si existe
        if ($despatch->observation) {
            $greenterDespatch->setObservacion($despatch->observation);
        }

        return $greenterDespatch;
    }

    /**
     * Mapear datos de la empresa emisora
     */
    private function mapCompany(DespatchAdvice $despatch): Company
    {
        $company = $despatch->company;

        $address = new Address();
        $address
            ->setUbigueo($company->ubigeo ?? '150101')
            ->setDepartamento($company->department ?? 'LIMA')
            ->setProvincia($company->province ?? 'LIMA')
            ->setDistrito($company->district ?? 'LIMA')
            ->setDireccion($company->address ?? '');

        $greenterCompany = new Company();
        $greenterCompany
            ->setRuc($company->tax_id)
            ->setRazonSocial($company->business_name)
            ->setNombreComercial($company->trade_name ?? $company->business_name)
            ->setAddress($address);

        return $greenterCompany;
    }

    /**
     * Mapear destinatario
     */
    private function mapDestinatario(DespatchAdvice $despatch): Client
    {
        $client = new Client();
        $client
            ->setTipoDoc($despatch->recipient_document_type ?? '6')
            ->setNumDoc($despatch->recipient_document_number ?? $despatch->company->tax_id)
            ->setRznSocial($despatch->recipient_name ?? $despatch->company->business_name);

        return $client;
    }

    /**
     * Mapear datos del envío (shipment)
     */
    private function mapShipment(DespatchAdvice $despatch): Shipment
    {
        $shipment = new Shipment();

        // Datos básicos del traslado
        $shipment
            ->setCodTraslado($despatch->transferReason?->code ?? '01')
            ->setModTraslado($despatch->transportModality?->code ?? '02')
            ->setFecTraslado($despatch->transfer_date)
            ->setPesoTotal($despatch->gross_weight)
            ->setUndPesoTotal('KGM')
            ->setNumBultos($despatch->package_count);

        // Dirección de origen
        $origen = new Direction();
        $origen
            ->setUbigueo($despatch->originUbigeo?->code ?? '150101')
            ->setDireccion($despatch->origin_address);
        $shipment->setLlegada($origen);

        // Dirección de destino
        $destino = new Direction();
        $destino
            ->setUbigueo($despatch->destinationUbigeo?->code ?? '150101')
            ->setDireccion($despatch->destination_address);
        $shipment->setPartida($destino);

        // Transportista (si es transporte público)
        if ($despatch->isPublicTransport() && $despatch->transporter) {
            $transportist = new Transportist();
            $transportist
                ->setTipoDoc('6') // RUC
                ->setNumDoc($despatch->transporter->document_number)
                ->setRznSocial($despatch->transporter->business_name);
            
            if ($despatch->transporter->registration_number) {
                $transportist->setNroMtc($despatch->transporter->registration_number);
            }
            
            $shipment->setTransportista($transportist);
        }

        // Conductor
        if ($despatch->driver_document_number) {
            $driver = new Driver();
            $driver
                ->setTipoDoc($despatch->driver_document_type ?? '1')
                ->setNroDoc($despatch->driver_document_number)
                ->setNombres($despatch->driver_name ?? 'CONDUCTOR')
                ->setNroLicencia($despatch->driver_license ?? '');
            
            $shipment->setChoferes([$driver]);
        }

        // Vehículo
        if ($despatch->vehicle) {
            $vehicle = new Vehicle();
            $vehicle->setPlaca($despatch->vehicle->plate_number);
            
            if ($despatch->vehicle->authorization_code) {
                $vehicle->setNroAutorizacion($despatch->vehicle->authorization_code);
            }
            
            $shipment->setVehiculos([$vehicle]);
        }

        return $shipment;
    }

    /**
     * Mapear items
     */
    private function mapItems(DespatchAdvice $despatch): array
    {
        $items = [];

        foreach ($despatch->items as $item) {
            $detail = new DespatchDetail();
            $detail
                ->setCodigo($item->product?->code ?? 'PROD001')
                ->setDescripcion($item->description)
                ->setUnidad($item->unit_code ?? 'NIU')
                ->setCantidad($item->quantity);

            $items[] = $detail;
        }

        return $items;
    }

    /**
     * Validar datos mínimos
     */
    private function validate(DespatchAdvice $despatch): void
    {
        if (!$despatch->company) {
            throw new \InvalidArgumentException('La guía debe tener una empresa asociada.');
        }

        if (!$despatch->series) {
            throw new \InvalidArgumentException('La guía debe tener una serie asignada.');
        }

        if ($despatch->items->isEmpty()) {
            throw new \InvalidArgumentException('La guía debe tener al menos un item.');
        }

        if (!$despatch->origin_address || !$despatch->destination_address) {
            throw new \InvalidArgumentException('Se requieren direcciones de origen y destino.');
        }

        if ($despatch->gross_weight <= 0) {
            throw new \InvalidArgumentException('El peso bruto debe ser mayor a cero.');
        }
    }
}
