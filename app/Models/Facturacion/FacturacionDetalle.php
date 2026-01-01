<?php

namespace App\Models\Facturacion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FacturacionDetalle extends Model
{
    protected $table = 'facturacion_detalles';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = true;

    /**
     * SOLO datos que pueden venir del dominio (Service)
     * NO cálculos
     */
    protected $fillable = [
        'id',
        'comprobante_id',

        'producto_id',      // NULL = servicio | UUID = producto
        'descripcion',

        'cantidad',
        'precio_unitario',
    ];

    /**
     * Casts contables
     */
    protected $casts = [
        'cantidad'         => 'decimal:3',
        'precio_unitario'  => 'decimal:2',
        'subtotal'         => 'decimal:2',
        'igv'              => 'decimal:2',
        'total'            => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            if (!$m->{$m->getKeyName()}) {
                $m->{$m->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /* =====================================================
     | RELACIONES
     ===================================================== */

    public function comprobante()
    {
        return $this->belongsTo(
            FacturacionComprobante::class,
            'comprobante_id',
            'id'
        );
    }

    /**
     * Relación con inventario (producto)
     * Si es NULL → es un servicio
     */
    public function producto()
    {
        return $this->belongsTo(
            \App\Models\Inventario\InventarioItem::class,
            'producto_id',
            'item_id'
        );
    }
}
