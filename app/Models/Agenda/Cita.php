<?php

namespace App\Models\Agenda;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cita extends Model
{
    protected $table = 'agenda_citas';
    protected $primaryKey = 'cita_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null; // tu tabla no tiene actualizado_en en citas
    public $timestamps = true;

    protected $fillable = [
        'cita_id',
        'paciente_id',
        'fisioterapeuta_id',
        'servicio_id',
        'sala_id',
        'fecha_inicio',
        'fecha_fin',
        'estado_id',
        'creado_por'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (!$m->{$m->getKeyName()}) {
                $m->{$m->getKeyName()} = (string) Str::uuid();
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
        return $this->belongsTo(\App\Models\Clinico\Servicio::class, 'servicio_id', 'servicio_id');
    }

    public function sala()
    {
        return $this->belongsTo(\App\Models\Principal\Sala::class, 'sala_id', 'sala_id');
    }

    public function creadoPor()
    {
        return $this->belongsTo(\App\Models\Principal\Usuario::class, 'creado_por', 'usuario_id');
    }

    public function estado()
    {
        return $this->belongsTo(CitaEstado::class, 'estado_id', 'estado_id');
    }
}
