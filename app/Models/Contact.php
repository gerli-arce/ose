<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id', 'identity_document_type_id', 'type', 'name', 'business_name', 'trade_name',
        'tax_id', 'email', 'phone', 'address_id', 'address', 'credit_limit',
        'payment_terms', 'active', 'observations'
    ];

    protected $casts = [
        'active' => 'boolean',
        'credit_limit' => 'decimal:2',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function identityDocumentType()
    {
        return $this->belongsTo(IdentityDocumentType::class);
    }

    /**
     * Obtener el código de tipo de documento SUNAT
     */
    public function getSunatDocTypeCodeAttribute(): string
    {
        if ($this->identityDocumentType) {
            return $this->identityDocumentType->code;
        }

        // Fallback: determinar por longitud del tax_id
        $length = strlen(trim($this->tax_id ?? ''));
        return match ($length) {
            11 => '6', // RUC
            8 => '1',  // DNI
            default => '0', // Sin documento
        };
    }
}

