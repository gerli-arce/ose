<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo SUNAT Nº 07: Tipos de Afectación del IGV
 */
class TaxAffectationType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'letter',
        'tribute_code',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Scope para tipos gravados
     */
    public function scopeGravado($query)
    {
        return $query->where('letter', 'S');
    }

    /**
     * Scope para tipos exonerados
     */
    public function scopeExonerado($query)
    {
        return $query->where('letter', 'E');
    }

    /**
     * Scope para tipos inafectos
     */
    public function scopeInafecto($query)
    {
        return $query->where('letter', 'O');
    }

    /**
     * Scope para exportación
     */
    public function scopeExportacion($query)
    {
        return $query->where('letter', 'Z');
    }

    /**
     * Obtener el tipo por código SUNAT
     */
    public static function findByCode(string $code): ?self
    {
        return static::where('code', $code)->first();
    }

    /**
     * Obtener el tipo por defecto (Gravado - Operación Onerosa)
     */
    public static function default(): ?self
    {
        return static::findByCode('10');
    }
}
