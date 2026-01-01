<?php

namespace App\Http\Resources\Principal;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'persona_id' => $this->persona_id,
            'tipo_persona' => $this->tipo_persona,
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'dni' => $this->dni,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'creado_en' => $this->creado_en,
            'actualizado_en' => $this->actualizado_en,
        ];
    }
}
