<?php

namespace App\Http\Resources\Principal;

use Illuminate\Http\Resources\Json\JsonResource;

class RolResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'rol_id' => $this->rol_id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'creado_en' => $this->creado_en,
            'actualizado_en' => $this->actualizado_en,
        ];
    }
}
