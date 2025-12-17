<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo 20 - Motivos de traslado SUNAT
 */
class DespatchTransferReason extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'active'];
    protected $casts = ['active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
