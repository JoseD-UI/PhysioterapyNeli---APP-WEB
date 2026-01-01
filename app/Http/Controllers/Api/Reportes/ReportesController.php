<?php

namespace App\Http\Controllers\Api\Reportes;

use App\Http\Controllers\Controller;
use App\Services\Reportes\ReportesService;
use App\Http\Resources\Reportes\SesionesMensualesResource;
use App\Http\Resources\Reportes\KardexResumenResource;
use App\Http\Resources\Reportes\VentasMensualesResource;
use Illuminate\Http\Request;

class ReportesController extends Controller
{
    public function __construct(
        protected ReportesService $service
    ) {}

    /* =====================================================
     | REPORTE 1: SESIONES MENSUALES
     | GET /reportes/sesiones-mensuales?mes=2025-01
     ===================================================== */
    public function sesionesMensuales(Request $request)
    {
        $data = $this->service->sesionesMensuales(
            $request->query('mes')
        );

        return SesionesMensualesResource::collection($data);
    }

    /* =====================================================
     | REPORTE 2: KARDEX RESUMEN
     | GET /reportes/kardex-resumen
     ===================================================== */
    public function kardexResumen()
    {
        $data = $this->service->kardexResumen();

        return KardexResumenResource::collection($data);
    }

    /* =====================================================
     | REPORTE 3: VENTAS MENSUALES
     | GET /reportes/ventas-mensuales?periodo=2025-01
     ===================================================== */
    public function ventasMensuales(Request $request)
    {
        $data = $this->service->ventasMensuales(
            $request->query('periodo')
        );

        return VentasMensualesResource::collection($data);
    }
}

