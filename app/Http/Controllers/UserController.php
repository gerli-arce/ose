<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:settings.user.manage');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = session('current_company_id');
        $currentCompany = Company::find($companyId);
        
        if (!$currentCompany) {
            return redirect()->route('select.company')->with('error', 'Sesión de empresa inválida.');
        }

        // Obtener CompanyUsers con relaciones eager loaded
        $companyUsers = \App\Models\CompanyUser::where('company_id', $currentCompany->id)
            ->with(['user', 'roles.role']) // Cargar usuario y roles
            ->get();

        // Transformar datos para JS Grid
        $usersData = $companyUsers->map(function($cu) {
            return [
                'id' => $cu->user->id,
                'name' => $cu->user->name,
                'email' => $cu->user->email,
                'roles' => $cu->roles->map(function($r) { return $r->role ? $r->role->name : ''; })->filter()->implode(', '),
                'status' => $cu->user->active ? 'Activo' : 'Suspendido',
                'active' => $cu->user->active,
                'edit_url' => route('users.edit', $cu->user->id),
                'delete_url' => route('users.destroy', $cu->user->id)
            ];
        });

        // Obtener roles y sucursales para los modales
        $roles = \App\Models\Role::all();
        $branches = \App\Models\Branch::where('company_id', $currentCompany->id)->get();

        return view('users.index', compact('usersData', 'roles', 'branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currentCompany = Auth::user()->companies->first();
        $roles = Role::all();
        $branches = Branch::where('company_id', $currentCompany->id)->get();
        
        return view('users.create', compact('roles', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array',
            'branches' => 'array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $currentCompany = Auth::user()->companies->first();

        DB::transaction(function () use ($request, $currentCompany) {
            // 1. Crear Usuario
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('users', 'public');
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'active' => $request->has('active') && $request->active == 'on', // Checkbox sends 'on'
                'is_super_admin' => false,
                'image' => $imagePath,
            ]);

            // 2. Asociar a la Empresa
            $companyUser = \App\Models\CompanyUser::create([
                'company_id' => $currentCompany->id,
                'user_id' => $user->id,
                'is_owner' => false,
                'status' => 'active'
            ]);

            // 3. Asignar Roles
            if ($request->has('roles')) {
                foreach ($request->roles as $roleId) {
                    \App\Models\CompanyUserRole::create([
                        'company_user_id' => $companyUser->id,
                        'role_id' => $roleId
                    ]);
                }
            }

            // 4. Asignar Sucursales
            if ($request->has('branches')) {
                foreach ($request->branches as $branchId) {
                    \App\Models\BranchUser::create([
                        'branch_id' => $branchId,
                        'company_user_id' => $companyUser->id
                    ]);
                }
            }
        });

        return response()->json(['success' => true, 'message' => 'Usuario creado exitosamente.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $currentCompany = Auth::user()->companies->first();
        
        // Obtener datos pivot
        $companyUser = \App\Models\CompanyUser::where('company_id', $currentCompany->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$companyUser) {
            return redirect()->route('users.index')->with('error', 'Usuario no pertenece a su empresa');
        }

        $userRolesIds = \App\Models\CompanyUserRole::where('company_user_id', $companyUser->id)->pluck('role_id')->toArray();
        $userBranchesIds = \App\Models\BranchUser::where('company_user_id', $companyUser->id)->pluck('branch_id')->toArray();

        $roles = Role::all();
        $branches = Branch::where('company_id', $currentCompany->id)->get();

        return view('users.edit', compact('user', 'roles', 'branches', 'userRolesIds', 'userBranchesIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'array',
            'branches' => 'array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $currentCompany = Company::find(session('current_company_id'));

        DB::transaction(function () use ($request, $user, $currentCompany) {
            // 1. Actualizar Usuario Base
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'active' => $request->has('active') && $request->active == 'on',
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('image')) {
                // Eliminar imagen anterior si existe
                if ($user->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->image)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->image);
                }
                $userData['image'] = $request->file('image')->store('users', 'public');
            }

            $user->update($userData);

            // 2. Obtener pivot
            $companyUserPivot = \App\Models\CompanyUser::where('company_id', $currentCompany->id)
                ->where('user_id', $user->id)
                ->first();

            if ($companyUserPivot) {
                // 3. Sincronizar Roles
                \App\Models\CompanyUserRole::where('company_user_id', $companyUserPivot->id)->delete();
                if ($request->has('roles')) {
                    foreach ($request->roles as $roleId) {
                        \App\Models\CompanyUserRole::create([
                            'company_user_id' => $companyUserPivot->id,
                            'role_id' => $roleId
                        ]);
                    }
                }

                // 4. Sincronizar Sucursales
                \App\Models\BranchUser::where('company_user_id', $companyUserPivot->id)->delete();
                if ($request->has('branches')) {
                    foreach ($request->branches as $branchId) {
                        \App\Models\BranchUser::create([
                            'branch_id' => $branchId,
                            'company_user_id' => $companyUserPivot->id
                        ]);
                    }
                }
            }
        });

        return response()->json(['success' => true, 'message' => 'Usuario actualizado exitosamente.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $currentCompany = Auth::user()->companies->first();
        
        // Detach de la empresa (Soft delete lógico en pivot o tabla)
        // Por simplicidad, eliminamos la relación
        // En producción, considerar SoftDeletes
        
        // Verificar si es el usuario autenticado para no auto-eliminarse
        if (Auth::id() == $user->id) {
             return response()->json(['error' => 'No puedes eliminar tu propio usuario.'], 403);
        }
        
        $user->companies()->detach($currentCompany->id);
        
        return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente.']);
    }
}
