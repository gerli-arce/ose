<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Registro de intento de envío a SUNAT
 */
class SunatSendAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_document_id',
        'attempt_type',
        'status',
        'response_code',
        'response_message',
        'ticket',
        'xml_path',
        'cdr_path',
        'error_details',
        'ip_address',
        'user_agent',
        'user_id',
        'attempted_at',
    ];

    protected $casts = [
        'attempted_at' => 'datetime',
    ];

    // Tipos de intento
    const TYPE_SEND = 'send';
    const TYPE_RESEND = 'resend';
    const TYPE_CHECK_STATUS = 'check_status';

    // Estados
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';
    const STATUS_PENDING = 'pending';

    // ========== Relaciones ==========

    public function salesDocument()
    {
        return $this->belongsTo(SalesDocument::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ========== Scopes ==========

    public function scopeSuccessful($query)
    {
        return $query->where('status', self::STATUS_SUCCESS);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_ERROR);
    }

    // ========== Helpers ==========

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function isError(): bool
    {
        return $this->status === self::STATUS_ERROR;
    }

    /**
     * Nombre legible del tipo de intento
     */
    public function getAttemptTypeNameAttribute(): string
    {
        return match($this->attempt_type) {
            self::TYPE_SEND => 'Envío inicial',
            self::TYPE_RESEND => 'Reenvío',
            self::TYPE_CHECK_STATUS => 'Consulta de estado',
            default => 'Desconocido',
        };
    }

    /**
     * Nombre legible del estado
     */
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            self::STATUS_SUCCESS => 'Exitoso',
            self::STATUS_ERROR => 'Error',
            self::STATUS_PENDING => 'Pendiente',
            default => 'Desconocido',
        };
    }

    /**
     * Color del badge según estado
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_SUCCESS => 'success',
            self::STATUS_ERROR => 'danger',
            self::STATUS_PENDING => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Registrar un intento
     */
    public static function log(
        int $documentId,
        string $type,
        string $status,
        ?string $code = null,
        ?string $message = null,
        ?string $xmlPath = null,
        ?string $cdrPath = null,
        ?string $ticket = null,
        ?string $errorDetails = null
    ): self {
        return static::create([
            'sales_document_id' => $documentId,
            'attempt_type' => $type,
            'status' => $status,
            'response_code' => $code,
            'response_message' => $message,
            'xml_path' => $xmlPath,
            'cdr_path' => $cdrPath,
            'ticket' => $ticket,
            'error_details' => $errorDetails,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id(),
            'attempted_at' => now(),
        ]);
    }
}
