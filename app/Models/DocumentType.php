<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'sunat_code',
        'name',
        'category',
        'affects_stock',
        'requires_customer_ruc',
        'is_electronic'
    ];

    protected $casts = [
        'affects_stock' => 'boolean',
        'requires_customer_ruc' => 'boolean',
        'is_electronic' => 'boolean',
    ];

    /**
     * Scope para documentos de venta
     */
    public function scopeSales($query)
    {
        return $query->whereIn('category', ['sales', 'note']);
    }

    /**
     * Scope para documentos electrónicos
     */
    public function scopeElectronic($query)
    {
        return $query->where('is_electronic', true);
    }

    /**
     * Scope para facturas y boletas
     */
    public function scopeInvoices($query)
    {
        return $query->whereIn('code', ['01', '03']);
    }

    /**
     * Scope para notas (crédito/débito)
     */
    public function scopeNotes($query)
    {
        return $query->whereIn('code', ['07', '08']);
    }

    /**
     * Obtener por código SUNAT
     */
    public static function findByCode(string $code): ?self
    {
        return static::where('code', $code)->first();
    }

    /**
     * Es factura electrónica
     */
    public function isFactura(): bool
    {
        return $this->code === '01';
    }

    /**
     * Es boleta de venta
     */
    public function isBoleta(): bool
    {
        return $this->code === '03';
    }

    /**
     * Es nota de crédito
     */
    public function isNotaCredito(): bool
    {
        return $this->code === '07';
    }

    /**
     * Es nota de débito
     */
    public function isNotaDebito(): bool
    {
        return $this->code === '08';
    }
}
