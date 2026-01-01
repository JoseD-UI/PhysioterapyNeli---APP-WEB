<?php
namespace App\Http\Resources\Clinico;
use Illuminate\Http\Resources\Json\JsonResource;

class TipoServicioResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'tipo_id' => $this->tipo_id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'activo' => (bool)$this->activo,
            'creado_en' => $this->creado_en,
            'actualizado_en' => $this->actualizado_en
        ];
    }
}

