<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ubigeo;
use Illuminate\Http\Request;

/**
 * API para selectores encadenados de ubigeos
 */
class UbigeoController extends Controller
{
    /**
     * Obtener todos los departamentos
     */
    public function departments()
    {
        $departments = Ubigeo::getDepartments();
        
        return response()->json([
            'success' => true,
            'data' => $departments->map(fn($d) => [
                'id' => $d->id,
                'code' => $d->code,
                'name' => $d->name,
            ]),
        ]);
    }

    /**
     * Obtener provincias de un departamento
     */
    public function provinces(Request $request)
    {
        $departmentCode = $request->get('department_code');
        
        if (!$departmentCode) {
            return response()->json([
                'success' => false,
                'message' => 'Código de departamento requerido',
            ], 400);
        }

        $provinces = Ubigeo::getProvincesByDepartment($departmentCode);
        
        return response()->json([
            'success' => true,
            'data' => $provinces->map(fn($p) => [
                'id' => $p->id,
                'code' => $p->code,
                'name' => $p->name,
            ]),
        ]);
    }

    /**
     * Obtener distritos de una provincia
     */
    public function districts(Request $request)
    {
        $provinceCode = $request->get('province_code');
        
        if (!$provinceCode) {
            return response()->json([
                'success' => false,
                'message' => 'Código de provincia requerido',
            ], 400);
        }

        $districts = Ubigeo::getDistrictsByProvince($provinceCode);
        
        return response()->json([
            'success' => true,
            'data' => $districts->map(fn($d) => [
                'id' => $d->id,
                'code' => $d->code,
                'name' => $d->name,
            ]),
        ]);
    }

    /**
     * Buscar ubigeo por código
     */
    public function show(string $code)
    {
        $ubigeo = Ubigeo::where('code', $code)->first();
        
        if (!$ubigeo) {
            return response()->json([
                'success' => false,
                'message' => 'Ubigeo no encontrado',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $ubigeo->id,
                'code' => $ubigeo->code,
                'name' => $ubigeo->name,
                'full_name' => $ubigeo->full_name,
                'level' => $ubigeo->level,
                'department' => $ubigeo->getDepartment()?->name,
                'province' => $ubigeo->getProvince()?->name,
            ],
        ]);
    }
}
