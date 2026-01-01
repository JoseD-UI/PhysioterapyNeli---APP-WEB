<?php
namespace App\Http\Resources\Agenda;
use Illuminate\Http\Resources\Json\JsonResource;

class CitaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'cita_id' => $this->cita_id,
            'paciente' => $this->whenLoaded('paciente', fn() => [
                'persona_id'=>$this->paciente->persona_id,'nombres'=>$this->paciente->nombres,'apellidos'=>$this->paciente->apellidos
            ]),
            'fisioterapeuta' => $this->whenLoaded('fisioterapeuta', fn() => [
                'persona_id'=>$this->fisioterapeuta->persona_id,'nombres'=>$this->fisioterapeuta->nombres,'apellidos'=>$this->fisioterapeuta->apellidos
            ]),
            'servicio' => $this->whenLoaded('servicio', fn() => $this->servicio ? ['servicio_id'=>$this->servicio->servicio_id,'nombre'=>$this->servicio->nombre] : null),
            'sala' => $this->whenLoaded('sala', fn() => $this->sala ? ['sala_id'=>$this->sala->sala_id,'nombre'=>$this->sala->nombre_sala] : null),
            'fecha_inicio_utc' => $this->fecha_inicio?->toDateTimeString(),
            'fecha_fin_utc' => $this->fecha_fin?->toDateTimeString(),
            'estado' => $this->whenLoaded('estado', fn() => $this->estado ? ['estado_id'=>$this->estado->estado_id,'codigo'=>$this->estado->codigo,'nombre'=>$this->estado->nombre] : null),
            'creado_por' => $this->creadoPor ? ['usuario_id'=>$this->creadoPor->usuario_id,'username'=>$this->creadoPor->username] : null,
            'creado_en' => $this->creado_en?->toDateTimeString()
        ];
    }
}
