<?php

namespace App\Http\Requests\Contabilidad;

use Illuminate\Foundation\Http\FormRequest;

class GenerarLibroResumenRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Aquí luego puedes meter permisos contables
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_libro' => [
                'required',
                'string',
                'max:255',
                // ejemplo: ventas, compras
            ],
            'periodo' => [
                'required',
                'regex:/^\d{4}-\d{2}$/',
                // Formato SUNAT: YYYY-MM
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_libro.required' => 'El tipo de libro es obligatorio.',
            'periodo.required'   => 'El periodo es obligatorio.',
            'periodo.regex'      => 'El periodo debe tener el formato YYYY-MM.',
        ];
    }
}
