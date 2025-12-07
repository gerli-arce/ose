<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockMovement;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Company;

class StockMovementSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        if (!$company) return;

        $warehouses = Warehouse::where('company_id', $company->id)->get();
        $products = Product::where('company_id', $company->id)->get();

        if ($warehouses->isEmpty() || $products->isEmpty()) return;

        // Create initial stock for all products in random warehouses
        foreach ($products as $product) {
            // Pick a random warehouse
            $warehouse = $warehouses->random();
            $qty = rand(10, 50);

            // Avoid duplicate initial entry if re-running
            $exists = StockMovement::where('company_id', $company->id)
                ->where('product_id', $product->id)
                ->where('source_type', 'Initial Inventory')
                ->exists();

            if (!$exists) {
                StockMovement::create([
                    'company_id' => $company->id,
                    'branch_id' => $warehouse->branch_id,
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                    'date' => now()->subDays(rand(1, 30)),
                    'type' => 'in',
                    'quantity' => $qty,
                    'cost_unit' => $product->cost_price,
                    'source_type' => 'Initial Inventory',
                    'observations' => 'Seed Data'
                ]);

                $this->updateStock($company->id, $warehouse->id, $product->id, $qty);
            }
        }
        
        // Random Movements (In/Out)
        for ($i = 0; $i < 20; $i++) {
            $product = $products->random();
            $warehouse = $warehouses->random();
            $type = rand(0, 1) ? 'in' : 'out';
            $qty = rand(1, 5);
            
            // Check stock before out
            if ($type == 'out') {
                $currentStock = Stock::where('company_id', $company->id)
                    ->where('warehouse_id', $warehouse->id)
                    ->where('product_id', $product->id)
                    ->value('quantity') ?? 0;
                
                if ($currentStock < $qty) continue; // Skip if not enough stock
            }

            $date = now()->subDays(rand(0, 10));

            StockMovement::create([
                'company_id' => $company->id,
                'branch_id' => $warehouse->branch_id,
                'warehouse_id' => $warehouse->id,
                'product_id' => $product->id,
                'date' => $date,
                'type' => $type,
                'quantity' => $qty,
                'cost_unit' => $product->cost_price,
                'source_type' => 'Manual Adjustment',
                'observations' => 'Random Movement ' . $i
            ]);

            $finalQty = $type == 'out' ? -$qty : $qty;
            $this->updateStock($company->id, $warehouse->id, $product->id, $finalQty);
        }
    }

    private function updateStock($companyId, $warehouseId, $productId, $qty)
    {
         $stock = Stock::firstOrNew([
            'company_id' => $companyId,
            'warehouse_id' => $warehouseId,
            'product_id' => $productId
        ]);
        $stock->quantity = ($stock->quantity ?? 0) + $qty;
        $stock->save();
    }
}
