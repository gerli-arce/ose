<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Catálogo 18 - Modalidad de transporte SUNAT
 */
class TransportModality extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'active'];
    protected $casts = ['active' => 'boolean'];

    const PUBLIC_TRANSPORT = '01';
    const PRIVATE_TRANSPORT = '02';

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function isPublic(): bool
    {
        return $this->code === self::PUBLIC_TRANSPORT;
    }

    public function isPrivate(): bool
    {
        return $this->code === self::PRIVATE_TRANSPORT;
    }
}
