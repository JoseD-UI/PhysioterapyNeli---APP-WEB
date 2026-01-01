<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventarioItem extends Model
{
    protected $table = 'inventario_items';
    protected $primaryKey = 'item_id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Campos asignables
     * NOTA SUNAT:
     * stock_actual y costo_promedio se actualizan SOLO vía Kardex
     */
    protected $fillable = [
        'categoria_id',
        'unidad_medida_id',
        'nombre',
        'descripcion',
        'tipo', // PRODUCTO | SERVICIO
        'precio_unitario',
        'ultimo_costo',
        'stock_minimo',
        'es_activo',
        'activo'
    ];

    /**
     * Casts obligatorios SUNAT
     */
    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'costo_promedio'  => 'decimal:6',
        'ultimo_costo'    => 'decimal:6',
        'stock_actual'    => 'decimal:6',
        'stock_minimo'    => 'decimal:6',
        'es_activo'       => 'boolean',
        'activo'          => 'boolean',
    ];

    /**
     * Relación con Kardex
     * SUNAT exige trazabilidad histórica
     */
    public function kardex(): HasMany
    {
        return $this->hasMany(
            InventarioKardex::class,
            'item_id',
            'item_id'
        )->orderBy('fecha', 'asc');
    }

    /**
     * Determina si el item maneja inventario
     */
    public function manejaStock(): bool
    {
        return strtoupper($this->tipo) === 'PRODUCTO';
    }

    /**
     * Verifica si está activo
     */
    public function estaActivo(): bool
    {
        return $this->activo === true;
    }

    public function esActivo(): bool
    {
        return $this->es_activo === true;
    }
}
