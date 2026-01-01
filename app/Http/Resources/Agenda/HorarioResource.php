<?php
namespace App\Http\Resources\Agenda;
use Illuminate\Http\Resources\Json\JsonResource;

class HorarioResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'horario_id' => $this->horario_id,
            'fisioterapeuta_id' => $this->fisioterapeuta_id,
            'dia_semana' => (int)$this->dia_semana,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
            'activo' => (bool)$this->activo,
            'creado_en' => $this->creado_en?->toDateTimeString(),
            'actualizado_en' => $this->actualizado_en?->toDateTimeString()
        ];
    }
}
