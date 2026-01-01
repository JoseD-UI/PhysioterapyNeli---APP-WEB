<?php

namespace App\Http\Requests\Facturacion;

use Illuminate\Foundation\Http\FormRequest;

class NotaCreditoRequest extends FormRequest
{
    public function rules()
    {
        return [
            'factura_id' => 'required|exists:facturacion_facturas,factura_id',
            'motivo_codigo' => 'required|string',
            'motivo_descripcion' => 'required|string',
            'detalles' => 'required|array|min:1',
            'detalles.*.descripcion' => 'required|string',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
            'detalles.*.precio_unitario' => 'required|numeric|min:0'
        ];
    }
}
