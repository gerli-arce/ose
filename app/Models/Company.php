<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'trade_name', 'tax_id', 'email', 'phone', 
        'address_id', 'address', 'active', 'config_json',
        'sunat_sol_user', 'sunat_sol_password', 'sunat_cert_path',
        'sunat_cert_password', 'sunat_env', 'logo_path'
    ];

    protected $casts = [
        'config_json' => 'array',
        'active' => 'boolean',
    ];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_users')
            ->withPivot(['is_owner', 'status'])
            ->withTimestamps();
    }

    public function settings()
    {
        return $this->hasMany(CompanySetting::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(CompanySubscription::class);
    }
    
    public function invoices() // Metric for usage
    {
        return $this->hasMany(Invoice::class, 'company_id');
    }

    /**
     * Helper to access the SOL password decrypted (if it was stored encrypted).
     */
    public function getSunatSolPasswordDecryptedAttribute(): ?string
    {
        return $this->decryptNullable($this->attributes['sunat_sol_password'] ?? null);
    }

    /**
     * Helper to access the certificate password decrypted (if it was stored encrypted).
     */
    public function getSunatCertPasswordDecryptedAttribute(): ?string
    {
        return $this->decryptNullable($this->attributes['sunat_cert_password'] ?? null);
    }

    private function decryptNullable(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable $e) {
            // Already plain text or invalid payload, return raw.
            return $value;
        }
    }
}
