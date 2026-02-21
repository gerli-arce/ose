<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_document_id',
        'provider',
        'xml_path',
        'pdf_path',
        'signed_at',
        'sent_at',
        'response_status',
        'response_code',
        'response_message',
        'cdr_path',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(SalesDocument::class, 'sales_document_id');
    }

    public function logs()
    {
        return $this->hasMany(EDocumentLog::class);
    }

    /**
     * Compatibilidad con vistas que leen eDocument->hash.
     */
    public function getHashAttribute(): ?string
    {
        return $this->document?->hash;
    }
}
