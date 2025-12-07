<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:settings.role.manage');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all roles for JS Grid
        $roles = Role::with('permissions')->get();
        
        // Data for Modals
        $permission_groups = Permission::all()->groupBy('module');
        
        // Transform for Grid
        $rolesData = $roles->map(function($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
                'description' => $role->description,
                'permissions' => $role->permissions->count(), // Count or list names? Count is cleaner for grid
                'edit_url' => route('roles.edit', $role->id),
                'delete_url' => route('roles.destroy', $role->id)
            ];
        });

        return view('roles.index', compact('rolesData', 'permission_groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'slug' => 'required|unique:roles,slug',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Role::create($request->only('name', 'slug', 'description'));

        return response()->json(['success' => true, 'message' => 'Rol creado exitosamente.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $role->id,
            'slug' => 'required|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role->update($request->only('name', 'slug', 'description'));

        return response()->json(['success' => true, 'message' => 'Rol actualizado exitosamente.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Logic to prevent deleting roles in use could go here
        $role->delete();
        return response()->json(['success' => true, 'message' => 'Rol eliminado exitosamente.']);
    }
}
