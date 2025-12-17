<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DespatchTransferReason;
use App\Models\TransportModality;

/**
 * Seeder para catálogos de Guías de Remisión
 */
class DespatchCatalogsSeeder extends Seeder
{
    public function run(): void
    {
        // Catálogo 20: Motivos de traslado
        $transferReasons = [
            ['code' => '01', 'name' => 'Venta'],
            ['code' => '02', 'name' => 'Compra'],
            ['code' => '03', 'name' => 'Venta con entrega a terceros'],
            ['code' => '04', 'name' => 'Traslado entre establecimientos de la misma empresa'],
            ['code' => '05', 'name' => 'Consignación'],
            ['code' => '06', 'name' => 'Devolución'],
            ['code' => '07', 'name' => 'Recojo de bienes transformados'],
            ['code' => '08', 'name' => 'Importación'],
            ['code' => '09', 'name' => 'Exportación'],
            ['code' => '13', 'name' => 'Otros'],
            ['code' => '14', 'name' => 'Venta sujeta a confirmación del comprador'],
            ['code' => '17', 'name' => 'Traslado de bienes para transformación'],
            ['code' => '18', 'name' => 'Traslado emisor itinerante CP'],
            ['code' => '19', 'name' => 'Traslado a zona primaria'],
        ];

        foreach ($transferReasons as $reason) {
            DespatchTransferReason::updateOrCreate(
                ['code' => $reason['code']],
                ['name' => $reason['name'], 'active' => true]
            );
        }

        // Catálogo 18: Modalidad de transporte
        $modalities = [
            ['code' => '01', 'name' => 'Transporte público'],
            ['code' => '02', 'name' => 'Transporte privado'],
        ];

        foreach ($modalities as $modality) {
            TransportModality::updateOrCreate(
                ['code' => $modality['code']],
                ['name' => $modality['name'], 'active' => true]
            );
        }

        $this->command->info('Catálogos de Guías de Remisión cargados.');
    }
}
