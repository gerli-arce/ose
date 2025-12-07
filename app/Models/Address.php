<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Address extends Model { 
    use HasFactory; 
    protected $fillable = ['line1', 'line2', 'city_id', 'state_id', 'country_id', 'postal_code']; 
}
