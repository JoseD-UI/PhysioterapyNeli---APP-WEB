<?php

namespace App\Models\Clinico;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TipoServicio extends Model
{
    protected $table = 'clinico_tipos_servicio';
    protected $primaryKey = 'tipo_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';
    public $timestamps = true;

    protected $fillable = [
        'tipo_id',
        'nombre',
        'descripcion',
        'activo'
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (!$m->{$m->getKeyName()}) $m->{$m->getKeyName()} = (string) Str::uuid();
        });
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'tipo_id', 'tipo_id');
    }
}
