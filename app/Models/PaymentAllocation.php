<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAllocation extends Model
{
    use HasFactory;

    protected $table = 'sales_payment_allocations';

    protected $fillable = [
        'sales_payment_id',
        'sales_document_id',
        'allocated_amount',
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'sales_payment_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'sales_document_id');
    }
}
