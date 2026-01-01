<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Permiso extends Model
{
    protected $table = 'principal_permisos';
    protected $primaryKey = 'permiso_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'creado_en';
    public $timestamps = true;
    const UPDATED_AT = null;

    /* ===================== MASS ASSIGNMENT ===================== */
    protected $fillable = [
        'permiso_id',
        'codigo',       // clave técnica: facturacion.emitir
        'nombre',       // nombre humano
        'descripcion',
    ];

    /* ===================== CASTS ===================== */
    protected $casts = [
        'permiso_id' => 'string',
    ];

    /* ===================== BOOT ===================== */
    protected static function booted()
    {
        static::creating(function (self $permiso) {
            if (empty($permiso->permiso_id)) {
                $permiso->permiso_id = (string) Str::uuid();
            }
        });
    }

    /* ===================== RELACIONES ===================== */

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Rol::class,
            'principal_rol_permiso',
            'permiso_id',
            'rol_id'
        )->withPivot('asignado_en');
    }

    /* ===================== HELPERS DE DOMINIO ===================== */

    /**
     * Permiso raíz del sistema (opcional)
     */
    public function esAdmin(): bool
    {
        return $this->codigo === '*';
    }
}
