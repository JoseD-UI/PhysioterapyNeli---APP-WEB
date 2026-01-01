<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activo extends Model
{
    protected $table = 'inventario_activos';
    protected $primaryKey = 'activo_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'creado_en';
    public $timestamps = false;

    protected $fillable = [
        'activo_id',
        'nombre_activo',
        'categoria',
        'estado_activo',
        'fecha_compra',
        'valor_compra',
        'proveedor_id',
        'vida_util_meses',
        'creado_en',
    ];

    protected $casts = [
        'fecha_compra' => 'date',
        'valor_compra' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->{$model->getKeyName()}) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function proveedor()
    {
        return $this->belongsTo(
            \App\Models\Compras\Proveedor::class,
            'proveedor_id',
            'proveedor_id'
        );
    }
}
