<?php

namespace App\Models\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Proveedor extends Model
{
    protected $table = 'compras_proveedores';
    protected $primaryKey = 'proveedor_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'creado_en';
    public $timestamps = false;

    protected $fillable = [
        'proveedor_id',
        'nombre',
        'ruc',
        'telefono',
        'email',
        'direccion',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->proveedor_id) {
                $model->proveedor_id = (string) Str::uuid();
            }
        });
    }
}
