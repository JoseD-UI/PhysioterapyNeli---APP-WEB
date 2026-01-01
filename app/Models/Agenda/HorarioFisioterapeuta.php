<?php

namespace App\Models\Agenda;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HorarioFisioterapeuta extends Model
{
    protected $table = 'agenda_horarios_fisioterapeuta';
    protected $primaryKey = 'horario_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';
    public $timestamps = true;

    protected $fillable = [
        'horario_id',
        'fisioterapeuta_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'activo'
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (!$m->{$m->getKeyName()}) $m->{$m->getKeyName()} = (string) Str::uuid();
        });
    }

    public function fisioterapeuta()
    {
        return $this->belongsTo(\App\Models\Principal\Persona::class, 'fisioterapeuta_id', 'persona_id');
    }
}
