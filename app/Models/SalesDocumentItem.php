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
        'code',
        'description',
        'quantity',
        'unit_price',
        'total',
        'igv_amount',
        'discount_amount'
    ];

    public function document()
    {
        return $this->belongsTo(SalesDocument::class, 'sales_document_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
