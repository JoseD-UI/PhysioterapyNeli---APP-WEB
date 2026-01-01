<?php

namespace App\Models\Clinico;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Servicio extends Model
{
    protected $table = 'clinico_servicios';
    protected $primaryKey = 'servicio_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // solo creado_en en migración -> usamos CREATED_AT pero no timestamps activos
    const CREATED_AT = 'creado_en';
    public $timestamps = false;

    protected $fillable = [
        'servicio_id',
        'nombre',
        'descripcion',
        'duracion_minutos',
        'precio',
        'activo',
        'creado_en',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function sesiones()
    {
        return $this->hasMany(Sesion::class, 'servicio_id', 'servicio_id');
    }

    public function citas()
    {
        return $this->hasMany(\App\Models\Agenda\Cita::class, 'servicio_id', 'servicio_id');
    }
}
