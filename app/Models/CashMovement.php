<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash_register_session_id',
        'type', // income, expense
        'amount',
        'description',
        'payment_method_id',
        'related_id',
        'related_type',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function session()
    {
        return $this->belongsTo(CashRegisterSession::class, 'cash_register_session_id');
    }

    // Polymorphic relation to Sale, Purchase, Expense
    public function related()
    {
        return $this->morphTo();
    }
}
