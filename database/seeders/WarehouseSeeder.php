<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\Company;
use App\Models\Branch;

class WarehouseSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        if (!$company) return;

        $branch = Branch::where('company_id', $company->id)->first();
        if (!$branch) return;

        $warehouses = [
            ['name' => 'ALMACEN PICHANAKI', 'code' => 'PCH'],
            ['name' => 'ALMACEN MAZAMARY', 'code' => 'MZM'],
        ];

        foreach ($warehouses as $wh) {
            Warehouse::updateOrCreate(
                ['company_id' => $company->id, 'name' => $wh['name']],
                ['branch_id' => $branch->id, 'code' => $wh['code'], 'active' => true]
            );
        }
    }
}
