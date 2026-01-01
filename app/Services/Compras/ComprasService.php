<?php

namespace App\Services\Compras;

use App\Services\Inventario\InventarioService;
use App\Models\Inventario\InventarioItem;
use App\Repositories\Compras\ComprasRepository;
use Illuminate\Support\Facades\DB;

class ComprasService
{

    public function __construct(
        protected ComprasRepository $repository,
        protected InventarioService $inventarioService
    ) {}

    public function registrarCompra(array $data)
    {
        return DB::transaction(function () use ($data) {

            $subtotal = collect($data['detalles'])
                ->sum(fn ($d) => $d['cantidad'] * $d['precio_unitario']);

            $igv   = round($subtotal * 0.18, 2);
            $total = $subtotal + $igv;

            return $this->repository->crearCompra([
                'proveedor_id' => $data['proveedor_id'],
                'fecha_compra' => $data['fecha_compra'],
                'subtotal'     => $subtotal,
                'igv'           => $igv,
                'total'         => $total,
                'estado'        => 'finalizado',
                'usuario_id'    => auth()->id() ?? null,
            ], $data['detalles']);

            foreach ($data['detalles'] as $detalle) {

                $item = InventarioItem::find($detalle['item_id']);
            
                // 1️⃣ INGRESO A KARDEX (SIEMPRE)
                $this->inventarioService->registrarMovimiento([
                    'item_id' => $detalle['item_id'],
                    'tipo_movimiento' => 'INGRESO',
                    'cantidad' => $detalle['cantidad'],
                    'costo_unitario' => $detalle['precio_unitario'],
                    'referencia_id' => $compra->compra_id,
                    'usuario_id' => auth()->id() ?? null,
                ]);
            
                // 2️⃣ SI ES ACTIVO → REGISTRAR ACTIVO
                if ($item && $item->es_activo) {
                    $this->inventarioService->registrarActivoDesdeCompra([
                        'nombre_activo'   => $item->nombre,
                        'categoria'       => 'COMPRA',
                        'fecha_compra'    => $data['fecha_compra'],
                        'valor_compra'    => $detalle['cantidad'] * $detalle['precio_unitario'],
                        'proveedor_id'    => $data['proveedor_id'],
                    ]);
                }
            }
        });

    }

    public function listarCompras()
    {
        return $this->repository->listarCompras();
    }

    public function crearProveedor(array $data)
    {
        return $this->repository->crearProveedor($data);
    }

    public function listarProveedores()
    {
        return $this->repository->listarProveedores();
    }
}
