<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Warehouse;

class SalesSettingsSeeder extends Seeder
{
    public function run()
    {
        // 1. Document Types (Global or per company? Schema suggests global or shared)
        // Check if table has company_id. Migration 2025_12_07_025946_create_document_types_table.php does NOT have company_id.
        // So these are system-wide types.
        $types = [
            ['code' => '01', 'name' => 'Factura Electrónica', 'affects_stock' => 1],
            ['code' => '03', 'name' => 'Boleta de Venta Electrónica', 'affects_stock' => 1],
            ['code' => '07', 'name' => 'Nota de Crédito', 'affects_stock' => 1], // Depends on type
            ['code' => '08', 'name' => 'Nota de Débito', 'affects_stock' => 0],
            ['code' => '09', 'name' => 'Guía de Remisión', 'affects_stock' => 1],
        ];

        foreach ($types as $type) {
            DB::table('document_types')->updateOrInsert(
                ['code' => $type['code']],
                ['name' => $type['name'], 'affects_stock' => $type['affects_stock'], 'created_at' => now(), 'updated_at' => now()]
            );
        }

        // 2. Payment Methods
        // Migration 2025_12_07_025945_create_payment_methods_table.php
        $methods = [
            ['code' => 'CASH', 'name' => 'Efectivo'],
            ['code' => 'VISA', 'name' => 'Visa'],
            ['code' => 'MC', 'name' => 'Mastercard'],
            ['code' => 'TRF', 'name' => 'Transferencia Bancaria'],
            ['code' => 'YAPE', 'name' => 'Yape'],
            ['code' => 'PLIN', 'name' => 'Plin'],
        ];

        foreach ($methods as $method) {
             DB::table('payment_methods')->updateOrInsert(
                ['code' => $method['code']],
                ['name' => $method['name'], 'created_at' => now(), 'updated_at' => now()]
             );
        }

        // 3. Series for existing Companies/Branches
        $company = Company::first();
        if (!$company) return;

        $branch = Branch::where('company_id', $company->id)->first();
        $warehouse = Warehouse::where('company_id', $company->id)->first(); // Optional, some series are warehouse specific
        
        if (!$branch) return;

        $facturaType = DB::table('document_types')->where('code', '01')->first();
        $boletaType = DB::table('document_types')->where('code', '03')->first();
        $ncType = DB::table('document_types')->where('code', '07')->first();

        $series = [
            [
                'document_type_id' => $facturaType->id,
                'prefix' => 'F001',
                'current_number' => 0
            ],
            [
                'document_type_id' => $boletaType->id,
                'prefix' => 'B001',
                'current_number' => 0
            ],
             [
                'document_type_id' => $ncType->id,
                'prefix' => 'FC01',
                'current_number' => 0 // For Factura NC
            ],
             [
                'document_type_id' => $ncType->id,
                'prefix' => 'BC01',
                'current_number' => 0 // For Boleta NC
            ],
        ];

        foreach ($series as $s) {
            DB::table('document_series')->updateOrInsert(
                [
                    'company_id' => $company->id,
                    'branch_id' => $branch->id,
                    'document_type_id' => $s['document_type_id'],
                    'prefix' => $s['prefix']
                ],
                [
                    'warehouse_id' => $warehouse ? $warehouse->id : null,
                    'current_number' => $s['current_number'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
