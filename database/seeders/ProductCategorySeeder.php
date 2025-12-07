<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use App\Models\Company;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        if (!$company) return;

        // Clear existing for idempotency (optional, but good for "updating" data)
        // ProductCategory::where('company_id', $company->id)->delete(); 
        // Better to use updateOrCreate to avoid breaking FKs if run multiple times

        $categories = [
            ['name' => 'Electrónica', 'code' => 'ELEC'],
            ['name' => 'Ropa y Textiles', 'code' => 'ROPA'],
            ['name' => 'Hogar y Cocina', 'code' => 'HOGAR'],
            ['name' => 'Juguetes', 'code' => 'JUG'],
            ['name' => 'Herramientas', 'code' => 'HERR'],
        ];

        foreach ($categories as $cat) {
            ProductCategory::updateOrCreate(
                ['company_id' => $company->id, 'name' => $cat['name']],
                ['code' => $cat['code']]
            );
        }
    }
}
