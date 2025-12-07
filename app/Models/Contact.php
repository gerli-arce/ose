<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id', 'type', 'name', 'business_name', 'trade_name',
        'tax_id', 'email', 'phone', 'address_id', 'address', 'credit_limit',
        'payment_terms', 'active', 'observations'
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
