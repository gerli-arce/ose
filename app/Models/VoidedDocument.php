<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Comunicación de Baja (Voided Documents)
 * Documento para anular comprobantes electrónicos ante SUNAT
 */
class VoidedDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'identifier',
        'voided_date',
        'reference_date',
        'ticket',
        'status',
        'response_code',
        'response_message',
        'cdr_path',
        'sent_at',
        'status_checked_at',
    ];

    protected $casts = [
        'voided_date' => 'date',
        'reference_date' => 'date',
        'sent_at' => 'datetime',
        'status_checked_at' => 'datetime',
    ];

    // ========== Relaciones ==========

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function items()
    {
        return $this->hasMany(VoidedDocumentItem::class);
    }

    public function salesDocuments()
    {
        return $this->hasManyThrough(
            SalesDocument::class,
            VoidedDocumentItem::class,
            'voided_document_id',
            'id',
            'id',
            'sales_document_id'
        );
    }

    // ========== Métodos ==========

    /**
     * Generar identificador único RA-YYYYMMDD-#####
     */
    public static function generateIdentifier(int $companyId, \DateTime $date): string
    {
        $dateStr = $date->format('Ymd');
        
        // Contar comunicaciones del día para obtener correlativo
        $count = static::where('company_id', $companyId)
            ->whereDate('voided_date', $date)
            ->count();
        
        $correlativo = str_pad($count + 1, 5, '0', STR_PAD_LEFT);
        
        return "RA-{$dateStr}-{$correlativo}";
    }

    /**
     * Verifica si la comunicación está pendiente de consulta
     */
    public function isPending(): bool
    {
        return $this->status === 'sent' && $this->ticket;
    }

    /**
     * Verifica si fue aceptada
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Verifica si fue rechazada
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
