<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'path', 'original_name', 'mime_type',
        'size', 'reference_type', 'reference_id'
    ];

    public function reference()
    {
        return $this->morphTo();
    }
}
