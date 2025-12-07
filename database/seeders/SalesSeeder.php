<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentType;
use App\Models\DocumentSeries;
use App\Models\PaymentMethod;
use App\Models\Branch;
use App\Models\Company;

class SalesSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        if (!$company) return;
        
        $branch = Branch::where('company_id', $company->id)->first();
        if (!$branch) return;

        // 1. Document Types
        DocumentType::firstOrCreate(['code' => '01'], ['name' => 'Factura Electrónica', 'short_name' => 'Factura']);
        DocumentType::firstOrCreate(['code' => '03'], ['name' => 'Boleta de Venta Electrónica', 'short_name' => 'Boleta']);
        DocumentType::firstOrCreate(['code' => '07'], ['name' => 'Nota de Crédito', 'short_name' => 'Nota Crédito']);
        DocumentType::firstOrCreate(['code' => '08'], ['name' => 'Nota de Débito', 'short_name' => 'Nota Débito']);

        // 2. Series (F001, B001 as requested)
        $typeFactura = DocumentType::where('code', '01')->first();
        $typeBoleta = DocumentType::where('code', '03')->first();

        DocumentSeries::firstOrCreate(
            ['branch_id' => $branch->id, 'document_type_id' => $typeFactura->id, 'prefix' => 'F001'],
            ['current_number' => 10, 'company_id' => $company->id]
        );

        DocumentSeries::firstOrCreate(
            ['branch_id' => $branch->id, 'document_type_id' => $typeBoleta->id, 'prefix' => 'B001'],
            ['current_number' => 5, 'company_id' => $company->id]
        );

        // 3. Payment Methods
        PaymentMethod::firstOrCreate(['code' => 'CASH'], ['name' => 'Efectivo']);
        PaymentMethod::firstOrCreate(['code' => 'VISA'], ['name' => 'Tarjeta Visa']);
        // 4. Currencies
        \App\Models\Currency::firstOrCreate(['code' => 'PEN'], ['name' => 'Soles', 'symbol' => 'S/']);
        \App\Models\Currency::firstOrCreate(['code' => 'USD'], ['name' => 'Dólares', 'symbol' => '$']);
    }
}
