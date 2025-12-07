<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_document_id',
        'company_id',
        'xml_content', // Store generated XML path or content
        'cdr_content', // Store CDR path or content
        'hash',
        'response_code',
        'response_description',
        'response_status', // pending, accepted, rejected
        'sent_at'
    ];

    public function document()
    {
        return $this->belongsTo(SalesDocument::class, 'sales_document_id');
    }

    public function logs()
    {
        return $this->hasMany(EDocumentLog::class);
    }
}
