<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\UnitOfMeasure;
use App\Models\Company;

class PurchaseModuleSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        if (!$company) return;

        // 1. Proveedor
        $provider = Contact::firstOrCreate(
            ['tax_id' => '20444444444', 'company_id' => $company->id],
            [
                'name' => 'Proveedor Equipos SAC',
                'type' => 'supplier',
                'email' => 'ventas@proveedorequipos.com',
                'phone' => '01-222-3333',
                'address' => 'Av. Tecnologia 123',
                'is_active' => true
            ]
        );

        // 2. Unit & Category
        $unit = UnitOfMeasure::firstOrCreate(
            ['code' => 'NIU', 'company_id' => $company->id],
            ['name' => 'Unidad']
        );
        
        $category = ProductCategory::firstOrCreate(
            ['name' => 'Equipos de Red', 'company_id' => $company->id]
        );

        // 3. Product
        $product = Product::firstOrCreate(
            ['name' => 'Router WiFi', 'company_id' => $company->id],
            [
                'code' => 'P001',
                'product_category_id' => $category->id,
                'unit_id' => $unit->id,
                'is_service' => false,
                'cost_price' => 100.00,
                'sale_price' => 150.00,
                'active' => true
            ]
        );
    }
}
