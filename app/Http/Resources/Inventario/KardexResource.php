<?php

namespace App\Http\Resources\Inventario;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KardexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'kardex_id' => $this->kardex_id,
            'item_id' => $this->item_id,

            'fecha' => $this->fecha->format('Y-m-d H:i:s'),

            'tipo_movimiento' => $this->tipo_movimiento,
            'origen' => $this->origen,

            'cantidad' => (float) $this->cantidad,

            'saldo_cantidad' => (float) $this->saldo_cantidad,
            'saldo_valorizado' => (float) $this->saldo_valorizado,

            'costo_unitario' => (float) $this->costo_unitario,
            'costo_promedio_resultante' => (float) $this->costo_promedio_resultante,

            'valor_total' => (float) $this->valor_total,

            'documento' => [
                'tipo' => $this->documento_tipo,
                'serie' => $this->documento_serie,
                'correlativo' => $this->documento_correlativo,
            ],

            'referencia' => [
                'tipo' => $this->referencia_tipo,
                'id' => $this->referencia_id,
            ],

            'metodo_valorizacion' => $this->metodo_valorizacion,

            'usuario_id' => $this->usuario_id,
            'nota' => $this->nota,
        ];
    }
}
