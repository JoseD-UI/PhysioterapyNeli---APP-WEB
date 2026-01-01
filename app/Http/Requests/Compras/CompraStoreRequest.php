<?php

namespace App\Http\Requests\Compras;

use Illuminate\Foundation\Http\FormRequest;

class CompraStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'proveedor_id' => 'nullable|uuid|exists:compras_proveedores,proveedor_id',
            'fecha_compra' => 'required|date',
            'detalles'     => 'required|array|min:1',

            'detalles.*.item_id'         => 'nullable|uuid|exists:inventario_items,item_id',
            'detalles.*.cantidad'        => 'required|numeric|min:0.001',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
        ];
    }



    public function messages()
    {
        return [
            'detalle.required' => 'El detalle de la compra es obligatorio y debe contener al menos una línea.',
            'detalle.min' => 'El detalle debe contener al menos una línea.',
            'detalle.*.item_id.exists' => 'El item enviado no existe.'
        ];
    }
}
