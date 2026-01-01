<?php

namespace App\Http\Requests\Clinico;

use Illuminate\Foundation\Http\FormRequest;

class HistoriaClinicaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'persona_id' => 'required|string|max:36',
            'motivo_consulta' => 'nullable|string',
            'antecedentes' => 'nullable|string',
            'alergias' => 'nullable|string',
            'diagnostico_inicial' => 'nullable|string',
            'recomendaciones' => 'nullable|string'
        ];
    }
}

