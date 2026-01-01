<?php

namespace App\Repositories\Compras;

use App\Models\Compras\Compra;
use App\Models\Compras\DetalleCompra;
use App\Models\Compras\Proveedor;

class ComprasRepository
{
    public function crearProveedor(array $data)
    {
        return Proveedor::create($data);
    }

    public function listarProveedores()
    {
        return Proveedor::orderBy('nombre')->get();
    }

    public function crearCompra(array $cabecera, array $detalles)
    {
        $compra = Compra::create($cabecera);

        foreach ($detalles as $detalle) {
            DetalleCompra::create([
                'compra_id' => $compra->compra_id,
                'item_id'   => $detalle['item_id'],
                'cantidad'  => $detalle['cantidad'],
                'precio_unitario' => $detalle['precio_unitario'],
                'subtotal'  => $detalle['cantidad'] * $detalle['precio_unitario'],
            ]);
        }

        return $compra->load('detalles');
    }

    public function listarCompras()
    {
        return Compra::with('detalles', 'proveedor')
            ->orderBy('fecha_compra', 'desc')
            ->get();
    }
}
