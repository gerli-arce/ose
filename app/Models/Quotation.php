<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'branch_id',
        'customer_id',
        'currency_id',
        'series',
        'number',
        'issue_date',
        'expiry_date',
        'seller_id',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
        'exchange_rate',
        'status',
        'sales_document_id',
        'notes',
        'terms',
        'validity_days',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'total' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
    ];

    // Estados
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';
    const STATUS_INVOICED = 'invoiced';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Borrador',
            self::STATUS_SENT => 'Enviada',
            self::STATUS_ACCEPTED => 'Aceptada',
            self::STATUS_REJECTED => 'Rechazada',
            self::STATUS_EXPIRED => 'Vencida',
            self::STATUS_INVOICED => 'Facturada',
        ];
    }

    // ========== Relaciones ==========

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer()
    {
        return $this->belongsTo(Contact::class, 'customer_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class)->orderBy('sort_order');
    }

    public function salesDocument()
    {
        return $this->belongsTo(SalesDocument::class);
    }

    // ========== Atributos Computados ==========

    public function getFullNumberAttribute(): string
    {
        return $this->series . '-' . str_pad($this->number, 6, '0', STR_PAD_LEFT);
    }

    public function getStatusNameAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_SENT => 'info',
            self::STATUS_ACCEPTED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_EXPIRED => 'warning',
            self::STATUS_INVOICED => 'primary',
            default => 'secondary',
        };
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date->isPast() && !in_array($this->status, [self::STATUS_INVOICED, self::STATUS_REJECTED]);
    }

    public function getDaysToExpireAttribute(): int
    {
        return max(0, Carbon::now()->diffInDays($this->expiry_date, false));
    }

    // ========== Métodos de Negocio ==========

    public function canEdit(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_SENT]);
    }

    public function canConvertToInvoice(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_SENT, self::STATUS_ACCEPTED]) 
            && !$this->sales_document_id;
    }

    public function canSendEmail(): bool
    {
        return in_array($this->status, [self::STATUS_DRAFT, self::STATUS_SENT, self::STATUS_ACCEPTED]);
    }

    public function markAsSent(): void
    {
        if ($this->status === self::STATUS_DRAFT) {
            $this->update(['status' => self::STATUS_SENT]);
        }
    }

    public function markAsAccepted(): void
    {
        $this->update(['status' => self::STATUS_ACCEPTED]);
    }

    public function markAsRejected(): void
    {
        $this->update(['status' => self::STATUS_REJECTED]);
    }

    public function markAsInvoiced(int $salesDocumentId): void
    {
        $this->update([
            'status' => self::STATUS_INVOICED,
            'sales_document_id' => $salesDocumentId,
        ]);
    }

    public function recalculateTotals(): void
    {
        $subtotal = $this->items->sum('subtotal');
        $discountTotal = $this->items->sum('discount_amount');
        $taxTotal = $this->items->sum('tax_amount');
        $total = $this->items->sum('total');

        $this->update([
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'tax_total' => $taxTotal,
            'total' => $total,
        ]);
    }

    // ========== Scopes ==========

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', Carbon::today())
            ->whereNotIn('status', [self::STATUS_INVOICED, self::STATUS_REJECTED]);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_INVOICED, self::STATUS_REJECTED, self::STATUS_EXPIRED]);
    }

    // ========== Generación de Número ==========

    public static function getNextNumber(int $companyId, string $series = 'COT'): int
    {
        $lastNumber = static::where('company_id', $companyId)
            ->where('series', $series)
            ->max('number');

        return ($lastNumber ?? 0) + 1;
    }
}
