<?php
namespace App\Http\Resources\Principal;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'sala_id' => $this->sala_id,
            'nombre_sala' => $this->nombre_sala,
            'descripcion' => $this->descripcion,
            'activa' => (bool)$this->activa,
            'creado_en' => $this->creado_en,
            'actualizado_en' => $this->actualizado_en
        ];
    }
}

