<?php

namespace App\Http\Requests\Inventario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MovimientoUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => 'sometimes|exists:inventario_items,item_id',

            'tipo_movimiento' => [
                'sometimes',
                Rule::in(['entrada', 'salida']),
            ],

            'evento' => 'sometimes|string|max:100',

            'entidad_tipo' => 'sometimes|string|max:100',
            'entidad_id' => 'sometimes|string|size:36',

            'datos_anteriores' => 'sometimes|array',
            'datos_nuevos' => 'sometimes|array',

            'usuario_id' => 'sometimes|exists:principal_usuarios,usuario_id',
        ];
    }
}
