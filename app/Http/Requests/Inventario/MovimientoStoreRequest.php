<?php

namespace App\Http\Requests\Inventario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MovimientoStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_id' => 'required|exists:inventario_items,item_id',

            'tipo_movimiento' => [
                'required',
                Rule::in(['entrada', 'salida']),
            ],

            'evento' => 'required|string|max:100',

            'entidad_tipo' => 'required|string|max:100',
            'entidad_id' => 'required|string|size:36',

            'datos_anteriores' => 'nullable|array',
            'datos_nuevos' => 'nullable|array',

            'usuario_id' => 'nullable|exists:principal_usuarios,usuario_id',
        ];
    }
}
