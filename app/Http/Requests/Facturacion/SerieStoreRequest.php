<?php

namespace App\Http\Requests\Facturacion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SerieStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            /* ===================== SUNAT ===================== */
            'tipo_comprobante' => [
                'required',
                'string',
                Rule::in(['01','03','07','12']),
            ],

            /* ===================== SERIE ===================== */
            'serie' => [
                'required',
                'string',
                'max:4',
                'regex:/^[A-Z0-9]+$/',
                Rule::unique('facturacion_series')
                    ->where(fn ($q) =>
                        $q->where('tipo_comprobante', $this->tipo_comprobante)
                    ),
            ],

            /* ===================== CONTROL ===================== */
            'ultimo_correlativo' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'activo' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'serie.regex' =>
                'La serie debe estar en mayúsculas y sin espacios (ej: F001)',

            'serie.unique' =>
                'Ya existe una serie registrada para este tipo de comprobante',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('serie')) {
            $this->merge([
                'serie' => strtoupper(trim($this->serie)),
            ]);
        }
    }
}
