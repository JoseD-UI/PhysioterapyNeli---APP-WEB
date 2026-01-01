<?php

namespace App\Http\Resources\Compras;

use Illuminate\Http\Resources\Json\JsonResource;

class DetalleCompraResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'item_id'        => $this->item_id,
            'cantidad'       => (float) $this->cantidad,
            'precio_unitario'=> (float) $this->precio_unitario,
            'subtotal'       => (float) $this->subtotal,
        ];
    }
}
