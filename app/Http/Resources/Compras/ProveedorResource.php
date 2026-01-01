<?php

namespace App\Http\Resources\Compras;

use Illuminate\Http\Resources\Json\JsonResource;

class ProveedorResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'proveedor_id' => $this->proveedor_id,
            'nombre'       => $this->nombre,
            'ruc'          => $this->ruc,
            'telefono'     => $this->telefono,
            'email'        => $this->email,
            'direccion'    => $this->direccion,
        ];
    }
}
