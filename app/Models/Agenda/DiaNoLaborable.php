<?php

namespace App\Models\Agenda;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DiaNoLaborable extends Model
{
    protected $table = 'agenda_dias_no_laborables';
    protected $primaryKey = 'nolaborable_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';
    public $timestamps = true;

    protected $fillable = [
        'nolaborable_id',
        'fecha',
        'fisioterapeuta_id',
        'motivo'
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (!$m->{$m->getKeyName()}) $m->{$m->getKeyName()} = (string) Str::uuid();
        });
    }
}
