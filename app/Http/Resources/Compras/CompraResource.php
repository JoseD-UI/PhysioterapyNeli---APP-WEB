<?php

namespace App\Http\Resources\Compras;

use Illuminate\Http\Resources\Json\JsonResource;

class CompraResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'compra_id'   => $this->compra_id,
            'fecha'       => $this->fecha_compra,
            'subtotal'    => (float) $this->subtotal,
            'igv'         => (float) $this->igv,
            'total'       => (float) $this->total,
            'estado'      => $this->estado,
            'proveedor'   => $this->proveedor?->nombre,
            'detalles'    => DetalleCompraResource::collection($this->whenLoaded('detalles')),
        ];
    }
}
