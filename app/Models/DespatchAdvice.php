<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Guía de Remisión Electrónica (Código SUNAT 09)
 */
class DespatchAdvice extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'series_id',
        'number',
        'issue_date',
        'transfer_date',
        'transfer_reason_id',
        'transport_modality_id',
        'gross_weight',
        'package_count',
        'sales_document_id',
        'origin_address',
        'origin_ubigeo_id',
        'destination_address',
        'destination_ubigeo_id',
        'recipient_document_type',
        'recipient_document_number',
        'recipient_name',
        'transporter_id',
        'driver_document_type',
        'driver_document_number',
        'driver_name',
        'driver_license',
        'vehicle_id',
        'sunat_status',
        'observation',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'transfer_date' => 'date',
        'gross_weight' => 'decimal:2',
        'package_count' => 'integer',
        'number' => 'integer',
    ];

    // Estados SUNAT
    const SUNAT_PENDING = 'pending';
    const SUNAT_ACCEPTED = 'accepted';
    const SUNAT_REJECTED = 'rejected';

    // Estados del documento
    const STATUS_DRAFT = 'draft';
    const STATUS_ISSUED = 'issued';
    const STATUS_CANCELLED = 'cancelled';

    // ========== Relaciones ==========

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function series()
    {
        return $this->belongsTo(DocumentSeries::class, 'series_id');
    }

    public function items()
    {
        return $this->hasMany(DespatchAdviceItem::class);
    }

    public function transferReason()
    {
        return $this->belongsTo(DespatchTransferReason::class);
    }

    public function transportModality()
    {
        return $this->belongsTo(TransportModality::class);
    }

    public function salesDocument()
    {
        return $this->belongsTo(SalesDocument::class);
    }

    public function originUbigeo()
    {
        return $this->belongsTo(Ubigeo::class, 'origin_ubigeo_id');
    }

    public function destinationUbigeo()
    {
        return $this->belongsTo(Ubigeo::class, 'destination_ubigeo_id');
    }

    public function transporter()
    {
        return $this->belongsTo(Transporter::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function eDocument()
    {
        return $this->morphOne(EDocument::class, 'documentable');
    }

    // ========== Scopes ==========

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopePending($query)
    {
        return $query->where('sunat_status', self::SUNAT_PENDING);
    }

    public function scopeAccepted($query)
    {
        return $query->where('sunat_status', self::SUNAT_ACCEPTED);
    }

    // ========== Helpers ==========

    /**
     * Obtener número completo de la guía
     */
    public function getFullNumberAttribute(): string
    {
        $prefix = $this->series?->prefix ?? 'T001';
        $number = str_pad($this->number, 8, '0', STR_PAD_LEFT);
        return "{$prefix}-{$number}";
    }

    /**
     * Verificar si usa transporte público
     */
    public function isPublicTransport(): bool
    {
        return $this->transportModality?->isPublic() ?? false;
    }

    /**
     * Verificar si usa transporte privado
     */
    public function isPrivateTransport(): bool
    {
        return $this->transportModality?->isPrivate() ?? false;
    }

    /**
     * Verificar si está aceptada por SUNAT
     */
    public function isAccepted(): bool
    {
        return $this->sunat_status === self::SUNAT_ACCEPTED;
    }

    /**
     * Verificar si está rechazada por SUNAT
     */
    public function isRejected(): bool
    {
        return $this->sunat_status === self::SUNAT_REJECTED;
    }

    /**
     * Verificar si está pendiente en SUNAT
     */
    public function isPending(): bool
    {
        return $this->sunat_status === self::SUNAT_PENDING;
    }

    /**
     * Obtener nombre del estado SUNAT
     */
    public function getSunatStatusNameAttribute(): string
    {
        return match($this->sunat_status) {
            self::SUNAT_ACCEPTED => 'Aceptado',
            self::SUNAT_REJECTED => 'Rechazado',
            default => 'Pendiente',
        };
    }

    /**
     * Obtener color del badge de estado SUNAT
     */
    public function getSunatStatusColorAttribute(): string
    {
        return match($this->sunat_status) {
            self::SUNAT_ACCEPTED => 'success',
            self::SUNAT_REJECTED => 'danger',
            default => 'warning',
        };
    }
}
