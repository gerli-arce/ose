<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_document_items';

    protected $fillable = [
        'purchase_document_id',
        'product_id',
        'description',
        'quantity',
        'unit_price', // Cost
        'line_total',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_price' => 'decimal:4',
        'line_total' => 'decimal:2',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_document_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
