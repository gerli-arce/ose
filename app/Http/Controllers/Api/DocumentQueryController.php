<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DocumentQueryService;
use Illuminate\Http\Request;

/**
 * API para consultar RUC/DNI
 */
class DocumentQueryController extends Controller
{
    public function __construct(
        private DocumentQueryService $queryService
    ) {
    }

    /**
     * Consultar RUC
     */
    public function queryRuc(Request $request)
    {
        $ruc = $request->get('ruc');
        
        if (!$ruc) {
            return response()->json([
                'success' => false,
                'message' => 'RUC requerido',
            ], 400);
        }

        // Limpiar RUC (solo números)
        $ruc = preg_replace('/[^0-9]/', '', $ruc);

        if (strlen($ruc) !== 11) {
            return response()->json([
                'success' => false,
                'message' => 'RUC debe tener 11 dígitos',
            ], 400);
        }

        $data = $this->queryService->queryRuc($ruc);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener información del RUC. Verifique el número o intente más tarde.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Consultar DNI
     */
    public function queryDni(Request $request)
    {
        $dni = $request->get('dni');
        
        if (!$dni) {
            return response()->json([
                'success' => false,
                'message' => 'DNI requerido',
            ], 400);
        }

        // Limpiar DNI (solo números)
        $dni = preg_replace('/[^0-9]/', '', $dni);

        if (strlen($dni) !== 8) {
            return response()->json([
                'success' => false,
                'message' => 'DNI debe tener 8 dígitos',
            ], 400);
        }

        $data = $this->queryService->queryDni($dni);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener información del DNI. Verifique el número o intente más tarde.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
