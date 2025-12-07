<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'user_id', 'action', 'module',
        'reference_type', 'reference_id', 'ip_address', 'user_agent'
    ];
}
