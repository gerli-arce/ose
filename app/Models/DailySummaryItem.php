<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Item de Resumen Diario
 * Representa cada documento incluido en el resumen
 */
class DailySummaryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'daily_summary_id',
        'sales_document_id',
        'document_type_code',
        'series',
        'start_number',
        'end_number',
        'status_code',
        'total_gravadas',
        'total_exoneradas',
        'total_inafectas',
        'total_exportacion',
        'total_gratuitas',
        'total_igv',
        'total_isc',
        'total_otros',
        'total',
    ];

    protected $casts = [
        'start_number' => 'integer',
        'end_number' => 'integer',
        'total_gravadas' => 'float',
        'total_exoneradas' => 'float',
        'total_inafectas' => 'float',
        'total_exportacion' => 'float',
        'total_gratuitas' => 'float',
        'total_igv' => 'float',
        'total_isc' => 'float',
        'total_otros' => 'float',
        'total' => 'float',
    ];

    // Códigos de estado para resumen
    const STATUS_ADD = '1';      // Agregar
    const STATUS_MODIFY = '2';   // Modificar
    const STATUS_ANNUL = '3';    // Anular

    // ========== Relaciones ==========

    public function dailySummary()
    {
        return $this->belongsTo(DailySummary::class);
    }

    public function salesDocument()
    {
        return $this->belongsTo(SalesDocument::class);
    }

    // ========== Atributos ==========

    /**
     * Número completo del documento: SERIE-CORRELATIVO
     */
    public function getFullNumberAttribute(): string
    {
        $start = str_pad($this->start_number, 8, '0', STR_PAD_LEFT);
        if ($this->start_number === $this->end_number) {
            return "{$this->series}-{$start}";
        }
        $end = str_pad($this->end_number, 8, '0', STR_PAD_LEFT);
        return "{$this->series}-{$start} a {$end}";
    }

    /**
     * Nombre del estado
     */
    public function getStatusNameAttribute(): string
    {
        return match($this->status_code) {
            self::STATUS_ADD => 'Agregar',
            self::STATUS_MODIFY => 'Modificar',
            self::STATUS_ANNUL => 'Anular',
            default => 'Desconocido',
        };
    }
}
