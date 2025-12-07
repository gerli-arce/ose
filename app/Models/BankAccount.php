<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'bank_name',
        'account_number',
        'account_type',
        'currency_id',
        'current_balance', // Cached balance
        'holder_name',
    ];

    protected $casts = [
        'current_balance' => 'decimal:2',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function transactions()
    {
        return $this->hasMany(BankTransaction::class);
    }
}
