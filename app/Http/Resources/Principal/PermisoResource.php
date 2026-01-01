<?php
namespace App\Http\Resources\Principal;
use Illuminate\Http\Resources\Json\JsonResource;

class PermisoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'permiso_id' => $this->permiso_id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'creado_en' => $this->creado_en
        ];
    }
}

