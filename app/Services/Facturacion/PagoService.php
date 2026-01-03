<?php

namespace App\Services\Facturacion;

use App\Models\Facturacion\FacturacionPago;
use App\Repositories\Facturacion\FacturacionRepository;
use Illuminate\Support\Str;
use Exception;

/**
 * Servicio especializado para registro y gestión de pagos
 */
class PagoService
{
    public function __construct(
        protected FacturacionRepository $repository
    ) {}

    /**
     * Registrar un pago
     * 
     * @param array $data Datos del pago
     * @return FacturacionPago
     * @throws Exception
     */
    public function registrar(array $data): FacturacionPago
    {
        // Validar que el comprobante existe y no está anulado
        $this->validarComprobante($data['comprobante_id']);

        // Crear el pago
        $pago = $this->repository->crearPago([
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

        // Actualizar estado del comprobante según saldo
        $this->actualizarEstadoComprobante($data['comprobante_id']);

        return $pago;
    }

    /**
     * Validar que el comprobante esté emitido y no anulado
     */
    private function validarComprobante(string $comprobanteId): void
    {
        $comprobante = $this->repository->obtenerComprobanteConRelaciones($comprobanteId);

        if ($comprobante->estado === 'anulada') {
            throw new Exception('No se puede registrar pago en un comprobante anulado');
        }

        if ($comprobante->estado !== 'emitida') {
            throw new Exception('El comprobante debe estar emitido para registrar pagos');
        }
    }

    /**
     * Calcular el saldo pendiente de un comprobante
     */
    public function calcularSaldo(string $comprobanteId): float
    {
        $comprobante = $this->repository->obtenerComprobanteConRelaciones($comprobanteId);

        $totalPagado = $comprobante->pagos
            ->where('estado_pago', 'pagado')
            ->sum('monto');

        $saldo = $comprobante->total - $totalPagado;

        return round($saldo, 2);
    }

    /**
     * Actualizar estado del comprobante según pagos
     */
    private function actualizarEstadoComprobante(string $comprobanteId): void
    {
        $saldo = $this->calcularSaldo($comprobanteId);

        // Si el saldo es 0 o negativo, el comprobante está pagado completamente
        // (esto podría manejarse con un estado específico si lo necesitas)
        // Por ahora solo calculamos el saldo
    }

    /**
     * Obtener historial de pagos de un comprobante
     */
    public function obtenerHistorialPagos(string $comprobanteId): array
    {
        $comprobante = $this->repository->obtenerComprobanteConRelaciones($comprobanteId);

        return [
            'comprobante_id' => $comprobante->id,
            'total'          => $comprobante->total,
            'pagado'         => $comprobante->pagos->where('estado_pago', 'pagado')->sum('monto'),
            'saldo'          => $this->calcularSaldo($comprobanteId),
            'pagos'          => $comprobante->pagos->map(function ($pago) {
                return [
                    'id'                 => $pago->id,
                    'fecha_pago'         => $pago->fecha_pago,
                    'medio_pago'         => $pago->medio_pago,
                    'monto'              => $pago->monto,
                    'recibo'             => $pago->recibo,
                    'referencia_externa' => $pago->referencia_externa,
                    'estado_pago'        => $pago->estado_pago,
                ];
            }),
        ];
    }
}
