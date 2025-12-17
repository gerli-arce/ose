<?php

namespace App\Services;

use App\Models\DocumentQuery;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para consultar RUC y DNI en APIs externas
 */
class DocumentQueryService
{
    // APIs gratuitas disponibles
    const API_APIPERU = 'https://apiperu.dev/api';
    const API_DNIRUC = 'https://dniruc.apisperu.com/api/v1';
    
    /**
     * Consultar RUC
     */
    public function queryRuc(string $ruc): ?array
    {
        // Validar formato
        if (!$this->isValidRuc($ruc)) {
            return null;
        }

        // Verificar caché
        $cached = DocumentQuery::getCached(DocumentQuery::TYPE_RUC, $ruc);
        if ($cached) {
            Log::info("RUC {$ruc} obtenido de caché");
            return $this->formatRucData($cached);
        }

        // Consultar API
        try {
            $data = $this->fetchRucFromApi($ruc);
            
            if ($data) {
                // Guardar en caché
                DocumentQuery::cache(DocumentQuery::TYPE_RUC, $ruc, $data, $data['source'] ?? 'unknown');
                
                return $data;
            }
        } catch (\Exception $e) {
            Log::error("Error al consultar RUC {$ruc}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Consultar DNI
     */
    public function queryDni(string $dni): ?array
    {
        // Validar formato
        if (!$this->isValidDni($dni)) {
            return null;
        }

        // Verificar caché
        $cached = DocumentQuery::getCached(DocumentQuery::TYPE_DNI, $dni);
        if ($cached) {
            Log::info("DNI {$dni} obtenido de caché");
            return $this->formatDniData($cached);
        }

        // Consultar API
        try {
            $data = $this->fetchDniFromApi($dni);
            
            if ($data) {
                // Guardar en caché
                DocumentQuery::cache(DocumentQuery::TYPE_DNI, $dni, $data, $data['source'] ?? 'unknown');
                
                return $data;
            }
        } catch (\Exception $e) {
            Log::error("Error al consultar DNI {$dni}: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Consultar RUC en API externa
     */
    private function fetchRucFromApi(string $ruc): ?array
    {
        // 1. Intentar con api.apis.net.pe (v1 - suele ser gratuita/limitada)
        try {
            $response = Http::timeout(5)->get('https://api.apis.net.pe/v1/ruc?numero=' . $ruc);
            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['nombre'])) {
                    return [
                        'business_name' => $data['nombre'],
                        'trade_name' => null,
                        'address' => $data['direccion'] ?? null,
                        'ubigeo' => $data['ubigeo'] ?? null,
                        'department' => $data['departamento'] ?? null,
                        'province' => $data['provincia'] ?? null,
                        'district' => $data['distrito'] ?? null,
                        'condition' => $data['condicion'] ?? 'HABIDO',
                        'state' => $data['estado'] ?? 'ACTIVO',
                        'raw_data' => $data,
                        'source' => 'apis.net.pe',
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning("Error con apis.net.pe: " . $e->getMessage());
        }

        // 2. Intentar con apiperu.dev
        try {
            $response = Http::timeout(5)->get(self::API_APIPERU . '/ruc/' . $ruc);
            
            if ($response->successful() && $response->json('success')) {
                $data = $response->json('data');
                
                return [
                    'business_name' => $data['nombre_o_razon_social'] ?? null,
                    'trade_name' => $data['nombre_comercial'] ?? null,
                    'address' => $data['direccion_completa'] ?? $data['direccion'] ?? null,
                    'ubigeo' => $data['ubigeo'] ?? null,
                    'department' => $data['departamento'] ?? null,
                    'province' => $data['provincia'] ?? null,
                    'district' => $data['distrito'] ?? null,
                    'condition' => $data['condicion'] ?? null,
                    'state' => $data['estado'] ?? null,
                    'raw_data' => $data,
                    'source' => 'apiperu.dev',
                ];
            }
        } catch (\Exception $e) {
            Log::warning("Error con apiperu.dev: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Consultar DNI en API externa
     */
    private function fetchDniFromApi(string $dni): ?array
    {
        // 1. Intentar con api.apis.net.pe (v1)
        try {
            $response = Http::timeout(5)->get('https://api.apis.net.pe/v1/dni?numero=' . $dni);
            if ($response->successful()) {
                $data = $response->json();
                if (!empty($data['nombre'])) {
                     return [
                        'name' => $data['nombre'],
                        'raw_data' => $data,
                        'source' => 'apis.net.pe',
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning("Error con apis.net.pe DNI: " . $e->getMessage());
        }

        // 2. Intentar con apiperu.dev
        try {
            $response = Http::timeout(5)->get(self::API_APIPERU . '/dni/' . $dni);
            
            if ($response->successful() && $response->json('success')) {
                $data = $response->json('data');
                
                $fullName = trim(
                    ($data['nombres'] ?? '') . ' ' . 
                    ($data['apellido_paterno'] ?? '') . ' ' . 
                    ($data['apellido_materno'] ?? '')
                );
                
                return [
                    'name' => $fullName,
                    'raw_data' => $data,
                    'source' => 'apiperu.dev',
                ];
            }
        } catch (\Exception $e) {
            Log::warning("Error con apiperu.dev DNI: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Formatear datos de RUC desde caché
     */
    private function formatRucData(DocumentQuery $query): array
    {
        return [
            'business_name' => $query->business_name,
            'trade_name' => $query->trade_name,
            'address' => $query->address,
            'ubigeo' => $query->ubigeo,
            'department' => $query->department,
            'province' => $query->province,
            'district' => $query->district,
            'condition' => $query->condition,
            'state' => $query->state,
            'source' => $query->source . ' (cached)',
        ];
    }

    /**
     * Formatear datos de DNI desde caché
     */
    private function formatDniData(DocumentQuery $query): array
    {
        return [
            'name' => $query->name,
            'source' => $query->source . ' (cached)',
        ];
    }

    /**
     * Validar formato de RUC
     */
    private function isValidRuc(string $ruc): bool
    {
        return preg_match('/^\d{11}$/', $ruc);
    }

    /**
     * Validar formato de DNI
     */
    private function isValidDni(string $dni): bool
    {
        return preg_match('/^\d{8}$/', $dni);
    }
}
