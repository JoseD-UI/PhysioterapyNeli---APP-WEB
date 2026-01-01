<?php

namespace App\Models\Agenda;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CitaEstado extends Model
{
    protected $table = 'agenda_cita_estados';
    protected $primaryKey = 'estado_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'estado_id',
        'codigo',
        'nombre',
        'descripcion',
        'activo',
        'creado_en'
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (!$m->{$m->getKeyName()}) $m->{$m->getKeyName()} = (string) Str::uuid();
        });
    }
}
