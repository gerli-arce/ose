<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo SUNAT Nº 10: Tipo de Nota de Débito Electrónica
 */
class DebitNoteType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'active'
    ];

    protected $casts = [
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
}
