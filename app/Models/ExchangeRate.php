<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ExchangeRate extends Model { use HasFactory; protected $fillable = ['date', 'from_currency_id', 'to_currency_id', 'rate']; }
