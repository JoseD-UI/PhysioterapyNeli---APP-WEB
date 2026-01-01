<?php

namespace App\Http\Requests\Compras;

use Illuminate\Foundation\Http\FormRequest;

class ProveedorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nombre'    => 'required|string|max:200',
            'ruc'       => 'nullable|string|max:20',
            'telefono'  => 'nullable|string|max:50',
            'email'     => 'nullable|email|max:200',
            'direccion' => 'nullable|string',
        ];
    }
}
