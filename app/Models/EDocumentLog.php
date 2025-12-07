<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EDocumentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'e_document_id',
        'message',
        'details',
        'status' // success, error
    ];

    public function eDocument()
    {
        return $this->belongsTo(EDocument::class);
    }
}
