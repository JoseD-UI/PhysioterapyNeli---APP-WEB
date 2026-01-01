<?php

namespace App\Http\Resources\Facturacion;

use Illuminate\Http\Resources\Json\JsonResource;

class SerieResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'tipo_comprobante'  => $this->tipo_comprobante,
            'serie'             => $this->serie,
            'ultimo_correlativo'=> $this->ultimo_correlativo,
            'activo'            => (bool) $this->activo,
        ];
    }
}
