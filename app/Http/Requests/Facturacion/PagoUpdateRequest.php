<?php

namespace App\Http\Requests\Facturacion;

use Illuminate\Foundation\Http\FormRequest;

class PagoUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [

            /* ===================== ESTADO ===================== */
            'estado_pago' => [
                'sometimes',
                'required',
                'string',
                'in:pendiente,pagado,anulado'
            ],

            /* ===================== EVIDENCIA ===================== */
            'recibo' => [
                'nullable',
                'string',
                'max:120'
            ],

            'referencia_externa' => [
                'nullable',
                'string',
                'max:200'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'estado_pago.in' =>
                'Estado de pago inválido',
        ];
    }
}
