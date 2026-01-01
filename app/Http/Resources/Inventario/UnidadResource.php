<?php

namespace App\Http\Resources\Inventario;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnidadResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'unidad_id' => $this->unidad_id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
        ];
    }
}
