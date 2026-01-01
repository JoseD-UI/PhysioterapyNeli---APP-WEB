<?php

namespace App\Http\Requests\Clinico;

use Illuminate\Foundation\Http\FormRequest;

class SesionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cita_id' => 'nullable|string|max:36',
            'paciente_id' => 'required|string|max:36',
            'fisioterapeuta_id' => 'nullable|string|max:36',
            'servicio_id' => 'nullable|string|max:36',
            'fecha_atencion' => 'required|date',
            'duracion_minutos' => 'nullable|integer|min:1',
            'notas' => 'nullable|string',
            'ejercicios_realizados' => 'nullable|string',
            'materiales_usados' => 'nullable|json'
        ];
    }
}

