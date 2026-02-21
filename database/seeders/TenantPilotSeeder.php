<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyUserRole;
use App\Models\Contact;
use App\Models\DocumentSeries;
use App\Models\DocumentType;
use App\Models\IdentityDocumentType;
use App\Models\Product;
use App\Models\Role;
use App\Models\UnitOfMeasure;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantPilotSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $company = Company::firstOrCreate(
                ['tax_id' => '20987654321'],
                [
                    'name' => 'Cliente Piloto SAC',
                    'trade_name' => 'Piloto',
                    'email' => 'admin@clientepiloto.com',
                    'phone' => '999111222',
                    'active' => true,
                ]
            );

            $branch = Branch::firstOrCreate(
                ['company_id' => $company->id, 'code' => 'PRIN'],
                ['name' => 'Sucursal Principal', 'active' => true]
            );

            $user = User::firstOrCreate(
                ['email' => 'admin@clientepiloto.com'],
                [
                    'name' => 'Admin Cliente Piloto',
                    'password' => Hash::make('Piloto123!'),
                    'is_super_admin' => false,
                    'active' => true,
                ]
            );

            $companyUser = CompanyUser::firstOrCreate(
                ['company_id' => $company->id, 'user_id' => $user->id],
                ['is_owner' => true, 'status' => 'active']
            );

            $adminRole = Role::where('slug', 'admin')->first();
            if ($adminRole) {
                CompanyUserRole::firstOrCreate([
                    'company_user_id' => $companyUser->id,
                    'branch_id' => $branch->id,
                    'role_id' => $adminRole->id,
                ]);
            }

            $this->seedSeries($company->id, $branch->id);
            $this->seedContacts($company->id);
            $this->seedProducts($company->id);

            if ($this->command) {
                $this->command->info("Tenant piloto listo. company_id={$company->id}, branch_id={$branch->id}, user_email={$user->email}");
            }
        });
    }

    private function seedSeries(int $companyId, int $branchId): void
    {
        $seriesByDocCode = [
            '01' => 'F001',
            '03' => 'B001',
            '07' => 'FC01',
            '08' => 'FD01',
            '09' => 'T001',
        ];

        foreach ($seriesByDocCode as $docCode => $prefix) {
            $documentType = DocumentType::where('code', $docCode)->first();
            if (!$documentType) {
                continue;
            }

            DocumentSeries::updateOrCreate(
                [
                    'company_id' => $companyId,
                    'branch_id' => $branchId,
                    'document_type_id' => $documentType->id,
                    'prefix' => $prefix,
                ],
                [
                    'warehouse_id' => null,
                    'current_number' => 0,
                ]
            );
        }
    }

    private function seedContacts(int $companyId): void
    {
        $rucType = IdentityDocumentType::where('code', '6')->first();
        $dniType = IdentityDocumentType::where('code', '1')->first();

        Contact::updateOrCreate(
            ['company_id' => $companyId, 'tax_id' => '20600099991'],
            [
                'identity_document_type_id' => $rucType?->id,
                'type' => 'customer',
                'name' => 'Cliente Piloto RUC SAC',
                'business_name' => 'Cliente Piloto RUC SAC',
                'address' => 'Lima, Peru',
                'active' => true,
            ]
        );

        Contact::updateOrCreate(
            ['company_id' => $companyId, 'tax_id' => '00000000'],
            [
                'identity_document_type_id' => $dniType?->id,
                'type' => 'customer',
                'name' => 'CLIENTE VARIOS',
                'business_name' => 'CLIENTE VARIOS',
                'address' => 'Lima, Peru',
                'active' => true,
            ]
        );
    }

    private function seedProducts(int $companyId): void
    {
        $unit = UnitOfMeasure::where('code', 'ZZ')->first()
            ?? UnitOfMeasure::where('code', 'NIU')->first();

        if (!$unit) {
            return;
        }

        Product::updateOrCreate(
            ['company_id' => $companyId, 'code' => 'SERV-001'],
            [
                'name' => 'Servicio de prueba FE',
                'description' => 'Servicio para pruebas de emision SUNAT',
                'unit_id' => $unit->id,
                'is_service' => true,
                'cost_price' => 0,
                'sale_price' => 100,
                'active' => true,
            ]
        );
    }
}

