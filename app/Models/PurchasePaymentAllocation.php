<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePaymentAllocation extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_payment_id', 'purchase_document_id', 'allocated_amount'];
}
