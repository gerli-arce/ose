<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Transportista para guías de remisión
 */
class Transporter extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'document_type',
        'document_number',
        'business_name',
        'registration_number',
        'active',
    ];

    protected $casts = ['active' => 'boolean'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
