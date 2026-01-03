<?php

namespace App\Services\Facturacion;

use App\Models\Facturacion\FacturacionComprobante;
use App\Repositories\Facturacion\FacturacionRepository;
use App\Services\Inventario\InventarioService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

/**
 * Servicio especializado para emisión de Notas de Crédito (07)
 */
class NotaCreditoService
{
    public function __construct(
        protected FacturacionRepository $repository,
        protected InventarioService $inventario
    ) {}

    /**
     * Emitir Nota de Crédito
     * 
     * @param array $data Datos de la nota de crédito
     * @return FacturacionComprobante
     * @throws Exception
     */
    public function emitir(array $data): FacturacionComprobante
    {
        return DB::transaction(function () use ($data) {

            /* ===================== 1. VALIDACIONES SUNAT ===================== */

            $this->validarDatos($data);

            /* ===================== 2. COMPROBANTE ORIGINAL ===================== */

            $comprobanteOriginal = $this->validarComprobanteOriginal(
                $data['comprobante_referencia_id']
            );

            /* ===================== 3. SERIE + CORRELATIVO (07) ===================== */

            $serieData = $this->repository->obtenerSerieYCorrelativo('07');

            /* ===================== 4. CREAR NOTA DE CRÉDITO ===================== */

            $notaCredito = $this->crearNotaCredito(
                $serieData,
                $comprobanteOriginal,
                $data
            );

            /* ===================== 5. DETALLES (PARCIAL O TOTAL) ===================== */

            $detalles = $data['detalles'] ?? $comprobanteOriginal->detalles;
            $totales = $this->procesarDetalles($notaCredito, $detalles);

            /* ===================== 6. TOTALES ===================== */

            $this->repository->actualizarTotales(
                $notaCredito->id,
                $totales['subtotal'],
                $totales['igv'],
                $totales['total']
            );

            /* ===================== 7. ANULACIÓN TOTAL (SUNAT 01) ===================== */

            if ($data['motivo_codigo'] === '01') {
                $this->anularComprobanteOriginal($comprobanteOriginal, $data);
            }

            return $notaCredito->fresh(['detalles']);
        });
    }

    /**
     * Validar datos de entrada
     */
    private function validarDatos(array $data): void
    {
        if (empty($data['comprobante_referencia_id'])) {
            throw new Exception('Debe indicar el comprobante a afectar');
        }

        if (empty($data['motivo_codigo']) || empty($data['motivo_descripcion'])) {
            throw new Exception('Debe indicar el motivo SUNAT de la nota de crédito');
        }

        // Códigos SUNAT válidos
        $motivosValidos = [
            '01','02','03','04','05','06',
            '07','08','09','10','11','12','13'
        ];

        if (!in_array($data['motivo_codigo'], $motivosValidos)) {
            throw new Exception('Código de motivo SUNAT inválido');
        }
    }

    /**
     * Validar que el comprobante original esté emitido
     */
    private function validarComprobanteOriginal(string $comprobanteId): FacturacionComprobante
    {
        $comprobante = $this->repository->obtenerComprobanteConRelaciones($comprobanteId);

        if ($comprobante->estado !== 'emitida') {
            throw new Exception('Solo se puede emitir nota de crédito sobre comprobantes emitidos');
        }

        return $comprobante;
    }

    /**
     * Crear la nota de crédito
     */
    private function crearNotaCredito(array $serieData, FacturacionComprobante $original, array $data): FacturacionComprobante
    {
        return $this->repository->crearComprobante([
            'id'               => (string) Str::uuid(),
            'tipo_comprobante' => '07',
            'serie'            => $serieData['serie'],
            'correlativo'      => $serieData['correlativo'],

            // Se replica el cliente del comprobante original
            'cliente_id'       => $original->cliente_id,
            'ruc_cliente'      => $original->ruc_cliente,
            'razon_social'     => $original->razon_social,
            'direccion_fiscal' => $original->direccion_fiscal,

            'estado'            => 'emitida',
            'estado_comprobante'=> 'pendiente_envio',

            // Auditoría
            'motivo_anulacion'  => $data['motivo_descripcion'],
        ]);
    }

    /**
     * Procesar detalles y revertir inventario
     */
    private function procesarDetalles(FacturacionComprobante $notaCredito, $detalles): array
    {
        $subtotal = 0.00;
        $igv      = 0.00;
        $total    = 0.00;

        foreach ($detalles as $item) {

            $cantidad = (float) $item['cantidad'];
            $precio   = round((float) $item['precio_unitario'], 2);

            $lineSubtotal = round($cantidad * $precio, 2);
            $lineIgv      = round($lineSubtotal * 0.18, 2);
            $lineTotal    = round($lineSubtotal + $lineIgv, 2);

            $detalle = $this->repository->crearDetalle([
                'id'              => (string) Str::uuid(),
                'comprobante_id'  => $notaCredito->id,
                'producto_id'     => $item['producto_id'] ?? null,
                'descripcion'     => $item['descripcion'],
                'cantidad'        => $cantidad,
                'precio_unitario' => $precio,
                'subtotal'        => $lineSubtotal,
                'igv'             => $lineIgv,
                'total'           => $lineTotal,
            ]);

            $subtotal += $lineSubtotal;
            $igv      += $lineIgv;
            $total    += $lineTotal;

            /* ===================== KARDEX (DEVOLUCIÓN) ===================== */

            if (!empty($detalle->producto_id)) {
                $this->inventario->registrarMovimiento([
                    'item_id'               => $detalle->producto_id,
                    'tipo_movimiento'       => 'INGRESO',
                    'cantidad'              => $detalle->cantidad,
                    'costo_unitario'        => $detalle->precio_unitario,

                    'documento_tipo'        => '07',
                    'documento_serie'       => $notaCredito->serie,
                    'documento_correlativo' => str_pad(
                        $notaCredito->correlativo,
                        8,
                        '0',
                        STR_PAD_LEFT
                    ),

                    'referencia_tipo'       => 'NOTA_CREDITO',
                    'referencia_id'         => $notaCredito->id,
                    'nota'                  => 'Devolución por nota de crédito',
                ]);
            }
        }

        return [
            'subtotal' => round($subtotal, 2),
            'igv'      => round($igv, 2),
            'total'    => round($total, 2),
        ];
    }

    /**
     * Anular comprobante original (motivo SUNAT 01 - Anulación)
     */
    private function anularComprobanteOriginal(FacturacionComprobante $comprobante, array $data): void
    {
        // Anulación completa del comprobante original
        $this->repository->anularComprobante(
            $comprobante->id,
            'Anulado por Nota de Crédito: ' . $data['motivo_descripcion']
        );

        $this->repository->anularPagos($comprobante->id);
    }
}
