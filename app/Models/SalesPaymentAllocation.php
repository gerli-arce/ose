<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPaymentAllocation extends Model
{
    use HasFactory;

    protected $fillable = ['sales_payment_id', 'sales_document_id', 'allocated_amount'];
}
