<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Item de Comunicación de Baja
 * Representa cada documento anulado dentro de una comunicación
 */
class VoidedDocumentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'voided_document_id',
        'sales_document_id',
        'document_type_code',
        'series',
        'number',
        'reason',
    ];

    // ========== Relaciones ==========

    public function voidedDocument()
    {
        return $this->belongsTo(VoidedDocument::class);
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
        $correlativo = str_pad($this->number, 8, '0', STR_PAD_LEFT);
        return "{$this->series}-{$correlativo}";
    }
}
