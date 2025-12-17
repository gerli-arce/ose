<?php

namespace Database\Seeders;

use App\Models\CreditNoteType;
use App\Models\DebitNoteType;
use Illuminate\Database\Seeder;

/**
 * Seeder para Catálogos SUNAT Nº 09 y Nº 10
 * - Tipos de nota de crédito electrónica
 * - Tipos de nota de débito electrónica
 */
class NoteTypesSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCreditNoteTypes();
        $this->seedDebitNoteTypes();
    }

    /**
     * Catálogo SUNAT Nº 09: Códigos de tipo de nota de crédito electrónica
     */
    private function seedCreditNoteTypes(): void
    {
        $types = [
            ['code' => '01', 'name' => 'Anulación de la operación', 'affects_stock' => true],
            ['code' => '02', 'name' => 'Anulación por error en el RUC', 'affects_stock' => false],
            ['code' => '03', 'name' => 'Corrección por error en la descripción', 'affects_stock' => false],
            ['code' => '04', 'name' => 'Descuento global', 'affects_stock' => false],
            ['code' => '05', 'name' => 'Descuento por ítem', 'affects_stock' => false],
            ['code' => '06', 'name' => 'Devolución total', 'affects_stock' => true],
            ['code' => '07', 'name' => 'Devolución por ítem', 'affects_stock' => true],
            ['code' => '08', 'name' => 'Bonificación', 'affects_stock' => false],
            ['code' => '09', 'name' => 'Disminución en el valor', 'affects_stock' => false],
            ['code' => '10', 'name' => 'Otros conceptos', 'affects_stock' => false],
            ['code' => '11', 'name' => 'Ajustes de operaciones de exportación', 'affects_stock' => false],
            ['code' => '12', 'name' => 'Ajustes afectos al IVAP', 'affects_stock' => false],
            ['code' => '13', 'name' => 'Corrección del monto neto pendiente de pago y/o la(s) fecha(s) de vencimiento del pago único o de las cuotas y/o los montos correspondientes a cada cuota, de ser el caso', 'affects_stock' => false],
        ];

        foreach ($types as $type) {
            CreditNoteType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }

    /**
     * Catálogo SUNAT Nº 10: Códigos de tipo de nota de débito electrónica
     */
    private function seedDebitNoteTypes(): void
    {
        $types = [
            ['code' => '01', 'name' => 'Intereses por mora'],
            ['code' => '02', 'name' => 'Aumento en el valor'],
            ['code' => '03', 'name' => 'Penalidades/ otros conceptos'],
            ['code' => '10', 'name' => 'Ajustes de operaciones de exportación'],
            ['code' => '11', 'name' => 'Ajustes afectos al IVAP'],
        ];

        foreach ($types as $type) {
            DebitNoteType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
