<?php

namespace App\Http\Resources\Contabilidad;

use Illuminate\Http\Resources\Json\JsonResource;

class LibroResumenResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'libro_id'       => $this->libro_id,
            'tipo_libro'     => $this->tipo_libro,
            'periodo'        => $this->periodo,
            'total_gravado'  => $this->total_gravado,
            'total_igv'      => $this->total_igv,
            'total_general'  => $this->total_general,
            'generado_en'    => $this->created_at,
        ];
    }
}
