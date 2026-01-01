<?php

namespace App\Http\Requests\Inventario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:200',

            'tipo' => [
                'required',
                'string',
                Rule::in(['PRODUCTO', 'INSUMO', 'SERVICIO']),
            ],

            'categoria_id' => 'required|exists:inventario_categorias,categoria_id',
            'unidad_medida_id' => 'required|exists:inventario_unidades,unidad_id',

            'descripcion' => 'nullable|string|max:500',

            'precio_unitario' => 'nullable|numeric|min:0',

            'stock_minimo' => 'required|numeric|min:0',
            
            'es_activo' => 'required|boolean',

        ];
    }
}
