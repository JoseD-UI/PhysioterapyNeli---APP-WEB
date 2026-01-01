<?php

namespace App\Repositories\Contabilidad;

use App\Models\Contabilidad\ContabilidadLibroResumen;

class LibroResumenRepository
{
    public function buscarPorPeriodo(string $tipoLibro, string $periodo): ?ContabilidadLibroResumen
    {
        return ContabilidadLibroResumen::where('tipo_libro', $tipoLibro)
            ->where('periodo', $periodo)
            ->first();
    }

    public function crear(array $data): ContabilidadLibroResumen
    {
        return ContabilidadLibroResumen::create($data);
    }

    public function listar(array $filtros = [])
    {
        $query = ContabilidadLibroResumen::query();

        if (!empty($filtros['tipo_libro'])) {
            $query->where('tipo_libro', $filtros['tipo_libro']);
        }

        if (!empty($filtros['periodo'])) {
            $query->where('periodo', $filtros['periodo']);
        }

        return $query->orderBy('periodo', 'desc')->get();
    }
}
