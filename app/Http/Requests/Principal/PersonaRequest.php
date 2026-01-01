<?php

namespace App\Http\Requests\Principal;

use Illuminate\Foundation\Http\FormRequest;

class PersonaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tipo_persona' => 'required|string|max:50',
            'nombres' => 'required|string|max:150',
            'apellidos' => 'nullable|string|max:150',
            'dni' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:200',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ];
    }
}
