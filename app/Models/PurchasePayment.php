<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'branch_id', 'supplier_id', 'payment_method_id',
        'payment_date', 'currency_id', 'amount', 'reference', 'observations'
    ];
}
