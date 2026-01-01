<?php

namespace App\Http\Resources\Clinico;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Principal\PersonaResource;

class HistoriaClinicaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'historia_id' => $this->historia_id,
            'persona' => new PersonaResource($this->persona),
            'motivo_consulta' => $this->motivo_consulta,
            'antecedentes' => $this->antecedentes,
            'alergias' => $this->alergias,
            'diagnostico_inicial' => $this->diagnostico_inicial,
            'recomendaciones' => $this->recomendaciones,
            'creado_en' => $this->creado_en,
            'actualizado_en' => $this->actualizado_en,
        ];
    }
}

