<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'is_super_admin',
        'active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_super_admin' => 'boolean',
        'active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_users')
            ->withPivot(['is_owner', 'status'])
            ->withTimestamps();
    }

    public function hasPermissionTo($permissionKey)
    {
        $companyId = session('current_company_id');
        if (!$companyId) return false;

        return \App\Models\CompanyUser::where('user_id', $this->id)
            ->where('company_id', $companyId)
            ->whereHas('roles.role.permissions', function ($query) use ($permissionKey) {
                $query->where('key', $permissionKey);
            })
            ->exists();
    }
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('assets/images/user/1.jpg');
    }
}
