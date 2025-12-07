<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAuthSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar tabla (opcional, o usar RefreshDatabase en tests, aquí usamos firstOrCreate)

        $modules = [
            'Ventas' => ['Ver', 'Crear', 'Editar', 'Eliminar', 'Anular'],
            'Compras' => ['Ver', 'Crear', 'Editar', 'Eliminar', 'Aprobar'],
            'Inventario' => ['Ver', 'Ajustar', 'Transferir', 'Kardex'],
            'Configuración' => ['Empresa', 'Usuarios', 'Roles', 'Sucursales'],
            'Reportes' => ['Ventas', 'Compras', 'Financiero', 'Inventario']
        ];

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $permissionName = $action . ' ' . $module;
                $key = strtolower(\Illuminate\Support\Str::slug($module . '.' . $action));
                
                Permission::firstOrCreate(
                    ['key' => $key],
                    [
                        'name' => $permissionName,
                        'module' => $module,
                        'description' => "Permite $action en el módulo de $module"
                    ]
                );
            }
        }

        // Roles Base (si no existen)
        if (!Role::where('slug', 'admin')->exists()) {
            $admin = Role::create(['name' => 'Administrador', 'slug' => 'admin', 'description' => 'Acceso total al sistema']);
            // Assign all permissions to Admin
            $allPermissions = Permission::all();
            $admin->permissions()->sync($allPermissions);
        }

        if (!Role::where('slug', 'vendedor')->exists()) {
            Role::create(['name' => 'Vendedor', 'slug' => 'vendedor', 'description' => 'Acceso limitado a ventas']);
        }
    }
}
