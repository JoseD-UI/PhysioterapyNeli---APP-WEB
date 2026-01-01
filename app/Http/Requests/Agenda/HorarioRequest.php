<?php
namespace App\Http\Requests\Agenda;
use Illuminate\Foundation\Http\FormRequest;

class HorarioRequest extends FormRequest
{
    public function authorize(){ return true; }
    public function rules()
    {
        return [
            'fisioterapeuta_id' => 'required|string|size:36|exists:principal_personas,persona_id',
            'dia_semana' => 'required|integer|min:0|max:6',
            'hora_inicio' => 'required|date_format:H:i:s',
            'hora_fin' => 'required|date_format:H:i:s|after:hora_inicio',
            'activo' => 'boolean'
        ];
    }
}
