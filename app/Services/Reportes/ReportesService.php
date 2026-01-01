<?php

namespace App\Services\Reportes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ReportesService
{
    /* =====================================================
     | REPORTE 1: SESIONES MENSUALES
     | Fuente: reportes_vw_sesiones_mensuales
     ===================================================== */
    public function sesionesMensuales(?string $mes = null): Collection
    {
        $query = DB::table('reportes_vw_sesiones_mensuales');

        if ($mes) {
            $query->where('mes', $mes);
        }

        return $query
            ->orderBy('mes')
            ->orderBy('paciente')
            ->get();
    }

    /* =====================================================
     | REPORTE 2: KARDEX RESUMEN (INVENTARIO)
     | Fuente: vw_inventario_kardex_resumen
     ===================================================== */
    public function kardexResumen(): Collection
    {
        return DB::table('vw_inventario_kardex_resumen')
            ->orderBy('fecha_ultimo_movimiento', 'desc')
            ->get();
    }

    /* =====================================================
     | REPORTE 3: VENTAS MENSUALES (FACTURACIÓN)
     | Fuente: facturacion_comprobantes
     ===================================================== */
    public function ventasMensuales(?string $periodo = null): Collection
    {
        $query = DB::table('facturacion_comprobantes')
            ->selectRaw("
                DATE_FORMAT(fecha_emision, '%Y-%m') AS periodo,
                tipo_comprobante,
                COUNT(*) AS cantidad_comprobantes,
                SUM(subtotal) AS total_gravado,
                SUM(igv) AS total_igv,
                SUM(total) AS total_general
            ")
            ->where('estado', 'emitida')
            ->groupBy('periodo', 'tipo_comprobante')
            ->orderBy('periodo', 'desc');

        if ($periodo) {
            $query->having('periodo', $periodo);
        }

        return $query->get();
    }
}
