<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:settings.role.manage');
    }

    /**
     * Show the form for editing permissions of a role.
     */
    public function edit(Role $role)
    {
        // Agrupar permisos por modulo para la vista
        $permissionGroups = Permission::all()->groupBy('module');
        
        // Obtener IDs de permisos actuales del rol
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.permissions', compact('role', 'permissionGroups', 'rolePermissions'));
    }

    /**
     * Update the permissions of a role.
     */
    public function update(Request $request, Role $role)
    {
        $role->permissions()->sync($request->permissions ?? []);
        
        return redirect()->route('roles.index')->with('success', 'Permisos actualizados correctamente.');
    }
}
