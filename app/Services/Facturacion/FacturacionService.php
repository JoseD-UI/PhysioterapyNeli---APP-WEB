<?php

namespace App\Services\Facturacion;

use App\Models\Facturacion\FacturacionComprobante;
use App\Repositories\Facturacion\FacturacionRepository;
use App\Services\Inventario\InventarioService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class FacturacionService
{
    public function __construct(
        protected FacturacionRepository $repository,
        protected InventarioService $inventario
    ) {}

    /* =====================================================
     | EMITIR COMPROBANTE (01,03,12)
     ===================================================== */
    public function emitirComprobante(array $data): FacturacionComprobante
    {
        return DB::transaction(function () use ($data) {

            /* ===================== 1. VALIDACIÓN DE DOMINIO ===================== */

            if (!in_array($data['tipo_comprobante'], ['01', '03', '12', '07'])) {
                throw new Exception('Tipo de comprobante SUNAT inválido');
            }

            if (
                $data['tipo_comprobante'] === '01' &&
                empty($data['ruc_cliente'])
            ) {
                throw new Exception('Factura requiere RUC del cliente');
            }

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

            $subtotal = 0.00;
            $igv      = 0.00;
            $total    = 0.00;

            foreach ($data['detalles'] as $item) {

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

            /* ===================== 5. ACTUALIZAR TOTALES ===================== */

            $this->repository->actualizarTotales(
                $comprobante->id,
                round($subtotal, 2),
                round($igv, 2),
                round($total, 2)
            );

            /* ===================== 6. RETORNO CONSISTENTE ===================== */

            return $comprobante->fresh(['detalles', 'pagos']);
        });
    }

    /* =====================================================
     | REGISTRAR PAGO
     ===================================================== */
    public function registrarPago(array $data)
    {
        return $this->repository->crearPago([
            'id'                => (string) Str::uuid(),
            'comprobante_id'    => $data['comprobante_id'],
            'paciente_id'       => $data['paciente_id'] ?? null,
            'sesion_id'         => $data['sesion_id'] ?? null,
            'medio_pago'        => $data['medio_pago'],
            'monto'             => round((float) $data['monto'], 2),
            'recibo'            => $data['recibo'] ?? null,
            'referencia_externa'=> $data['referencia_externa'] ?? null,
            'estado_pago'       => 'pagado',
        ]);
    }

    /* =====================================================
     | ANULAR COMPROBANTE
     ===================================================== */
    public function anularComprobante(string $comprobanteId, string $motivo): void
    {
        DB::transaction(function () use ($comprobanteId, $motivo) {

            $comprobante = $this->repository
                ->obtenerComprobanteConRelaciones($comprobanteId);

            if ($comprobante->estado !== 'emitida') {
                throw new Exception('El comprobante no puede anularse');
            }

            /* ---------- REVERTIR KARDEX ---------- */
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

            /* ---------- ANULAR PAGOS ---------- */
            $this->repository->anularPagos($comprobante->id);

            /* ---------- ANULAR COMPROBANTE ---------- */
            $this->repository->anularComprobante(
                $comprobante->id,
                $motivo
            );
        });
    }

    /* =====================================================
    | NOTA DE CRÉDITO (07)
    ===================================================== */
    public function emitirNotaCredito(array $data): FacturacionComprobante
    {
        return DB::transaction(function () use ($data) {

            /* ===================== 1. VALIDACIONES SUNAT ===================== */

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

            /* ===================== 2. COMPROBANTE ORIGINAL ===================== */

            $comprobanteOriginal = $this->repository
                ->obtenerComprobanteConRelaciones(
                    $data['comprobante_referencia_id']
                );

            if ($comprobanteOriginal->estado !== 'emitida') {
                throw new Exception('Solo se puede emitir nota de crédito sobre comprobantes emitidos');
            }

            /* ===================== 3. SERIE + CORRELATIVO (07) ===================== */

            $serieData = $this->repository
                ->obtenerSerieYCorrelativo('07');

            /* ===================== 4. CREAR NOTA DE CRÉDITO ===================== */

            $notaCredito = $this->repository->crearComprobante([
                'id'               => (string) Str::uuid(),
                'tipo_comprobante' => '07',
                'serie'            => $serieData['serie'],
                'correlativo'      => $serieData['correlativo'],

                // Se replica el cliente del comprobante original
                'cliente_id'       => $comprobanteOriginal->cliente_id,
                'ruc_cliente'      => $comprobanteOriginal->ruc_cliente,
                'razon_social'     => $comprobanteOriginal->razon_social,
                'direccion_fiscal' => $comprobanteOriginal->direccion_fiscal,

                'estado'            => 'emitida',
                'estado_comprobante'=> 'pendiente_envio',

                // Auditoría
                'motivo_anulacion'  => $data['motivo_descripcion'],
            ]);

            /* ===================== 5. DETALLES (PARCIAL O TOTAL) ===================== */

            $subtotal = 0.00;
            $igv      = 0.00;
            $total    = 0.00;

            // Si no se envían detalles → NC TOTAL
            $detalles = $data['detalles'] ?? $comprobanteOriginal->detalles;

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

                /* ===================== 6. KARDEX (DEVOLUCIÓN) ===================== */

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

            /* ===================== 7. TOTALES ===================== */

            $this->repository->actualizarTotales(
                $notaCredito->id,
                round($subtotal, 2),
                round($igv, 2),
                round($total, 2)
            );

            /* ===================== 8. ANULACIÓN TOTAL (SUNAT 01) ===================== */

            if ($data['motivo_codigo'] === '01') {
                // Anulación completa del comprobante original
                $this->repository->anularComprobante(
                    $comprobanteOriginal->id,
                    'Anulado por Nota de Crédito: ' . $data['motivo_descripcion']
                );

                $this->repository->anularPagos(
                    $comprobanteOriginal->id
                );
            }

            return $notaCredito->fresh(['detalles']);
        });
    }

}
