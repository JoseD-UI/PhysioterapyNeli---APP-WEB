<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Unidad extends Model
{
    protected $table = 'inventario_unidades';
    protected $primaryKey = 'unidad_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'creado_en';
    public $timestamps = true;

    protected $fillable = ['unidad_id','codigo','nombre'];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (!$m->{$m->getKeyName()}) $m->{$m->getKeyName()} = (string) Str::uuid();
        });
    }
}
