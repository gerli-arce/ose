<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Ubigeo de Perú (INEI)
 * Código de 6 dígitos: DDPPDD
 * DD = Departamento, PP = Provincia, DD = Distrito
 */
class Ubigeo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'department_code',
        'province_code',
        'district_code',
        'name',
        'level',
        'parent_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Niveles
    const LEVEL_DEPARTMENT = 'department';
    const LEVEL_PROVINCE = 'province';
    const LEVEL_DISTRICT = 'district';

    // ========== Relaciones ==========

    public function parent()
    {
        return $this->belongsTo(Ubigeo::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Ubigeo::class, 'parent_id');
    }

    // ========== Scopes ==========

    public function scopeDepartments($query)
    {
        return $query->where('level', self::LEVEL_DEPARTMENT);
    }

    public function scopeProvinces($query)
    {
        return $query->where('level', self::LEVEL_PROVINCE);
    }

    public function scopeDistricts($query)
    {
        return $query->where('level', self::LEVEL_DISTRICT);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByDepartment($query, string $departmentCode)
    {
        return $query->where('department_code', $departmentCode);
    }

    public function scopeByProvince($query, string $provinceCode)
    {
        return $query->where('province_code', $provinceCode);
    }

    // ========== Helpers ==========

    /**
     * Obtener todos los departamentos
     */
    public static function getDepartments()
    {
        return static::departments()->active()->orderBy('name')->get();
    }

    /**
     * Obtener provincias de un departamento
     */
    public static function getProvincesByDepartment(string $departmentCode)
    {
        return static::provinces()
            ->byDepartment($departmentCode)
            ->active()
            ->orderBy('name')
            ->get();
    }

    /**
     * Obtener distritos de una provincia
     */
    public static function getDistrictsByProvince(string $provinceCode)
    {
        return static::districts()
            ->byProvince($provinceCode)
            ->active()
            ->orderBy('name')
            ->get();
    }

    /**
     * Obtener nombre completo (Departamento - Provincia - Distrito)
     */
    public function getFullNameAttribute(): string
    {
        $parts = [];
        
        if ($this->level === self::LEVEL_DISTRICT) {
            $province = $this->parent;
            $department = $province?->parent;
            
            if ($department) $parts[] = $department->name;
            if ($province) $parts[] = $province->name;
            $parts[] = $this->name;
        } elseif ($this->level === self::LEVEL_PROVINCE) {
            $department = $this->parent;
            if ($department) $parts[] = $department->name;
            $parts[] = $this->name;
        } else {
            $parts[] = $this->name;
        }
        
        return implode(' - ', $parts);
    }

    /**
     * Verificar si es departamento
     */
    public function isDepartment(): bool
    {
        return $this->level === self::LEVEL_DEPARTMENT;
    }

    /**
     * Verificar si es provincia
     */
    public function isProvince(): bool
    {
        return $this->level === self::LEVEL_PROVINCE;
    }

    /**
     * Verificar si es distrito
     */
    public function isDistrict(): bool
    {
        return $this->level === self::LEVEL_DISTRICT;
    }

    /**
     * Obtener departamento
     */
    public function getDepartment()
    {
        if ($this->isDepartment()) {
            return $this;
        }
        
        if ($this->isProvince()) {
            return $this->parent;
        }
        
        // Es distrito
        return $this->parent?->parent;
    }

    /**
     * Obtener provincia
     */
    public function getProvince()
    {
        if ($this->isProvince()) {
            return $this;
        }
        
        if ($this->isDistrict()) {
            return $this->parent;
        }
        
        return null;
    }
}
