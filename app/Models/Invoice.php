<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales_documents';

    protected $fillable = [
        'company_id',
        'branch_id',
        'document_type_id',
        'series_id',
        'number',
        'issue_date',
        'due_date',
        'customer_id',
        'currency_id',
        'exchange_rate',
        'subtotal',
        'tax_total',
        'total',
        'status', // draft, issued, paid, cancelled
        'payment_status', // unpaid, partial, paid
        'related_document_id',
        'observations',
        'electronic_uuid',
        'hash',
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

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function series()
    {
        return $this->belongsTo(DocumentSeries::class);
    }

    public function customer()
    {
        return $this->belongsTo(Contact::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'sales_document_id');
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class, 'sales_payment_allocations', 'sales_document_id', 'sales_payment_id')
                    ->withPivot('allocated_amount')
                    ->withTimestamps();
    }

    // Helpers
    public function getSeriesNumberAttribute()
    {
        $prefix = $this->series ? $this->series->prefix : '???';
        return $prefix . '-' . str_pad($this->number, 8, '0', STR_PAD_LEFT);
    }
}
