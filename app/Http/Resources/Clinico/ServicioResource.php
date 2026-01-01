<?php

namespace App\Http\Resources\Clinico;

use Illuminate\Http\Resources\Json\JsonResource;

class ServicioResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'servicio_id' => $this->servicio_id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'duracion_minutos' => $this->duracion_minutos,
            'precio' => $this->precio,
            'activo' => $this->activo,
            'creado_en' => $this->creado_en,
        ];
    }
}
