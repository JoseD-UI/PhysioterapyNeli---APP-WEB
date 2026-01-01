<?php

namespace App\Services\Inventario;

use App\Models\Inventario\Activo;
use Illuminate\Support\Str;

class ActivoService
{
    public function listar()
    {
        return Activo::orderBy('creado_en', 'desc')->get();
    }

    public function crear(array $data)
    {
        $data['activo_id'] = (string) Str::uuid();
        $data['estado_activo'] = 'ACTIVO';
        $data['creado_en'] = now();

        return Activo::create($data);
    }

    public function cambiarEstado(string $id, string $estado)
    {
        $activo = Activo::findOrFail($id);

        $activo->update([
            'estado_activo' => $estado
        ]);

        return $activo;
    }
}
