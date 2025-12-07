<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitOfMeasureSeeder extends Seeder
{
    public function run()
    {
        $units = [
            ['code' => 'NIU', 'name' => 'Unidad'],
            ['code' => 'KGM', 'name' => 'Kilogramo'],
            ['code' => 'MTR', 'name' => 'Metro'],
            ['code' => 'LTR', 'name' => 'Litro'],
            ['code' => 'BX', 'name' => 'Caja'],
        ];

        foreach ($units as $unit) {
            \App\Models\UnitOfMeasure::updateOrCreate(
                ['code' => $unit['code']],
                ['name' => $unit['name']]
            );
        }
    }
}
