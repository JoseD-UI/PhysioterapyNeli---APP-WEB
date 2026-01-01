<?php

namespace App\Http\Controllers\Api\Inventario;

use App\Http\Controllers\Controller;
use App\Services\Inventario\ActivoService;
use App\Http\Requests\Inventario\ActivoStoreRequest;
use App\Http\Resources\Inventario\ActivoResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivoController extends Controller
{
    public function __construct(
        protected ActivoService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(
            ActivoResource::collection($this->service->listar())
        );
    }

    public function store(ActivoStoreRequest $request): JsonResponse
    {
        return response()->json(
            new ActivoResource($this->service->crear($request->validated())),
            201
        );
    }

    public function cambiarEstado(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'estado_activo' => 'required|in:ACTIVO,MANTENIMIENTO,BAJA'
        ]);

        return response()->json(
            new ActivoResource(
                $this->service->cambiarEstado($id, $request->estado_activo)
            )
        );
    }
}
