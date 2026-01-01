<?php
namespace App\Http\Resources\Agenda;
use Illuminate\Http\Resources\Json\JsonResource;

class DiaNoLaborableResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'nolaborable_id' => $this->nolaborable_id,
            'fecha' => $this->fecha,
            'fisioterapeuta_id' => $this->fisioterapeuta_id,
            'motivo' => $this->motivo,
            'creado_en' => $this->creado_en?->toDateTimeString(),
            'actualizado_en' => $this->actualizado_en?->toDateTimeString()
        ];
    }
}
