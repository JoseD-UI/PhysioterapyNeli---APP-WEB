<?php

namespace App\Http\Requests\Inventario;

use Illuminate\Validation\Rule;

class ItemUpdateRequest extends ItemStoreRequest
{
    public function rules(): array
    {
        return [
            'nombre' => 'sometimes|string|max:200',

            'tipo' => [
                'sometimes',
                'string',
                Rule::in(['PRODUCTO', 'INSUMO', 'SERVICIO']),
            ],

            'categoria_id' => 'sometimes|exists:inventario_categorias,categoria_id',
            'unidad_medida_id' => 'sometimes|exists:inventario_unidades,unidad_id',

            'descripcion' => 'sometimes|nullable|string|max:500',

            'precio_unitario' => 'sometimes|nullable|numeric|min:0',

            'stock_minimo' => 'sometimes|numeric|min:0',
        ];
    }
}
