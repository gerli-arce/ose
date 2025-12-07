<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'sales_document_items';

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

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_price' => 'decimal:4',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'line_subtotal' => 'decimal:2',
        'line_tax_total' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'sales_document_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(UnitOfMeasure::class);
    }
}
