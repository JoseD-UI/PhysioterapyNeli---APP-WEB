<?php

namespace App\Http\Controllers\Api\Compras;

use App\Http\Controllers\Controller;
use App\Services\Compras\ComprasService;
use App\Http\Requests\Compras\CompraStoreRequest;
use App\Http\Requests\Compras\ProveedorRequest;
use App\Http\Resources\Compras\CompraResource;
use App\Http\Resources\Compras\ProveedorResource;
use Illuminate\Http\JsonResponse;

class ComprasController extends Controller
{
    public function __construct(
        protected ComprasService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(
            CompraResource::collection(
                $this->service->listarCompras()
            )
        );
    }

    public function store(CompraStoreRequest $request): JsonResponse
    {
        return response()->json(
            new CompraResource(
                $this->service->registrarCompra($request->validated())
            ),
            201
        );
    }

    public function proveedoresIndex(): JsonResponse
    {
        return response()->json(
            ProveedorResource::collection(
                $this->service->listarProveedores()
            )
        );
    }

    public function proveedoresStore(ProveedorRequest $request): JsonResponse
    {
        return response()->json(
            new ProveedorResource(
                $this->service->crearProveedor($request->validated())
            ),
            201
        );
    }
}
