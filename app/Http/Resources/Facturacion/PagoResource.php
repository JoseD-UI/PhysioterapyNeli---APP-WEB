<?php

namespace App\Http\Resources\Facturacion;

use Illuminate\Http\Resources\Json\JsonResource;

class PagoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,

            'monto'       => (float) $this->monto,
            'medio_pago'  => $this->medio_pago,
            'estado'      => $this->estado_pago,
            'fecha_pago'  => $this->fecha_pago,

            // Relación clínica
            'paciente_id' => $this->paciente_id,
            'sesion_id'   => $this->sesion_id,

            // Relación contable
            'comprobante_id' => $this->comprobante_id,

            // Evidencia
            'recibo'             => $this->recibo,
            'referencia_externa' => $this->referencia_externa,

            // Flags útiles para frontend / reportes
            'es_anulado' => $this->estado_pago === 'anulado',
        ];
    }
}
