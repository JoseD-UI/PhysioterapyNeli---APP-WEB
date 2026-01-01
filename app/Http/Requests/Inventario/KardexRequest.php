<?php

namespace App\Http\Requests\Inventario;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KardexRequest extends FormRequest
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

            'cantidad' => 'required|numeric|min:0.001',

            // Solo requerido para ENTRADAS (compras, ajustes positivos)
            'costo_unitario' => 'nullable|numeric|min:0',

            'origen' => 'nullable|string|max:50', // compra | venta | ajuste | nota_credito

            'documento_tipo' => 'nullable|string|max:20',
            'documento_serie' => 'nullable|string|max:10',
            'documento_correlativo' => 'nullable|integer|min:1',

            'referencia_tipo' => 'nullable|string|max:100',
            'referencia_id' => 'nullable|string|size:36',

            'usuario_id' => 'nullable|exists:principal_usuarios,usuario_id',

            'nota' => 'nullable|string|max:500',
        ];
    }

    /**
     * Validaciones adicionales según tipo de movimiento
     */
    protected function prepareForValidation(): void
    {
        if ($this->tipo_movimiento === 'entrada' && !$this->has('costo_unitario')) {
            $this->merge([
                'costo_unitario' => null,
            ]);
        }
    }
}
