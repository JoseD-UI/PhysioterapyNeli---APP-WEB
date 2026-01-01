<?php

namespace App\Models\Clinico;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sesion extends Model
{
    protected $table = 'clinico_sesiones';
    protected $primaryKey = 'sesion_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // solo creado_en -> usamos CREATED_AT pero no timestamps activos
    const CREATED_AT = 'creado_en';
    public $timestamps = false;

    protected $fillable = [
        'sesion_id',
        'cita_id',
        'paciente_id',
        'fisioterapeuta_id',
        'servicio_id',
        'fecha_atencion',
        'duracion_minutos',
        'notas',
        'ejercicios_realizados',
        'materiales_usados',
        'creado_en',
    ];

    protected $casts = [
        'materiales_usados' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // Relaciones
    public function paciente()
    {
        return $this->belongsTo(\App\Models\Principal\Persona::class, 'paciente_id', 'persona_id');
    }

    public function fisioterapeuta()
    {
        return $this->belongsTo(\App\Models\Principal\Persona::class, 'fisioterapeuta_id', 'persona_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id', 'servicio_id');
    }

    public function cita()
    {
        return $this->belongsTo(\App\Models\Agenda\Cita::class, 'cita_id', 'cita_id');
    }
}
