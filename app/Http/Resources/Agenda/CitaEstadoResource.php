<?php
namespace App\Http\Resources\Agenda;
use Illuminate\Http\Resources\Json\JsonResource;

class CitaEstadoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'estado_id' => $this->estado_id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'activo' => (bool)$this->activo,
            'creado_en' => $this->creado_en?->toDateTimeString()
        ];
    }
}
