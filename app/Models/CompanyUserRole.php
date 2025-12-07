<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyUserRole extends Model
{
    use HasFactory;

    protected $fillable = ['company_user_id', 'branch_id', 'role_id'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
