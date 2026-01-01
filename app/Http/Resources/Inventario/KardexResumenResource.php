<?php

namespace App\Http\Resources\Inventario;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KardexResumenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'item_id' => $this->item_id,
            'nombre_item' => $this->nombre_item,

            'stock_actual' => (float) $this->stock_actual,
            'costo_promedio' => (float) $this->costo_promedio,
            'valor_stock' => (float) $this->valor_stock,
        ];
    }
}
