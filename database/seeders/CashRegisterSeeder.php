<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CashRegister;
use App\Models\Branch;
use App\Models\Company;

class CashRegisterSeeder extends Seeder
{
    public function run()
    {
        $company = Company::first();
        if(!$company) {
            $this->command->error('No company found. Run DemoSeeder first.');
            return;
        }

        $branch = Branch::where('company_id', $company->id)->first();
        if(!$branch) {
             $this->command->error('No branch found.');
             return;
        }

        CashRegister::firstOrCreate(
            ['name' => 'Caja Principal - ' . $branch->name, 'company_id' => $company->id],
            [
                'branch_id' => $branch->id,
                'status' => 'closed'
            ]
        );
        
        $this->command->info('Cash Register created successfully.');
    }
}
