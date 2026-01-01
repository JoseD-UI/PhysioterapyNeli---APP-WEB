<?php

namespace App\Models\Seguridad;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'seguridad_audit_log';
    protected $primaryKey = 'audit_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'esquema',
        'tabla_nombre',
        'operacion',
        'registro_id',
        'usuario_id',
        'fecha',
        'datos_previos',
        'datos_nuevos',
        'descripcion',
    ];

    protected $casts = [
        'datos_previos' => 'array',
        'datos_nuevos' => 'array',
        'fecha' => 'datetime',
    ];
}
