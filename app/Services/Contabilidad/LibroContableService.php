<?php

namespace App\Services\Contabilidad;

use App\Repositories\Contabilidad\LibroResumenRepository;
use Illuminate\Support\Facades\DB;

class LibroContableService
{
    public function __construct(
        protected LibroResumenRepository $repository
    ) {}

    /**
     * Genera el libro contable de un periodo (idempotente)
     */
    public function generar(string $tipoLibro, string $periodo)
    {
        // Si ya existe, no recalcula
        $existente = $this->repository->buscarPorPeriodo($tipoLibro, $periodo);
        if ($existente) {
            return $existente;
        }

        return DB::transaction(function () use ($tipoLibro, $periodo) {

            $totales = DB::table('facturacion_comprobantes')
                ->where('estado', 'emitida')
                ->whereRaw("DATE_FORMAT(fecha_emision,'%Y-%m') = ?", [$periodo])
                ->selectRaw('
                    SUM(subtotal) AS total_gravado,
                    SUM(igv) AS total_igv,
                    SUM(total) AS total_general
                ')
                ->first();

            return $this->repository->crear([
                'tipo_libro'    => $tipoLibro,
                'periodo'       => $periodo,
                'total_gravado' => $totales->total_gravado ?? 0,
                'total_igv'     => $totales->total_igv ?? 0,
                'total_general' => $totales->total_general ?? 0,
            ]);
        });
    }

    public function consultar(string $tipoLibro, string $periodo)
    {
        return $this->repository->buscarPorPeriodo($tipoLibro, $periodo);
    }

    public function listar(array $filtros = [])
    {
        return $this->repository->listar($filtros);
    }
}
