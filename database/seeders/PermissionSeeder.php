<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyUserRole;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Define Permissions
        $permissions = [
            // Sales
            ['name' => 'Ver Facturas', 'key' => 'sales.invoice.view', 'module' => 'sales'],
            ['name' => 'Crear Facturas', 'key' => 'sales.invoice.create', 'module' => 'sales'],
            ['name' => 'Editar Facturas', 'key' => 'sales.invoice.edit', 'module' => 'sales'],
            ['name' => 'Anular Facturas', 'key' => 'sales.invoice.cancel', 'module' => 'sales'],
            
            // Purchases
            ['name' => 'Ver Compras', 'key' => 'purchases.purchase.view', 'module' => 'purchases'],
            ['name' => 'Crear Compras', 'key' => 'purchases.purchase.create', 'module' => 'purchases'],
            ['name' => 'Editar Compras', 'key' => 'purchases.purchase.edit', 'module' => 'purchases'],
            
            // Inventory
            ['name' => 'Ver Productos', 'key' => 'inventory.product.view', 'module' => 'inventory'],
            ['name' => 'Crear Productos', 'key' => 'inventory.product.create', 'module' => 'inventory'],
            ['name' => 'Editar Productos', 'key' => 'inventory.product.edit', 'module' => 'inventory'],
            ['name' => 'Ajustar Stock', 'key' => 'inventory.stock.adjust', 'module' => 'inventory'],
            
            // Settings
            ['name' => 'Ver Empresa', 'key' => 'settings.company.view', 'module' => 'settings'],
            ['name' => 'Editar Empresa', 'key' => 'settings.company.edit', 'module' => 'settings'],
            ['name' => 'Gestionar Usuarios', 'key' => 'settings.user.manage', 'module' => 'settings'],
            ['name' => 'Gestionar Roles', 'key' => 'settings.role.manage', 'module' => 'settings'],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(['key' => $perm['key']], $perm);
        }

        // 2. Define Roles
        $adminRole = Role::updateOrCreate(['slug' => 'admin'], [
            'name' => 'Administrador',
            'description' => 'Acceso total al sistema',
        ]);
        
        $sellerRole = Role::updateOrCreate(['slug' => 'seller'], [
            'name' => 'Vendedor',
            'description' => 'Acceso limitado a ventas',
        ]);
        
        $accountantRole = Role::updateOrCreate(['slug' => 'accountant'], [
            'name' => 'Contador',
            'description' => 'Acceso a ventas y compras',
        ]);

        // 3. Assign Permissions to Roles
        
        // Admin: All permissions
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions);

        // Seller: Sales permissions only (invoice view/create)
        $sellerPermissions = Permission::whereIn('key', [
            'sales.invoice.view',
            'sales.invoice.create',
            'inventory.product.view' // Usually sellers need to see products
        ])->get();
        $sellerRole->permissions()->sync($sellerPermissions);

        // Accountant: Sales and Purchases view
        $accountantPermissions = Permission::whereIn('key', [
            'sales.invoice.view',
            'purchases.purchase.view',
            'purchases.purchase.create', // Maybe create logic for expenses
            'inventory.product.view'
        ])->get();
        $accountantRole->permissions()->sync($accountantPermissions);

        // 4. Create Example Users (if not exists)
        // Note: Assuming 'CONORLD' company already exists from previous steps or manual entry.
        // We will attach them to the first company found.
        
        $company = Company::first();
        
        if ($company) {
            // Helper to create user
            $this->createUser($company, 'Admin', 'admin@conorld.com', $adminRole);
            $this->createUser($company, 'Ventas', 'ventas@conorld.com', $sellerRole);
            $this->createUser($company, 'Contabilidad', 'contabilidad@conorld.com', $accountantRole);
        }
    }

    private function createUser($company, $name, $email, $role)
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
                'active' => true
            ]
        );

        // Attach to company if not already
        $companyUser = CompanyUser::firstOrCreate(
            ['company_id' => $company->id, 'user_id' => $user->id],
            ['is_owner' => false, 'status' => 'active']
        );

        // Assign Role
        // Check if role already assigned to avoid dups
        // Reload to be sure
        $companyUser->load('roles');
        if (!$companyUser->roles->contains('role_id', $role->id)) {
            CompanyUserRole::create([
                'company_user_id' => $companyUser->id,
                'role_id' => $role->id
            ]);
        }
        
        // Assign first branch if exists
        $branch = $company->branches()->first();
        // Reload branches
        $companyUser->load('branches');
        if ($branch && !$companyUser->branches->contains('branch_id', $branch->id)) {
             \App\Models\BranchUser::create([
                'branch_id' => $branch->id,
                'company_user_id' => $companyUser->id
            ]);
        }
    }
}
