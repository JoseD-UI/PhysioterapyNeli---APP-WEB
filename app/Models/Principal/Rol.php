<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rol extends Model
{
    protected $table = 'principal_roles';
    protected $primaryKey = 'rol_id';
    public $incrementing = true; // smallint
    protected $keyType = 'int';
    public $timestamps = false;

    /* ===================== MASS ASSIGNMENT ===================== */
    protected $fillable = [
        'rol_id',
        'nombre',
        'descripcion',
        // si luego agregas: 'codigo'
    ];

    /* ===================== CASTS ===================== */
    protected $casts = [
        'rol_id' => 'integer',
    ];

    /* ===================== RELACIONES ===================== */

    public function usuarios(): HasMany
    {
        return $this->hasMany(
            Usuario::class,
            'rol_id',
            'rol_id'
        );
    }

    public function permisos(): BelongsToMany
    {
        return $this->belongsToMany(
            Permiso::class,
            'principal_rol_permiso',
            'rol_id',
            'permiso_id'
        )->withPivot('asignado_en');
    }

    /* ===================== HELPERS DE DOMINIO ===================== */

    /**
     * Verifica si el rol tiene un permiso específico
     */
    public function tienePermiso(string $codigoPermiso): bool
    {
        if (!$this->relationLoaded('permisos')) {
            $this->load('permisos');
        }

        return $this->permisos
            ->contains('codigo', $codigoPermiso);
    }

    /**
     * Rol administrador (acceso total)
     */
    public function esAdmin(): bool
    {
        // si luego decides agregar campo codigo = ADMIN
        return strtoupper($this->nombre) === 'ADMINISTRADOR'
            || strtoupper($this->nombre) === 'ADMIN';
    }

    /**
     * Devuelve todos los permisos como array de códigos
     */
    public function permisosCodigos(): array
    {
        if (!$this->relationLoaded('permisos')) {
            $this->load('permisos');
        }

        return $this->permisos
            ->pluck('codigo')
            ->toArray();
    }
}
