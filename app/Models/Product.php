<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'product_category_id',
        'code',
        'barcode',
        'name',
        'description',
        'unit_id',
        'is_service',
        'cost_price',
        'sale_price',
        'image_path',
        'active'
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function unit()
    {
        return $this->belongsTo(UnitOfMeasure::class, 'unit_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'product_taxes');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
