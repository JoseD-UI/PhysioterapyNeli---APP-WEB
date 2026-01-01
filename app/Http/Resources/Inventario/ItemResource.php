<?php

namespace App\Http\Resources\Inventario;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'item_id' => $this->item_id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,

            'categoria_id' => $this->categoria_id,
            'unidad_medida_id' => $this->unidad_medida_id,

            'precio_unitario' => (float) $this->precio_unitario,
            'costo_promedio' => (float) $this->costo_promedio,
            'ultimo_costo' => (float) $this->ultimo_costo,

            'stock_actual' => (float) $this->stock_actual,
            'stock_minimo' => (float) $this->stock_minimo,
            'es_activo'    => $this->es_activo,

            'activo' => (bool) $this->activo,
        ];
    }
}
