<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Resumen Diario de Boletas (Daily Summary)
 * Documento obligatorio para informar boletas a SUNAT
 */
class DailySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'identifier',
        'summary_date',
        'reference_date',
        'ticket',
        'status',
        'response_code',
        'response_message',
        'xml_path',
        'cdr_path',
        'total_documents',
        'total_amount',
        'sent_at',
        'status_checked_at',
    ];

    protected $casts = [
        'summary_date' => 'date',
        'reference_date' => 'date',
        'sent_at' => 'datetime',
        'status_checked_at' => 'datetime',
        'total_documents' => 'integer',
        'total_amount' => 'float',
    ];

    // ========== Relaciones ==========

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function items()
    {
        return $this->hasMany(DailySummaryItem::class);
    }

    public function salesDocuments()
    {
        return $this->hasMany(SalesDocument::class);
    }

    // ========== Métodos ==========

    /**
     * Generar identificador único RC-YYYYMMDD-#####
     */
    public static function generateIdentifier(int $companyId, \DateTime $date): string
    {
        $dateStr = $date->format('Ymd');
        
        // Contar resúmenes del día para obtener correlativo
        $count = static::where('company_id', $companyId)
            ->whereDate('summary_date', $date)
            ->count();
        
        $correlativo = str_pad($count + 1, 5, '0', STR_PAD_LEFT);
        
        return "RC-{$dateStr}-{$correlativo}";
    }

    /**
     * Verifica si el resumen está pendiente de consulta
     */
    public function isPending(): bool
    {
        return $this->status === 'sent' && $this->ticket;
    }

    /**
     * Verifica si fue aceptado
     */
    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    /**
     * Verifica si fue rechazado
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Obtener boletas pendientes de un día para una empresa
     */
    public static function getPendingBoletas(int $companyId, \DateTime $date)
    {
        return SalesDocument::where('company_id', $companyId)
            ->whereDate('issue_date', $date)
            ->whereHas('documentType', fn($q) => $q->whereIn('code', ['03', '07', '08']))
            ->whereNull('daily_summary_id')
            ->where('status', 'emitted')
            ->with(['documentType', 'series', 'customer', 'items'])
            ->get();
    }
}
