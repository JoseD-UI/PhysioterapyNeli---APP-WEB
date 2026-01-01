<?php

namespace App\Http\Requests\Facturacion;

use Illuminate\Foundation\Http\FormRequest;

class ComprobanteAnularRequest extends FormRequest
{
    public function rules(): array
    {
        return [

            /* ===================== SUNAT ===================== */
            'motivo_codigo' => [
                'required',
                'string',
                'in:01,02,03,04'
            ],

            'motivo_descripcion' => [
                'required',
                'string',
                'max:255'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'motivo_codigo.required' =>
                'Debe indicar el motivo de anulación SUNAT',

            'motivo_codigo.in' =>
                'Motivo de anulación SUNAT inválido',

            'motivo_descripcion.required' =>
                'Debe describir el motivo de la anulación',
        ];
    }
}
