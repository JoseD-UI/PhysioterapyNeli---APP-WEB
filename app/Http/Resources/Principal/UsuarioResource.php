<?php

namespace App\Http\Resources\Principal;

use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'usuario_id' => $this->usuario_id,
            'usuario' => $this->usuario,
            'estado' => $this->estado,

            'persona' => new PersonaResource($this->persona),
            'rol' => new RolResource($this->rol),

            'creado_en' => $this->creado_en,
            'actualizado_en' => $this->actualizado_en,
        ];
    }
}
