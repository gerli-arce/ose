<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo SUNAT Nº 06: Tipos de Documento de Identidad
 */
class IdentityDocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'abbreviation',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Obtener el tipo por código SUNAT
     */
    public static function findByCode(string $code): ?self
    {
        return static::where('code', $code)->first();
    }

    /**
     * Determinar tipo de documento basado en longitud del número
     */
    public static function guessFromNumber(?string $number): ?self
    {
        if (!$number) {
            return null;
        }

        $length = strlen(trim($number));
        
        return match ($length) {
            11 => static::findByCode('6'), // RUC
            8 => static::findByCode('1'),  // DNI
            default => static::findByCode('0'), // Sin documento
        };
    }
}
