<?php

namespace App\Services\Inventario;

use App\Models\Inventario\InventarioItem;
use App\Models\Inventario\InventarioKardex;
use App\Models\Inventario\InventarioMovimiento;
use App\Models\Inventario\Activo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class InventarioService
{
    /* ======================================================
     |  ITEMS
     ====================================================== */

    public function listarItems()
    {
        return InventarioItem::orderBy('nombre')->get();
    }

    public function crearItem(array $data)
    {
        $data['item_id']        = (string) Str::uuid();
        $data['stock_actual']   = 0;
        $data['costo_promedio'] = 0;

        return InventarioItem::create($data);
    }

    public function actualizarItem(string $id, array $data)
    {
        $item = InventarioItem::findOrFail($id);
        $item->update($data);

        return $item;
    }

    /* ======================================================
     |  CATEGORÍAS
     ====================================================== */

    public function listarCategorias()
    {
        return DB::table('inventario_categorias')
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();
    }

    public function crearCategoria(array $data)
    {
        $data['categoria_id'] = (string) Str::uuid();
        $data['activo'] = 1;

        DB::table('inventario_categorias')->insert($data);

        return $data;
    }

    /* ======================================================
     |  UNIDADES
     ====================================================== */

    public function listarUnidades()
    {
        return DB::table('inventario_unidades')
            ->orderBy('nombre')
            ->get();
    }

    public function crearUnidad(array $data)
    {
        $data['unidad_id'] = (string) Str::uuid();

        DB::table('inventario_unidades')->insert($data);

        return $data;
    }

    /* ======================================================
     |  KARDEX (DETALLE)
     ====================================================== */

    public function obtenerKardexPorItem(string $itemId)
    {
        return InventarioKardex::where('item_id', $itemId)
            ->orderBy('fecha', 'asc')
            ->get();
    }

    /* ======================================================
     |  KARDEX RESUMEN (VISTA)
     ====================================================== */

    public function obtenerKardexResumen()
    {
        return DB::table('inventario_kardex_resumen')
            ->orderBy('nombre_item')
            ->get();
    }

    /* ======================================================
     |  REGISTRAR MOVIMIENTO (NÚCLEO)
     ====================================================== */

    public function registrarMovimiento(array $data): void
    {
        DB::transaction(function () use ($data) {

            /* 1. Item */
            $item = InventarioItem::lockForUpdate()
                ->findOrFail($data['item_id']);

            /* 2. Último kardex */
            $ultimo = InventarioKardex::where('item_id', $item->item_id)
                ->orderBy('fecha', 'desc')
                ->lockForUpdate()
                ->first();

            $saldoAnteriorCantidad = (float) ($ultimo->saldo_cantidad ?? 0);
            $costoPromedioAnterior = (float) ($ultimo->costo_promedio_resultante ?? 0);

            /* 3. Tipo movimiento */
            // Normalizar input para lógica de negocio
            $tipoInput = strtoupper($data['tipo_movimiento']);
            $esEntrada = in_array($tipoInput, ['ENTRADA', 'INGRESO', 'AJUSTE_ENTRADA']);
            
            // Determinar valor para BD (enum estricto: entrada|salida)
            $tipoDb = $esEntrada ? 'entrada' : 'salida';
            
            $cantidad  = abs((float) $data['cantidad']);
            
            // Validación de stock para salidas
            if (!$esEntrada && $cantidad > $saldoAnteriorCantidad) {
                throw new Exception('Stock insuficiente para realizar la salida. Saldo: ' . $saldoAnteriorCantidad);
            }

            /* 4. Cálculos */
            if ($esEntrada) {
                $nuevoSaldo = $saldoAnteriorCantidad + $cantidad;
                $costoUnitario = (float) ($data['costo_unitario'] ?? 0);

                $nuevoCostoPromedio = (
                    ($saldoAnteriorCantidad * $costoPromedioAnterior) +
                    ($cantidad * $costoUnitario)
                ) / max($nuevoSaldo, 1);

                $valorTotal = $cantidad * $costoUnitario;
            } else {
                $nuevoSaldo = $saldoAnteriorCantidad - $cantidad;
                $nuevoCostoPromedio = $costoPromedioAnterior;
                $valorTotal = $cantidad * $costoPromedioAnterior;
            }

            $saldoValorizado = $nuevoSaldo * $nuevoCostoPromedio;

            /* 5. Registrar KARDEX */
            $kardex = InventarioKardex::create([
                'kardex_id'                 => (string) Str::uuid(),
                'item_id'                   => $item->item_id,
                'tipo_movimiento'           => $tipoDb, // Usar valor mapeado para BD
                'origen'                    => $data['origen'] ?? null,

                'cantidad'                  => $cantidad,
                'saldo_cantidad'            => $nuevoSaldo,
                'saldo_valorizado'          => $saldoValorizado,

                'costo_unitario'            => $data['costo_unitario'] ?? null,
                'costo_promedio_resultante' => $nuevoCostoPromedio,
                'valor_total'               => $valorTotal,

                'documento_tipo'            => $data['documento_tipo'] ?? null,
                'documento_serie'           => $data['documento_serie'] ?? null,
                'documento_correlativo'     => $data['documento_correlativo'] ?? null,

                'referencia_tipo'           => $data['referencia_tipo'] ?? null,
                'referencia_id'             => $data['referencia_id'] ?? null,

                'usuario_id'                => $data['usuario_id'] ?? null,
                'nota'                      => $data['nota'] ?? null,

                'metodo_valorizacion'       => 'PROMEDIO',
                'fecha'                     => now(),
            ]);

            /* 6. Auditoría */
            InventarioMovimiento::create([
                'movimiento_id'    => (string) Str::uuid(),
                'item_id'          => $item->item_id,
                'tipo_movimiento'  => $data['tipo_movimiento'],
                'cantidad'         => $cantidad,
                'referencia'       => $data['referencia'] ?? null,
                'usuario_id'       => $data['usuario_id'] ?? null,
                'fecha_movimiento' => now(),

                'entidad_tipo'     => 'INVENTARIO_KARDEX',
                'entidad_id'       => $kardex->kardex_id,

                'datos_anteriores' => [
                    'saldo_cantidad' => $saldoAnteriorCantidad,
                    'costo_promedio' => $costoPromedioAnterior,
                ],
                'datos_nuevos' => [
                    'saldo_cantidad' => $nuevoSaldo,
                    'costo_promedio' => $nuevoCostoPromedio,
                ],
            ]);

            /* 7. Actualizar ITEM */
            $item->update([
                'stock_actual'   => $nuevoSaldo,
                'costo_promedio' => $nuevoCostoPromedio,
            ]);

            /* 8. SI ES ACTIVO → registrar activo fijo */
            if (
                $item->es_activo === true &&
                ($data['origen'] ?? null) === 'COMPRA'
            ) {
                $this->registrarActivoDesdeCompra($item, $data, $kardex);
            }
        });
    }

    /* ======================================================
     |  REGISTRAR ACTIVO FIJO DESDE COMPRA (SUNAT)
     ====================================================== */

    protected function registrarActivoDesdeCompra(
        InventarioItem $item,
        array $data,
        InventarioKardex $kardex
    ): void {
        Activo::create([
            'activo_id'        => (string) Str::uuid(),
            'nombre_activo'    => $item->nombre,
            'categoria'        => $data['categoria_activo'] ?? 'EQUIPOS',
            'estado_activo'    => 'ACTIVO',
            'fecha_compra'     => now(),
            'valor_compra'     => $data['costo_unitario'] ?? 0,
            'proveedor_id'     => $data['proveedor_id'] ?? null,
            'vida_util_meses'  => $data['vida_util_meses'] ?? 60,
            'creado_en'        => now(),
        ]);
    }
}
