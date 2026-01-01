<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sala extends Model
{
    protected $table = 'principal_salas';
    protected $primaryKey = 'sala_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';
    public $timestamps = true;

    protected $fillable = [
        'sala_id',
        'nombre_sala',
        'descripcion',
        'activa'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->{$model->getKeyName()}) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function citas()
    {
        return $this->hasMany(\App\Models\Agenda\Cita::class, 'sala_id', 'sala_id');
    }
}
