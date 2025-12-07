<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\DocumentType;
use App\Models\PaymentMethod;
use App\Models\UnitOfMeasure;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        // Currencies
        Currency::create(['code' => 'PEN', 'name' => 'Soles', 'symbol' => 'S/']);
        Currency::create(['code' => 'USD', 'name' => 'Dólares Americanos', 'symbol' => '$']);

        // Units
        UnitOfMeasure::create(['code' => 'NIU', 'name' => 'Unidad']);
        UnitOfMeasure::create(['code' => 'KGM', 'name' => 'Kilogramo']);
        UnitOfMeasure::create(['code' => 'LTR', 'name' => 'Litro']);
        UnitOfMeasure::create(['code' => 'ZZ', 'name' => 'Servicio']);

        // Payment Methods
        PaymentMethod::create(['name' => 'Efectivo', 'code' => '001']);
        PaymentMethod::create(['name' => 'Transferencia', 'code' => '002']);
        PaymentMethod::create(['name' => 'Tarjeta de Crédito', 'code' => '003']);

        // Document Types
        DocumentType::create(['code' => '01', 'name' => 'Factura Electrónica', 'affects_stock' => true]);
        DocumentType::create(['code' => '03', 'name' => 'Boleta de Venta', 'affects_stock' => true]);
        DocumentType::create(['code' => '07', 'name' => 'Nota de Crédito', 'affects_stock' => true]);
        DocumentType::create(['code' => '08', 'name' => 'Nota de Débito', 'affects_stock' => false]);
    }
}
