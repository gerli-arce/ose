<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyUser extends Pivot
{
    use HasFactory;

    protected $table = 'company_users';
    public $incrementing = true;

    protected $fillable = ['company_id', 'user_id', 'is_owner', 'status'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function roles()
    {
        return $this->hasMany(CompanyUserRole::class, 'company_user_id');
    }

    public function branches()
    {
        return $this->hasMany(BranchUser::class, 'company_user_id');
    }
}
