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
        'customer_id',
        'document_type_id',
        'series_id',
        'number',
        'issue_date',
        'due_date',
        'currency_id',
        'exchange_rate',
        'observation',
        'subtotal',
        'tax_total',
        'total_discount',
        'total',
        'status',
        'sunat_status',
        'payment_status',
        // Campos para NC/ND
        'related_document_id',
        'credit_note_type_id',
        'debit_note_type_id',
        'note_reason',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'total' => 'float',
        'subtotal' => 'float',
        'tax_total' => 'float',
        'exchange_rate' => 'float',
    ];

    // ========== Relaciones ==========

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

    // Relaciones para Notas de Crédito/Débito
    public function relatedDocument()
    {
        return $this->belongsTo(SalesDocument::class, 'related_document_id');
    }

    public function creditNotes()
    {
        return $this->hasMany(SalesDocument::class, 'related_document_id')
                    ->whereHas('documentType', fn($q) => $q->where('code', '07'));
    }

    public function debitNotes()
    {
        return $this->hasMany(SalesDocument::class, 'related_document_id')
                    ->whereHas('documentType', fn($q) => $q->where('code', '08'));
    }

    public function creditNoteType()
    {
        return $this->belongsTo(CreditNoteType::class);
    }

    public function debitNoteType()
    {
        return $this->belongsTo(DebitNoteType::class);
    }

    // ========== Atributos Calculados ==========

    /**
     * Obtener número formateado: SERIE-CORRELATIVO
     */
    public function getFullNumberAttribute(): string
    {
        $serie = $this->series?->prefix ?? 'XXX';
        $correlativo = str_pad($this->number, 8, '0', STR_PAD_LEFT);
        return "{$serie}-{$correlativo}";
    }

    /**
     * Es una nota de crédito
     */
    public function isCreditNote(): bool
    {
        return $this->documentType?->code === '07';
    }

    /**
     * Es una nota de débito
     */
    public function isDebitNote(): bool
    {
        return $this->documentType?->code === '08';
    }

    /**
     * Es factura
     */
    public function isFactura(): bool
    {
        return $this->documentType?->code === '01';
    }

    /**
     * Es boleta
     */
    public function isBoleta(): bool
    {
        return $this->documentType?->code === '03';
    }

    /**
     * Puede emitir NC (solo facturas/boletas emitidas y aceptadas)
     */
    public function canIssueCreditNote(): bool
    {
        return in_array($this->documentType?->code, ['01', '03'])
            && $this->status === 'emitted'
            && in_array($this->sunat_status, ['accepted', 'pending']);
    }

    /**
     * Total pendiente de pago (considerando NC emitidas)
     */
    public function getPendingAmountAttribute(): float
    {
        $creditNotesTotal = $this->creditNotes()->sum('total');
        $paymentsTotal = $this->payments()->sum('amount');
        return max(0, $this->total - $creditNotesTotal - $paymentsTotal);
    }
}

