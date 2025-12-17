<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'sunat_code',
        'name',
        'symbol',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Scope para unidades activas
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
     * Obtener código SUNAT (prioriza sunat_code si existe)
     */
    public function getSunatCodeAttribute(): string
    {
        return $this->attributes['sunat_code'] ?? $this->attributes['code'];
    }
}
