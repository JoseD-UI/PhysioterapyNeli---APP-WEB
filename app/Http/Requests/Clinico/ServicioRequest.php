<?php

namespace App\Http\Requests\Clinico;

use Illuminate\Foundation\Http\FormRequest;

class ServicioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'duracion_minutos' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'activo' => 'boolean'
        ];
    }
}

