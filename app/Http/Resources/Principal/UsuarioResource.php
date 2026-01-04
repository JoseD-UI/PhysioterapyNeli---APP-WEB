<?php

namespace App\Http\Resources\Principal;

use Illuminate\Http\Resources\Json\JsonResource;

class UsuarioResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'activo' => $this->activo,

            'persona' => new PersonaResource($this->persona),
            'rol' => new RolResource($this->rol),

            'creado_en' => $this->creado_en,
            'actualizado_en' => $this->actualizado_en,
        ];
    }
}
