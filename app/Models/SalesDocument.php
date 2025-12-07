<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'branch_id',
        'branch_id',
        'customer_id', // Contact
        'document_type_id',
        'series_id', // Changed from series string
        'number',
        'issue_date',
        'due_date',
        'currency_id', // Changed from currency string
        'exchange_rate',
        'observation',
        'subtotal',
        'tax_total', // Changed from total_igv

        'total_discount',
        'total',
        'status', // draft, emitted, annulled
        'sunat_status', // pending, sent, accepted, rejected
        'payment_status', // pending, partial, paid
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'total' => 'float',
        'subtotal' => 'float',
        'total_igv' => 'float',
    ];

    public function items()
    {
        return $this->hasMany(SalesDocumentItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Contact::class, 'customer_id');
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

    public function payments()
    {
        return $this->hasMany(SalesPayment::class);
    }

    public function series()
    {
        return $this->belongsTo(DocumentSeries::class, 'series_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function eDocument()
    {
        return $this->hasOne(EDocument::class);
    }
}
