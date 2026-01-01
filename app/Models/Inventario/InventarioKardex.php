<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class InventarioKardex extends Model
{
    protected $table = 'inventario_kardex';
    protected $primaryKey = 'kardex_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'item_id',
        'tipo_movimiento',       // entrada | salida
        'origen',                // compra | venta | nota_credito | ajuste

        'cantidad',              // movimiento
        'saldo',
        'saldo_cantidad',
        'saldo_valorizado',

        'costo_unitario',
        'costo_promedio_resultante',
        'valor_total',

        'metodo_valorizacion',

        'documento_tipo',
        'documento_serie',
        'documento_correlativo',

        'referencia_id',
        'referencia_tipo',

        'usuario_id',
        'nota',
        'fecha'
    ];

    protected $casts = [
        'cantidad'                   => 'decimal:3',
        'saldo'                      => 'decimal:3',
        'saldo_cantidad'             => 'decimal:3',
        'saldo_valorizado'           => 'decimal:2',
        'costo_unitario'             => 'decimal:2',
        'costo_promedio_resultante'  => 'decimal:2',
        'valor_total'                => 'decimal:2',
        'fecha'                      => 'datetime',
    ];

    /** Constantes alineadas a tu ENUM */
    public const TIPO_ENTRADA = 'entrada';
    public const TIPO_SALIDA  = 'salida';

    protected static function booted()
    {
        static::creating(function ($m) {
            if (!$m->{$m->getKeyName()}) {
                $m->{$m->getKeyName()} = (string) Str::uuid();
            }

            if (empty($m->fecha)) {
                $m->fecha = now();
            }
        });
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(
            InventarioItem::class,
            'item_id',
            'item_id'
        );
    }
}
