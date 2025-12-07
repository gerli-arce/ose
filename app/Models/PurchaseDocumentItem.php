<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDocumentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_document_id', 'product_id', 'description',
        'quantity', 'unit_id', 'unit_cost',
        'line_subtotal', 'line_tax_total', 'line_total'
    ];
}
