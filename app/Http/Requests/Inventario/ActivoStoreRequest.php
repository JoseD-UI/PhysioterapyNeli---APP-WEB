<?php

namespace App\Http\Requests\Inventario;

use Illuminate\Foundation\Http\FormRequest;

class ActivoStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_activo'     => 'required|string|max:200',
            'categoria'         => 'required|string|max:100',
            'fecha_compra'      => 'required|date',
            'valor_compra'      => 'required|numeric|min:0',
            'vida_util_meses'   => 'required|integer|min:1',
            'proveedor_id'      => 'nullable|exists:compras_proveedores,proveedor_id',
        ];
    }
}
