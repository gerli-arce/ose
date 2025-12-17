<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Caché de consultas de RUC/DNI
 */
class DocumentQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type',
        'document_number',
        'name',
        'business_name',
        'trade_name',
        'address',
        'ubigeo',
        'department',
        'province',
        'district',
        'condition',
        'state',
        'raw_data',
        'source',
        'queried_at',
    ];

    protected $casts = [
        'raw_data' => 'array',
        'queried_at' => 'datetime',
    ];

    // Tipos de documento
    const TYPE_RUC = 'RUC';
    const TYPE_DNI = 'DNI';

    /**
     * Verificar si la consulta es reciente (menos de 30 días)
     */
    public function isRecent(): bool
    {
        return $this->queried_at->diffInDays(now()) < 30;
    }

    /**
     * Verificar si está activo
     */
    public function isActive(): bool
    {
        return strtoupper($this->condition ?? '') === 'ACTIVO';
    }

    /**
     * Verificar si está habido
     */
    public function isHabido(): bool
    {
        return strtoupper($this->state ?? '') === 'HABIDO';
    }

    /**
     * Obtener consulta en caché
     */
    public static function getCached(string $documentType, string $documentNumber): ?self
    {
        $query = static::where('document_type', $documentType)
            ->where('document_number', $documentNumber)
            ->first();

        // Retornar solo si es reciente
        if ($query && $query->isRecent()) {
            return $query;
        }

        return null;
    }

    /**
     * Guardar consulta en caché
     */
    public static function cache(string $documentType, string $documentNumber, array $data, string $source): self
    {
        return static::updateOrCreate(
            [
                'document_type' => $documentType,
                'document_number' => $documentNumber,
            ],
            array_merge($data, [
                'source' => $source,
                'queried_at' => now(),
            ])
        );
    }
}
