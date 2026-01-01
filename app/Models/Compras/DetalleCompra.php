<?php

namespace App\Models\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DetalleCompra extends Model
{
    protected $table = 'compras_detalle_compra';
    protected $primaryKey = 'detalle_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'detalle_id',
        'compra_id',
        'item_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->detalle_id) {
                $model->detalle_id = (string) Str::uuid();
            }
        });
    }
}
