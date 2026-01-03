<?php

namespace App\Services\Facturacion;

use App\Models\Facturacion\FacturacionComprobante;
use App\Repositories\Facturacion\FacturacionRepository;
use App\Services\Inventario\InventarioService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

/**
 * Servicio especializado para emisión y gestión de comprobantes de pago
 * Tipos: 01 (Factura), 03 (Boleta), 12 (Ticket)
 */
class ComprobanteService
{
    public function __construct(
        protected FacturacionRepository $repository,
        protected InventarioService $inventario
    ) {}

    /**
     * Emitir comprobante de pago (Factura, Boleta o Ticket)
     * 
     * @param array $data Datos del comprobante
     * @return FacturacionComprobante
     * @throws Exception
     */
    public function emitir(array $data): FacturacionComprobante
    {
        return DB::transaction(function () use ($data) {

            /* ===================== 1. VALIDACIÓN DE DOMINIO ===================== */

            $this->validarTipoComprobante($data['tipo_comprobante']);
            $this->validarRequerimientosFiscales($data);

            /* ===================== 2. SERIE + CORRELATIVO ===================== */

            $serieData = $this->repository
                ->obtenerSerieYCorrelativo($data['tipo_comprobante']);

            /* ===================== 3. CREAR COMPROBANTE ===================== */

            $comprobante = $this->repository->crearComprobante([
                'id'               => (string) Str::uuid(),
                'tipo_comprobante' => $data['tipo_comprobante'],
                'serie'            => $serieData['serie'],
                'correlativo'      => $serieData['correlativo'],

                'cliente_id'       => $data['cliente_id'] ?? null,
                'ruc_cliente'      => $data['ruc_cliente'] ?? null,
                'razon_social'     => $data['razon_social'] ?? null,
                'direccion_fiscal' => $data['direccion_fiscal'] ?? null,

                'estado'           => 'emitida',
                'estado_comprobante'=> 'pendiente_envio', // SUNAT
            ]);

            /* ===================== 4. DETALLES + KARDEX ===================== */

            $totales = $this->procesarDetalles($comprobante, $data['detalles']);

            /* ===================== 5. ACTUALIZAR TOTALES ===================== */

            $this->repository->actualizarTotales(
                $comprobante->id,
                $totales['subtotal'],
                $totales['igv'],
                $totales['total']
            );

            /* ===================== 6. RETORNO CON RELACIONES ===================== */

            return $comprobante->fresh(['detalles', 'pagos']);
        });
    }

    /**
     * Anular comprobante y revertir inventario
     * 
     * @param string $comprobanteId ID del comprobante
     * @param string $motivo Motivo de anulación
     * @return void
     * @throws Exception
     */
    public function anular(string $comprobanteId, string $motivo): void
    {
        DB::transaction(function () use ($comprobanteId, $motivo) {

            $comprobante = $this->repository
                ->obtenerComprobanteConRelaciones($comprobanteId);

            if ($comprobante->estado !== 'emitida') {
                throw new Exception('El comprobante no puede anularse');
            }

            /* ---------- REVERTIR KARDEX ---------- */
            $this->revertirInventario($comprobante);

            /* ---------- ANULAR PAGOS ---------- */
            $this->repository->anularPagos($comprobante->id);

            /* ---------- ANULAR COMPROBANTE ---------- */
            $this->repository->anularComprobante(
                $comprobante->id,
                $motivo
            );
        });
    }

    /**
     * Validar tipo de comprobante
     */
    private function validarTipoComprobante(string $tipo): void
    {
        if (!in_array($tipo, ['01', '03', '12'])) {
            throw new Exception('Tipo de comprobante inválido para este servicio. Use: 01 (Factura), 03 (Boleta), 12 (Ticket)');
        }
    }

    /**
     * Validar requerimientos fiscales según tipo
     */
    private function validarRequerimientosFiscales(array $data): void
    {
        // Factura requiere RUC
        if (
            $data['tipo_comprobante'] === '01' &&
            empty($data['ruc_cliente'])
        ) {
            throw new Exception('Factura requiere RUC del cliente');
        }
    }

    /**
     * Procesar detalles y registrar movimientos de inventario
     */
    private function procesarDetalles(FacturacionComprobante $comprobante, array $detalles): array
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
                'comprobante_id'  => $comprobante->id,
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

            /* ---------- KARDEX SOLO PARA PRODUCTOS ---------- */
            if (!empty($detalle->producto_id)) {
                $this->inventario->registrarMovimiento([
                    'item_id'               => $detalle->producto_id,
                    'tipo_movimiento'       => 'SALIDA',
                    'cantidad'              => $detalle->cantidad,
                    'costo_unitario'        => $detalle->precio_unitario,

                    'documento_tipo'        => $comprobante->tipo_comprobante,
                    'documento_serie'       => $comprobante->serie,
                    'documento_correlativo' => str_pad(
                        $comprobante->correlativo,
                        8,
                        '0',
                        STR_PAD_LEFT
                    ),

                    'referencia_tipo'       => 'COMPROBANTE',
                    'referencia_id'         => $comprobante->id,
                    'nota'                  => 'Salida por venta',
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
     * Revertir inventario de comprobante anulado
     */
    private function revertirInventario(FacturacionComprobante $comprobante): void
    {
        foreach ($comprobante->detalles as $detalle) {
            if (!$detalle->producto_id) {
                continue;
            }

            $this->inventario->registrarMovimiento([
                'item_id'         => $detalle->producto_id,
                'tipo_movimiento' => 'INGRESO',
                'cantidad'        => $detalle->cantidad,
                'costo_unitario'  => $detalle->precio_unitario,
                'referencia_tipo' => 'ANULACION_COMPROBANTE',
                'referencia_id'   => $comprobante->id,
                'nota'            => 'Reversión por anulación',
            ]);
        }
    }
}
