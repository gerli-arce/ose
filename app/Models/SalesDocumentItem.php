<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDocumentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_document_id',
        'product_id',
        'description',
        'quantity',
        'unit_id',
        'unit_price',
        'discount_percent',
        'discount_amount',
        'line_subtotal',
        'line_tax_total',
        'line_total',
    ];

    public function document()
    {
        return $this->belongsTo(SalesDocument::class, 'sales_document_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'unit_id');
    }

    /**
     * Alias para compatibilidad con código SUNAT existente.
     */
    public function getTotalAttribute(): float
    {
        return (float) ($this->line_total ?? 0);
    }

    /**
     * Alias para compatibilidad con código SUNAT existente.
     */
    public function getIgvAmountAttribute(): float
    {
        return (float) ($this->line_tax_total ?? 0);
    }

    /**
     * Alias para compatibilidad con código SUNAT existente.
     */
    public function getCodeAttribute(): string
    {
        return (string) ($this->product?->code ?? '');
    }
}
