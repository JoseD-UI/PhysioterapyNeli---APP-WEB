<?php

namespace App\Http\Resources\Inventario;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovimientoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'movimiento_id' => $this->movimiento_id,
            'item_id' => $this->item_id,

            'evento' => $this->evento,

            'entidad' => [
                'tipo' => $this->entidad_tipo,
                'id' => $this->entidad_id,
            ],

            'datos_anteriores' => $this->datos_anteriores,
            'datos_nuevos' => $this->datos_nuevos,

            'usuario_id' => $this->usuario_id,

            'fecha_movimiento' => $this->fecha_movimiento
                ? $this->fecha_movimiento->format('Y-m-d H:i:s')
                : null,
        ];
    }
}
