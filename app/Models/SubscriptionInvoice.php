<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'subscription_id', 'period_start', 'period_end',
        'amount', 'status', 'payment_method_id'
    ];
}
