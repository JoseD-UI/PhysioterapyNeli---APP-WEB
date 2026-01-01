<?php

namespace App\Http\Resources\Clinico;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Principal\PersonaResource;
use App\Http\Resources\Clinico\ServicioResource;

class SesionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'sesion_id' => $this->sesion_id,
            'paciente' => new PersonaResource($this->paciente),
            'fisioterapeuta' => $this->fisioterapeuta ? new PersonaResource($this->fisioterapeuta) : null,
            'servicio' => $this->servicio ? new ServicioResource($this->servicio) : null,
            'fecha_atencion' => $this->fecha_atencion,
            'duracion_minutos' => $this->duracion_minutos,
            'notas' => $this->notas,
            'ejercicios_realizados' => $this->ejercicios_realizados,
            'materiales_usados' => $this->materiales_usados,
            'creado_en' => $this->creado_en,
        ];
    }
}
