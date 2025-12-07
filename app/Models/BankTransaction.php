<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_account_id',
        'type', // deposit, withdrawal, transfer_in, transfer_out
        'amount',
        'reference',
        'description',
        'transaction_date',
        'is_reconciled',
        'reconciled_at',
        'related_id',
        'related_type',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'is_reconciled' => 'boolean',
        'reconciled_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }
    
    public function related()
    {
        return $this->morphTo();
    }
}
