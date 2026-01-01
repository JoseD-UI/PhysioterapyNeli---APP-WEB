<?php

namespace App\Http\Requests\Facturacion;

use Illuminate\Foundation\Http\FormRequest;

class ComprobanteStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [

            /* ===================== SUNAT ===================== */
            'tipo_comprobante' => 'required|in:01,03,07,12',

            /* ===================== CLIENTE ===================== */
            'cliente_id' => 'nullable|uuid',

            'ruc_cliente' => [
                'nullable',
                'string',
                'size:11',
                'required_if:tipo_comprobante,01'
            ],

            'razon_social' => [
                'nullable',
                'string',
                'max:200',
                'required_with:ruc_cliente'
            ],

            'direccion_fiscal' => 'nullable|string|max:255',

            /* ===================== DETALLES ===================== */
            'detalles' => 'required|array|min:1',

            'detalles.*.producto_id' => [
                'nullable',
                'uuid',
                'exists:inventario_items,item_id'
            ],

            'detalles.*.descripcion' =>
                'required|string|max:255',

            'detalles.*.cantidad' =>
                'required|numeric|min:0.01',

            'detalles.*.precio_unitario' =>
                'required|numeric|min:0.00',

            /* ===================== NOTA DE CRÉDITO ===================== */
            'comprobante_referencia_id' => [
                'required_if:tipo_comprobante,07',
                'uuid',
                'exists:facturacion_comprobantes,id'
            ],

            'motivo_codigo' => [
                'required_if:tipo_comprobante,07',
                'string',
                'in:01,02,03,04,05,06,07,08,09,10,11,12,13'
            ],

            'motivo_descripcion' =>
                'required_if:tipo_comprobante,07|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_comprobante.in' =>
                'Tipo de comprobante SUNAT inválido',

            'ruc_cliente.required_if' =>
                'El RUC es obligatorio para facturas',

            'ruc_cliente.size' =>
                'El RUC debe tener exactamente 11 dígitos',

            'razon_social.required_with' =>
                'La razón social es obligatoria cuando se envía RUC',

            'detalles.required' =>
                'Debe registrar al menos un detalle',

            'detalles.*.cantidad.min' =>
                'La cantidad debe ser mayor a cero',

            'motivo_codigo.in' =>
                'Motivo de nota de crédito SUNAT inválido',
        ];
    }
}
