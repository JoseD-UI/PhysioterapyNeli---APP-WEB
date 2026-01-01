<?php

namespace App\Models\Compras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Compra extends Model
{
    protected $table = 'compras_compras';
    protected $primaryKey = 'compra_id';
    public $incrementing = false;
    protected $keyType = 'string';

    const CREATED_AT = 'fecha_compra';
    public $timestamps = false;

    protected $fillable = [
        'compra_id',
        'proveedor_id',
        'fecha_compra',
        'subtotal',
        'igv',
        'total',
        'estado',
        'usuario_id',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->compra_id) {
                $model->compra_id = (string) Str::uuid();
            }
        });
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id', 'proveedor_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'compra_id', 'compra_id');
    }
}
