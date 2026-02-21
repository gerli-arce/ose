<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EDocumentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'e_document_id',
        'event_date_time',
        'status',
        'message',
        'raw_response',
    ];

    protected $casts = [
        'event_date_time' => 'datetime',
    ];

    public function eDocument()
    {
        return $this->belongsTo(EDocument::class);
    }
}
