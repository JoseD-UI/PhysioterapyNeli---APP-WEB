<?php

namespace App\Http\Requests\Principal;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'persona_id' => 'required|string|max:36',
            'rol_id' => 'required|string|max:36',

            'usuario' => 'required|string|max:50',
            'password' => 'required|string|max:200',

            'estado' => 'required|string|in:activo,inactivo',
        ];
    }
}

