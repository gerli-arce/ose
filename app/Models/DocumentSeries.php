<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class DocumentSeries extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'branch_id', 'warehouse_id', 'document_type_id',
        'prefix', 'current_number'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function scopeActive($query)
    {
        if (Schema::hasColumn($this->getTable(), 'active')) {
            return $query->where('active', true);
        }

        return $query;
    }
}
