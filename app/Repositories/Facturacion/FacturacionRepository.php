<?php

namespace App\Repositories\Facturacion;

use App\Models\Facturacion\FacturacionComprobante;
use App\Models\Facturacion\FacturacionDetalle;
use App\Models\Facturacion\FacturacionPago;
use App\Models\Facturacion\FacturacionSerie;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class FacturacionRepository
{
    /* =====================================================
     | SERIE + CORRELATIVO (CONCURRENCIA REAL)
     | - NO padding aquí
     | - Bloqueo FOR UPDATE
     | - Obligatorio usar dentro de transaction()
     ===================================================== */
    public function obtenerSerieYCorrelativo(string $tipoComprobante): array
    {
        $serie = FacturacionSerie::where('tipo_comprobante', $tipoComprobante)
            ->where('activo', 1)
            ->lockForUpdate()
            ->first();

        if (!$serie) {
            throw new Exception(
                "No existe serie activa para el tipo {$tipoComprobante}"
            );
        }

        $nuevoCorrelativo = $serie->ultimo_correlativo + 1;

        $serie->update([
            'ultimo_correlativo' => $nuevoCorrelativo
        ]);

        return [
            'serie'       => $serie->serie,
            'correlativo' => $nuevoCorrelativo, // padding SOLO en Service / SUNAT
        ];
    }

    /* =====================================================
     | COMPROBANTES
     ===================================================== */
    public function crearComprobante(array $data): FacturacionComprobante
    {
        return FacturacionComprobante::create($data);
    }

    /**
     * 🔒 Se usa SIEMPRE para:
     * - anulación
     * - nota de crédito
     * - SUNAT
     */
    public function obtenerComprobanteConRelaciones(
        string $comprobanteId
    ): FacturacionComprobante {
        return FacturacionComprobante::with([
            'detalles',
            'pagos'
        ])->findOrFail($comprobanteId);
    }

    public function actualizarTotales(
        string $comprobanteId,
        float $subtotal,
        float $igv,
        float $total
    ): void {
        FacturacionComprobante::where('id', $comprobanteId)->update([
            'subtotal' => round($subtotal, 2),
            'igv'      => round($igv, 2),
            'total'    => round($total, 2),
        ]);
    }

    public function anularComprobante(
        string $comprobanteId,
        string $motivo
    ): void {
        FacturacionComprobante::where('id', $comprobanteId)->update([
            'estado'           => 'anulada',
            'fecha_anulacion'  => now(),
            'motivo_anulacion' => $motivo,
        ]);
    }

    /* =====================================================
     | DETALLES
     ===================================================== */
    public function crearDetalle(array $data): FacturacionDetalle
    {
        return FacturacionDetalle::create($data);
    }

    public function obtenerDetallesPorComprobante(
        string $comprobanteId
    ): Collection {
        return FacturacionDetalle::where(
            'comprobante_id',
            $comprobanteId
        )->get();
    }

    /* =====================================================
     | PAGOS
     ===================================================== */
    public function crearPago(array $data): FacturacionPago
    {
        return FacturacionPago::create($data);
    }

    public function anularPagos(string $comprobanteId): void
    {
        FacturacionPago::where('comprobante_id', $comprobanteId)
            ->where('estado_pago', '!=', 'anulado')
            ->update([
                'estado_pago' => 'anulado'
            ]);
    }

    public function obtenerPagosPorComprobante(
        string $comprobanteId
    ): Collection {
        return FacturacionPago::where(
            'comprobante_id',
            $comprobanteId
        )->get();
    }
}
