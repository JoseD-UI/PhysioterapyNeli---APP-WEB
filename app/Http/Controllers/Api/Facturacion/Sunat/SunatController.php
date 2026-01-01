<?php

namespace App\Http\Controllers\Api\Facturacion\Sunat;

use App\Http\Controllers\Controller;
use App\Services\Facturacion\Sunat\SunatService;
use App\Http\Resources\Facturacion\DocumentoSunatResource;
use Illuminate\Http\JsonResponse;
use Exception;

class SunatController extends Controller
{
    public function __construct(
        protected SunatService $service
    ) {}

    /* =====================================================
     | ENVIAR COMPROBANTE A SUNAT
     ===================================================== */
    public function enviar(string $comprobanteId): DocumentoSunatResource
    {
        $documento = $this->service->enviarComprobante($comprobanteId);

        return new DocumentoSunatResource($documento);
    }

    /* =====================================================
     | CONSULTAR ESTADO SUNAT
     ===================================================== */
    public function estado(string $comprobanteId): JsonResponse
    {
        $documento = $this->service->consultarEstado($comprobanteId);

        if (!$documento) {
            return response()->json([
                'message' => 'No existe información SUNAT para este comprobante'
            ], 404);
        }

        return response()->json(
            new DocumentoSunatResource($documento),
            200
        );
    }
}
