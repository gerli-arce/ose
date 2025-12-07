<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_document_id',
        'payment_method_id', // Assuming you have this table or using enum/string
        'amount',
        'payment_date',
        'reference'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'float',
    ];

    public function document()
    {
        return $this->belongsTo(SalesDocument::class, 'sales_document_id');
    }
}
