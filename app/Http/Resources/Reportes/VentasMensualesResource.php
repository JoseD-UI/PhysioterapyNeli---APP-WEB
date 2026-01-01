<?php

namespace App\Http\Resources\Reportes;

use Illuminate\Http\Resources\Json\JsonResource;

class VentasMensualesResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'periodo'               => $this->periodo,
            'tipo_comprobante'      => $this->tipo_comprobante,
            'cantidad_comprobantes' => (int) $this->cantidad_comprobantes,
            'total_gravado'         => (float) $this->total_gravado,
            'total_igv'             => (float) $this->total_igv,
            'total_general'         => (float) $this->total_general,
        ];
    }
}
