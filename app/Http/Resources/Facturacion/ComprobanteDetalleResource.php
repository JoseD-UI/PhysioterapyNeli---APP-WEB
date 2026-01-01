<?php

namespace App\Http\Resources\Facturacion;

use Illuminate\Http\Resources\Json\JsonResource;

class ComprobanteDetalleResource extends JsonResource
{
    public function toArray($request): array
    {
        $esProducto = !empty($this->producto_id);

        return [
            'id' => $this->id,

            // Identificación
            'producto_id' => $this->producto_id,
            'tipo_item'   => $esProducto ? 'PRODUCTO' : 'SERVICIO',
            'descripcion' => $this->descripcion,

            // Cantidades
            'cantidad'        => (float) $this->cantidad,
            'precio_unitario' => (float) $this->precio_unitario,

            // Importes
            'subtotal' => (float) $this->subtotal,
            'igv'      => (float) $this->igv,
            'total'    => (float) $this->total,

            // Flags contables / SUNAT
            'afecto_igv' => ((float) $this->igv) > 0,
        ];
    }
}
