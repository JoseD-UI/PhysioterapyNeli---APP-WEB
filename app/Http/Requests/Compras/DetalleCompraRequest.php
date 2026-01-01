<?php

namespace App\Http\Requests\Compras;

use Illuminate\Foundation\Http\FormRequest;

class DetalleCompraRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'item_id' => 'required|string|size:36|exists:inventario_items,item_id',
            'cantidad' => 'required|numeric|min:0.001',
            'precio_unitario' => 'required|numeric|min:0'
        ];
    }
}
