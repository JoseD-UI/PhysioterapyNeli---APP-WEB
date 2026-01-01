<?php

namespace App\Http\Controllers\Api\Facturacion;

use App\Http\Controllers\Controller;
use App\Services\Facturacion\FacturacionService;
use App\Http\Requests\Facturacion\ComprobanteStoreRequest;
use App\Http\Requests\Facturacion\ComprobanteAnularRequest;
use App\Http\Requests\Facturacion\PagoStoreRequest;
use App\Http\Resources\Facturacion\ComprobanteResource;
use App\Http\Resources\Facturacion\PagoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Exception;

class FacturacionController extends Controller
{
    public function __construct(
        protected FacturacionService $service
    ) {}

    /* =====================================================
     | COMPROBANTES
     ===================================================== */

    public function emitirComprobante(
        ComprobanteStoreRequest $request
    ): JsonResponse {
        try {
            $comprobante = $this->service
                ->emitirComprobante($request->validated())
                ->load(['detalles', 'pagos']);

            return response()->json(
                new ComprobanteResource($comprobante),
                Response::HTTP_CREATED
            );

        } catch (Exception $e) {
            return response()->json([
                'message' => 'No se pudo emitir el comprobante',
                'error'   => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function anularComprobante(
        string $comprobanteId,
        ComprobanteAnularRequest $request
    ): JsonResponse {
        try {
            $this->service->anularComprobante(
                $comprobanteId,
                $request->motivo
            );

            return response()->json([
                'message' => 'Comprobante anulado correctamente'
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'No se pudo anular el comprobante',
                'error'   => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /* =====================================================
     | PAGOS
     ===================================================== */

    public function registrarPago(
        PagoStoreRequest $request
    ): JsonResponse {
        try {
            $pago = $this->service
                ->registrarPago($request->validated());

            return response()->json(
                new PagoResource($pago),
                Response::HTTP_CREATED
            );

        } catch (Exception $e) {
            return response()->json([
                'message' => 'No se pudo registrar el pago',
                'error'   => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
