<?php

namespace App\Http\Resources\Reportes;

use Illuminate\Http\Resources\Json\JsonResource;

class SesionesMensualesResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'mes'               => $this->mes,
            'paciente_id'       => $this->paciente_id,
            'paciente'          => $this->paciente,
            'fisioterapeuta_id' => $this->fisioterapeuta_id,
            'sesiones_total'    => (int) $this->sesiones_total,
            'minutos_total'     => (int) $this->minutos_total,
        ];
    }
}
