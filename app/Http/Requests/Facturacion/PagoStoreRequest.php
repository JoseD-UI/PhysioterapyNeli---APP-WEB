<?php

namespace App\Http\Requests\Facturacion;

use Illuminate\Foundation\Http\FormRequest;

class PagoStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [

            /* ===================== RELACIÓN ===================== */
            'comprobante_id' =>
                'required|uuid|exists:facturacion_comprobantes,id',

            /* ===================== CONTEXTO CLÍNICO ===================== */
            'paciente_id' =>
                'nullable|uuid|exists:principal_personas,persona_id',

            'sesion_id' =>
                'nullable|uuid|exists:clinico_sesiones,sesion_id',

            /* ===================== PAGO ===================== */
            'medio_pago' => [
                'required',
                'string',
                'in:EFECTIVO,TARJETA,TRANSFERENCIA,YAPE,PLIN,DEPOSITO,OTRO'
            ],

            'monto' =>
                'required|numeric|min:0.01',

            'estado_pago' => [
                'nullable',
                'string',
                'in:pendiente,pagado,anulado'
            ],

            /* ===================== EVIDENCIA ===================== */
            'recibo' =>
                'nullable|string|max:120',

            'referencia_externa' =>
                'nullable|string|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'medio_pago.in' =>
                'El medio de pago no es válido',

            'monto.min' =>
                'El monto del pago debe ser mayor a cero',

            'estado_pago.in' =>
                'Estado de pago inválido',
        ];
    }
}
