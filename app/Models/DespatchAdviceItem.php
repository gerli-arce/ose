<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Item de Guía de Remisión
 */
class DespatchAdviceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'despatch_advice_id',
        'product_id',
        'description',
        'quantity',
        'unit_code',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    public function despatchAdvice()
    {
        return $this->belongsTo(DespatchAdvice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
