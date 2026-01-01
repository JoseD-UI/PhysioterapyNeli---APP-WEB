<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\Seguridad\PermisoService;

class Usuario extends Model
{
    protected $table = 'principal_usuarios';
    protected $primaryKey = 'usuario_id';
    public $incrementing = false;
    protected $keyType = 'string';

    /* ===================== TIMESTAMPS ===================== */
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';
    public $timestamps = true;

    /* ===================== MASS ASSIGNMENT ===================== */
    protected $fillable = [
        'usuario_id',
        'persona_id',
        'username',
        'password_hash',
        'rol_id',
        'activo',
        'intento_fallido',
        'ultimo_acceso',
    ];

    /* ===================== OCULTOS ===================== */
    protected $hidden = [
        'password_hash',
    ];

    /* ===================== CASTS ===================== */
    protected $casts = [
        'activo'          => 'boolean',
        'intento_fallido' => 'integer',
        'ultimo_acceso'   => 'datetime',
        'creado_en'       => 'datetime',
        'actualizado_en'  => 'datetime',
    ];

    /* ===================== BOOT ===================== */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /* ===================== RELACIONES ===================== */

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'persona_id');
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id', 'rol_id');
    }

    /* ===================== HELPERS DE DOMINIO ===================== */

    /**
     * Verifica si el usuario está activo
     */
    public function esActivo(): bool
    {
        return $this->activo === true;
    }

    /**
     * Verifica si el usuario tiene un permiso específico
     * (RBAC)
     */
    /* =====================================================
    | PERMISOS (API LIMPIA)
    ===================================================== */

   /**
    * Validar permiso por código
    */
   public function tienePermiso(string $permisoCodigo): bool
   {
       return app(PermisoService::class)
           ->validar($this, $permisoCodigo);
   }

   /**
    * Validar múltiples permisos (OR)
    */
   public function tieneAlguno(array $permisos): bool
   {
       foreach ($permisos as $permiso) {
           if ($this->tienePermiso($permiso)) {
               return true;
           }
       }
       return false;
   }

   /**
    * Validar múltiples permisos (AND)
    */
   public function tieneTodos(array $permisos): bool
   {
       foreach ($permisos as $permiso) {
           if (!$this->tienePermiso($permiso)) {
               return false;
           }
       }
       return true;
   }

   /**
    * Es administrador del sistema
    */
   public function esAdmin(): bool
   {
       return $this->rol &&
           strtoupper($this->rol->nombre) === 'ADMINISTRADOR';
   }
}
