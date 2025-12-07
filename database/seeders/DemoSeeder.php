<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Address
            $address = Address::create([
                'line1' => 'Av. Demo 123',
                'line2' => 'Oficina 404',
                'postal_code' => '15001'
            ]);

            // Company
            $company = Company::create([
                'name' => 'Empresa Demo SAC',
                'trade_name' => 'DemoTech',
                'tax_id' => '20102030401',
                'email' => 'contacto@demotech.com',
                'address_id' => $address->id,
                'active' => true
            ]);

            // Branches
            Branch::create([
                'company_id' => $company->id,
                'name' => 'Oficina Principal',
                'code' => '0000',
                'address_id' => $address->id,
                'active' => true
            ]);

            Branch::create([
                'company_id' => $company->id,
                'name' => 'Sucursal Norte',
                'code' => '0001',
                'active' => true
            ]);

            // User
            $user = User::create([
                'name' => 'Admin User',
                'email' => 'admin@demo.com',
                'password' => Hash::make('password'),
                'is_super_admin' => true,
                'active' => true
            ]);

            // Attach user to company as owner
            $company->users()->attach($user->id, [
                'is_owner' => true,
                'status' => 'active'
            ]);
        });
    }
}
