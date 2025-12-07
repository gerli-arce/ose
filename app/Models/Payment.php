<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'sales_payments';

    protected $fillable = [
        'company_id',
        'branch_id',
        'customer_id',
        'payment_method_id',
        'payment_date',
        'currency_id',
        'amount',
        'reference',
        'observations',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function customer()
    {
        return $this->belongsTo(Contact::class, 'customer_id');
    }
    
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function allocations()
    {
        return $this->hasMany(PaymentAllocation::class, 'sales_payment_id');
    }
    
    public function invoices()
    {
        return $this->belongsToMany(Invoice::class, 'sales_payment_allocations', 'sales_payment_id', 'sales_document_id')
                    ->withPivot('allocated_amount')
                    ->withTimestamps();
    }
}
