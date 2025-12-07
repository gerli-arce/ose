<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\UnitOfMeasure;
use App\Models\StockMovement;
use App\Models\Stock;
use App\Models\Branch;
use App\Models\Company;

class InventorySeeder extends Seeder
{
    public function run()
    {
        // Assume first company and branch for demo
        $company = Company::first();
        if (!$company) return; // Need a company
        
        $branch = Branch::where('company_id', $company->id)->first();
        if (!$branch) return; // Need a branch

        // 1. Categories
        $catInternet = ProductCategory::firstOrCreate(
            ['name' => 'Internet', 'company_id' => $company->id]
        );
        $catEquipos = ProductCategory::firstOrCreate(
            ['name' => 'Equipos', 'company_id' => $company->id]
        );
        $catServicios = ProductCategory::firstOrCreate(
            ['name' => 'Servicios técnicos', 'company_id' => $company->id]
        );

        // 2. Units (Ensure NIU exists)
        $unitU = UnitOfMeasure::firstOrCreate(['code' => 'NIU'], ['name' => 'Unidad']);
        $unitS = UnitOfMeasure::firstOrCreate(['code' => 'ZZ'], ['name' => 'Servicio']);

        // 3. Products
        // "Internet 50Mb"
        Product::firstOrCreate(
            ['name' => 'Internet 50Mb', 'company_id' => $company->id],
            [
                'code' => 'INT-50',
                'product_category_id' => $catInternet->id,
                'unit_id' => $unitS->id,
                'is_service' => true,
                'sale_price' => 100.00,
                'active' => true
            ]
        );

        // "Router WiFi"
        $prodRouter = Product::firstOrCreate(
            ['name' => 'Router WiFi', 'company_id' => $company->id],
            [
                'code' => 'EQ-ROUTER',
                'product_category_id' => $catEquipos->id,
                'unit_id' => $unitU->id,
                'is_service' => false,
                'sale_price' => 150.00,
                'cost_price' => 80.00,
                'active' => true
            ]
        );

        // "Instalación Servicio"
        Product::firstOrCreate(
            ['name' => 'Instalación Servicio', 'company_id' => $company->id],
            [
                'code' => 'SERV-INST',
                'product_category_id' => $catServicios->id,
                'unit_id' => $unitS->id,
                'is_service' => true,
                'sale_price' => 80.00,
                'active' => true
            ]
        );

        // 4. Warehouse
        $warehouse = Warehouse::firstOrCreate(
            ['name' => 'Almacén Principal PICHANAKI', 'company_id' => $company->id],
            [
                'branch_id' => $branch->id,
                'code' => 'WH-PICHANAKI',
                'active' => true
            ]
        );

        // 5. Movements & Stocks logic
        // Only if no movements exist to avoid duplication on re-run
        if (StockMovement::count() == 0) {
            
            // Purchase 1: +10 routers on 2025-12-01
            $this->registerMovement($company, $branch, $warehouse, $prodRouter, 'in', 10, '2025-12-01 10:00:00', 'Compra inicial');
            
            // Purchase 2: +10 routers on 2025-12-02
            $this->registerMovement($company, $branch, $warehouse, $prodRouter, 'in', 10, '2025-12-02 10:00:00', 'Segunda compra');
            
            // Sale: -5 routers on 2025-12-03
            $this->registerMovement($company, $branch, $warehouse, $prodRouter, 'out', 5, '2025-12-03 14:00:00', 'Venta Factura F001-1');

            // Expected Stock: 15
        }
    }

    private function registerMovement($company, $branch, $warehouse, $product, $type, $qty, $date, $obs)
    {
        // 1. Create Movement
        StockMovement::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'date' => $date,
            'type' => $type,
            'quantity' => $qty,
            'cost_unit' => $product->cost_price,
            'source_type' => 'manual',
            'observations' => $obs
        ]);

        // 2. Update Stock
        $stock = Stock::firstOrNew([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id
        ]);
        
        if (!$stock->exists) $stock->quantity = 0;

        if ($type == 'in') $stock->quantity += $qty;
        if ($type == 'out') $stock->quantity -= $qty;

        $stock->save();
    }
}
