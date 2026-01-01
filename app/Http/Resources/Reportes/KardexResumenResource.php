<?php

namespace App\Http\Resources\Reportes;

use Illuminate\Http\Resources\Json\JsonResource;

class KardexResumenResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'item_id'                 => $this->item_id,
            'stock_actual'            => (float) $this->stock_actual,
            'costo_promedio'          => (float) $this->costo_promedio,
            'saldo_valorizado'        => (float) $this->saldo_valorizado,
            'fecha_ultimo_movimiento' => $this->fecha_ultimo_movimiento,
            'metodo_valorizacion'     => $this->metodo_valorizacion,
        ];
    }
}
