<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id', 'branch_id', 'supplier_id', 'document_type_id',
        'series', 'number', 'issue_date', 'due_date', 'currency_id',
        'exchange_rate', 'subtotal', 'tax_total', 'total',
        'status', 'observations'
    ];

    public function items()
    {
        return $this->hasMany(PurchaseDocumentItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Contact::class, 'supplier_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function payments()
    {
        return $this->hasMany(PurchasePayment::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
}
