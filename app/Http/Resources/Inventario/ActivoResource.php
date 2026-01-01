<?php

namespace App\Http\Resources\Inventario;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'activo_id'        => $this->activo_id,
            'nombre_activo'    => $this->nombre_activo,
            'categoria'        => $this->categoria,
            'estado_activo'    => $this->estado_activo,
            'fecha_compra'     => $this->fecha_compra,
            'valor_compra'     => (float) $this->valor_compra,
            'vida_util_meses'  => $this->vida_util_meses,
            'proveedor_id'     => $this->proveedor_id,
            'creado_en'        => $this->creado_en,
        ];
    }
}
