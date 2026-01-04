<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class InventarioMovimiento extends Model
{
    protected $table = 'inventario_movimientos';
    protected $primaryKey = 'movimiento_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'item_id',

        // Auditoría
        'evento',          // KARDEX_ENTRADA | KARDEX_SALIDA | AJUSTE_STOCK | ACTUALIZACION_ITEM
        'entidad_tipo',    // INVENTARIO_KARDEX | COMPRA | FACTURA | NOTA_CREDITO
        'entidad_id',

        'datos_anteriores',
        'datos_nuevos',

        'usuario_id',
        'fecha_movimiento'
    ];

    protected $casts = [
        'datos_anteriores' => 'array',
        'datos_nuevos'     => 'array',
        'fecha_movimiento' => 'datetime',
    ];

    /** Eventos estándar */
    public const EVENTO_KARDEX_ENTRADA = 'KARDEX_ENTRADA';
    public const EVENTO_KARDEX_SALIDA  = 'KARDEX_SALIDA';
    public const EVENTO_AJUSTE_STOCK   = 'AJUSTE_STOCK';
    public const EVENTO_ACTUALIZACION  = 'ACTUALIZACION_ITEM';

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }

            if (empty($model->fecha_movimiento)) {
                $model->fecha_movimiento = now();
            }
        });
    }

    /**
     * Relación con Item
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(
            InventarioItem::class,
            'item_id',
            'item_id'
        );
    }

    /**
     * Relación con Usuario
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(
            \App\Models\Principal\Usuario::class,
            'usuario_id',
            'id'
        );
    }
}
