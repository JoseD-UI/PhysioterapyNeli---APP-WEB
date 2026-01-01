<?php

namespace App\Http\Requests\Facturacion;

use Illuminate\Foundation\Http\FormRequest;

class SerieUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // ÚNICAMENTE se permite activar o desactivar
            'activo' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'activo.required' =>
                'Debe indicar si la serie estará activa o inactiva',

            'activo.boolean' =>
                'El campo activo debe ser true o false',
        ];
    }
}
