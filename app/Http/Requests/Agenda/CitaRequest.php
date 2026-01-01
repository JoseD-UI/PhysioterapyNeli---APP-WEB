<?php
namespace App\Http\Requests\Agenda;
use Illuminate\Foundation\Http\FormRequest;

class CitaRequest extends FormRequest
{
    public function authorize(){ return true; }
    public function rules()
    {
        $isUpdate = in_array($this->method(), ['PUT','PATCH']);
        return [
            'paciente_id' => 'required|string|size:36|exists:principal_personas,persona_id',
            'fisioterapeuta_id' => 'required|string|size:36|exists:principal_personas,persona_id',
            'servicio_id' => 'nullable|string|size:36|exists:clinico_servicios,servicio_id',
            'sala_id' => 'nullable|string|size:36|exists:principal_salas,sala_id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'timezone' => 'nullable|string',
            'creado_por' => 'nullable|string|size:36|exists:principal_usuarios,usuario_id',
            'estado_id' => $isUpdate ? 'nullable|string|size:36|exists:agenda_cita_estados,estado_id' : 'prohibited'
        ];
    }
}
