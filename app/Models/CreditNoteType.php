<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo SUNAT Nº 09: Tipo de Nota de Crédito Electrónica
 */
class CreditNoteType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'affects_stock',
        'active'
    ];

    protected $casts = [
        'affects_stock' => 'boolean',
        'active' => 'boolean',
    ];

    /**
     * Scope para tipos activos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Obtener por código SUNAT
     */
    public static function findByCode(string $code): ?self
    {
        return static::where('code', $code)->first();
    }

    /**
     * Tipos más comunes
     */
    public static function anulacion(): ?self
    {
        return static::findByCode('01');
    }

    public static function devolucion(): ?self
    {
        return static::findByCode('06');
    }

    public static function descuento(): ?self
    {
        return static::findByCode('02');
    }
}
