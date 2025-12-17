<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'product_id',
        'description',
        'unit_code',
        'quantity',
        'unit_price',
        'discount_percent',
        'discount_amount',
        'subtotal',
        'tax_amount',
        'total',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'unit_price' => 'decimal:4',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // ========== Relaciones ==========

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ========== Métodos de Cálculo ==========

    public function calculateTotals(float $taxRate = 0.18): void
    {
        $grossAmount = $this->quantity * $this->unit_price;
        
        // Aplicar descuento
        if ($this->discount_percent > 0) {
            $this->discount_amount = $grossAmount * ($this->discount_percent / 100);
        }
        
        $this->subtotal = $grossAmount - $this->discount_amount;
        $this->tax_amount = $this->subtotal * $taxRate;
        $this->total = $this->subtotal + $this->tax_amount;
    }

    public static function createFromProduct(Product $product, float $quantity, float $unitPrice = null, float $discountPercent = 0): array
    {
        $price = $unitPrice ?? $product->sale_price ?? $product->price ?? 0;
        
        return [
            'product_id' => $product->id,
            'description' => $product->name,
            'unit_code' => $product->unit_code ?? 'NIU',
            'quantity' => $quantity,
            'unit_price' => $price,
            'discount_percent' => $discountPercent,
        ];
    }
}
