<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyUserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminConorldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // 1. Usuario admin
            $user = User::firstOrCreate(
                ['email' => 'admin@conorld.com'],
                [
                    'name' => 'Administrador CONORLD',
                    'password' => Hash::make('asucar123YON'),
                    'is_super_admin' => true,
                    'active' => true,
                ]
            );

            // 2. Empresa demo
            $company = Company::firstOrCreate(
                ['tax_id' => '20123456780'],
                [
                    'name' => 'CONORLD',
                    'trade_name' => 'CONORLD',
                    // Si tienes address_id obligatorio en migraciones, deberías manejarlo, 
                    // pero en el request anterior era nullable.
                    'email' => 'soporte@conorld.com',
                    'phone' => '999888777',
                    'active' => true,
                ]
            );

            // 3. Sucursal demo
            $branch = Branch::firstOrCreate(
                ['code' => 'PICH', 'company_id' => $company->id],
                [
                    'name' => 'PICHANAKI',
                    'active' => true,
                    // address_id es nullable
                ]
            );

            // 4. Rol Admin
            $role = Role::firstOrCreate(
                ['slug' => 'admin'],
                [
                    'name' => 'Administrador',
                    'description' => 'Acceso total a la empresa',
                ]
            );

            // 5. Relación usuario–empresa
            $companyUser = CompanyUser::firstOrCreate(
                [
                    'company_id' => $company->id,
                    'user_id' => $user->id,
                ],
                [
                    'is_owner' => true,
                    'status' => 'active',
                ]
            );

            // 6. Asignar rol en sucursal
            CompanyUserRole::firstOrCreate(
                [
                    'company_user_id' => $companyUser->id,
                    'branch_id' => $branch->id,
                    'role_id' => $role->id,
                ]
            );
        });
    }
}
