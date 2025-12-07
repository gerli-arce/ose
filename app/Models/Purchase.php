<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'purchase_documents';

    protected $fillable = [
        'company_id',
        'branch_id',
        'supplier_id',
        'document_type_id',
        'series',
        'number',
        'issue_date',
        'due_date',
        'currency_id',
        'exchange_rate',
        'subtotal',
        'tax_total',
        'total',
        'status', // registered, paid, canceled
        'observations',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'total' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Contact::class, 'supplier_id');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_document_id');
    }
}
