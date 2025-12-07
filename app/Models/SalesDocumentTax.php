<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDocumentTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_document_id', 'tax_id', 'taxable_amount', 'tax_amount'
    ];
}
