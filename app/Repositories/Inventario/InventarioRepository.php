<?php

namespace App\Repositories\Inventario;

use App\Models\Inventario\InventarioItem;
use App\Models\Inventario\Categoria;
use App\Models\Inventario\Unidad;
use App\Models\Inventario\InventarioKardex;
use App\Models\Inventario\InventarioKardexResumen;
use Illuminate\Support\Facades\DB;

class InventarioRepository
{
    /* ===================== ITEMS ===================== */

    public function getItems()
    {
        return InventarioItem::orderBy('nombre')->get();
    }

    public function findItemById(string $itemId): InventarioItem
    {
        return InventarioItem::findOrFail($itemId);
    }

    public function createItem(array $data): InventarioItem
    {
        return InventarioItem::create($data);
    }

    public function updateItem(string $itemId, array $data): InventarioItem
    {
        $item = $this->findItemById($itemId);
        $item->update($data);

        return $item;
    }

    public function updateStockAndCost(
        string $itemId,
        float $stockActual,
        float $costoPromedio,
        ?float $ultimoCosto = null
    ): void {
        InventarioItem::where('item_id', $itemId)->update([
            'stock_actual'   => $stockActual,
            'costo_promedio' => $costoPromedio,
            'ultimo_costo'   => $ultimoCosto,
        ]);
    }

    /* ===================== CATEGORÍAS ===================== */

    public function getCategorias()
    {
        return Categoria::where('activo', 1)
            ->orderBy('nombre')
            ->get();
    }

    public function createCategoria(array $data): Categoria
    {
        return Categoria::create($data);
    }

    /* ===================== UNIDADES ===================== */

    public function getUnidades()
    {
        return Unidad::orderBy('nombre')->get();
    }

    public function createUnidad(array $data): Unidad
    {
        return Unidad::create($data);
    }

    /* ===================== KARDEX ===================== */

    /**
     * Obtiene el último movimiento del kardex (bloqueado)
     */
    public function getUltimoKardexBloqueado(string $itemId): ?InventarioKardex
    {
        return InventarioKardex::where('item_id', $itemId)
            ->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->lockForUpdate()
            ->first();
    }

    public function createKardex(array $data): InventarioKardex
    {
        return InventarioKardex::create($data);
    }

    public function getKardexByItem(string $itemId)
    {
        return InventarioKardex::where('item_id', $itemId)
            ->orderBy('fecha', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /* ===================== KARDEX RESUMEN ===================== */

    /**
     * Consulta rápida valorizada (vista)
     */
    public function getKardexResumenByItem(string $itemId): ?InventarioKardexResumen
    {
        return InventarioKardexResumen::where('item_id', $itemId)->first();
    }

    public function getKardexResumenAll()
    {
        return InventarioKardexResumen::orderBy('nombre_item')->get();
    }
}
