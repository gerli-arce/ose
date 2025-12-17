<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Vehículo para guías de remisión
 */
class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'transporter_id',
        'plate_number',
        'brand',
        'model',
        'authorization_code',
        'active',
    ];

    protected $casts = ['active' => 'boolean'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function getFullDescriptionAttribute(): string
    {
        $parts = [$this->plate_number];
        if ($this->brand) $parts[] = $this->brand;
        if ($this->model) $parts[] = $this->model;
        return implode(' - ', $parts);
    }
}
