<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;

class RolPermiso extends Model
{
    protected $table = 'principal_rol_permiso';

    /**
     * La tabla NO tiene ID autoincremental
     * La PK es compuesta (rol_id, permiso_id)
     */
    public $incrementing = false;
    protected $primaryKey = null;
    protected $keyType = 'string';

    /**
     * No existen created_at / updated_at
     */
    public $timestamps = false;

    /**
     * Campos permitidos
     */
    protected $fillable = [
        'rol_id',
        'permiso_id',
        'asignado_en',
    ];

    /**
     * Casts exactos según BD
     */
    protected $casts = [
        'asignado_en' => 'datetime',
    ];

    /* =====================================================
     | RELACIONES
     ===================================================== */

    public function rol()
    {
        return $this->belongsTo(
            Rol::class,
            'rol_id',
            'rol_id'
        );
    }

    public function permiso()
    {
        return $this->belongsTo(
            Permiso::class,
            'permiso_id',
            'permiso_id'
        );
    }
}
