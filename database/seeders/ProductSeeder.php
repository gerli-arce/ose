<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\UnitOfMeasure;
use App\Models\Company;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        if (!$company) return;

        $categories = ProductCategory::where('company_id', $company->id)->get();
        $unit = UnitOfMeasure::where('code', 'NIU')->first();

        if ($categories->isEmpty() || !$unit) return;

        $products = [
            ['name' => 'Laptop HP Pavilion', 'price' => 3500.00, 'cost' => 2800.00],
            ['name' => 'Mouse Logitech G203', 'price' => 80.00, 'cost' => 45.00],
            ['name' => 'Teclado Mecánico Redragon', 'price' => 150.00, 'cost' => 90.00],
            ['name' => 'Monitor Samsung 24"', 'price' => 600.00, 'cost' => 450.00],
            ['name' => 'Impresora Epson L3150', 'price' => 700.00, 'cost' => 550.00],
            ['name' => 'Camiseta Algodón Talla M', 'price' => 25.00, 'cost' => 10.00],
            ['name' => 'Pantalón Jean Clásico', 'price' => 80.00, 'cost' => 40.00],
            ['name' => 'Juego de Satenes Antiadl.', 'price' => 120.00, 'cost' => 70.00],
            ['name' => 'Licuadora Oster', 'price' => 150.00, 'cost' => 100.00],
            ['name' => 'Taladro Percutor Bosch', 'price' => 250.00, 'cost' => 180.00],
            ['name' => 'Juego de Destornilladores', 'price' => 40.00, 'cost' => 20.00],
            ['name' => 'Muñeca Articulada', 'price' => 60.00, 'cost' => 30.00],
            ['name' => 'Carro a Control Remoto', 'price' => 90.00, 'cost' => 50.00],
        ];

        foreach ($products as $index => $prod) {
            Product::updateOrCreate(
                ['company_id' => $company->id, 'name' => $prod['name']],
                [
                    'product_category_id' => $categories->random()->id,
                    'unit_id' => $unit->id,
                    'code' => 'PROD-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'sale_price' => $prod['price'],
                    'cost_price' => $prod['cost'],
                    'active' => true,
                    'is_service' => false
                ]
            );
        }
    }
}
