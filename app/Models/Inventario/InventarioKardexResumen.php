<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarioKardexResumen extends Model
{
    protected $table = 'vw_inventario_kardex_resumen';

    protected $primaryKey = 'item_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'stock_actual',
        'costo_promedio',
        'saldo_valorizado',
        'fecha_ultimo_movimiento',
        'metodo_valorizacion',
    ];

    protected $casts = [
        'stock_actual'            => 'decimal:3',
        'costo_promedio'          => 'decimal:2',
        'saldo_valorizado'        => 'decimal:2',
        'fecha_ultimo_movimiento' => 'datetime',
    ];

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
}
