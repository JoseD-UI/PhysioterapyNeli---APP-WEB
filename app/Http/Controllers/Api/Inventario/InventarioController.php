<?php

namespace App\Http\Controllers\Api\Inventario;

use App\Http\Controllers\Controller;
use App\Services\Inventario\InventarioService;

use App\Http\Requests\Inventario\ItemStoreRequest;
use App\Http\Requests\Inventario\ItemUpdateRequest;
use App\Http\Requests\Inventario\CategoriaRequest;
use App\Http\Requests\Inventario\UnidadRequest;
use App\Http\Requests\Inventario\MovimientoStoreRequest;

use App\Http\Resources\Inventario\ItemResource;
use App\Http\Resources\Inventario\CategoriaResource;
use App\Http\Resources\Inventario\UnidadResource;
use App\Http\Resources\Inventario\KardexResource;
use App\Http\Resources\Inventario\KardexResumenResource;
use App\Http\Resources\Inventario\MovimientoResource;

use Illuminate\Http\JsonResponse;

class InventarioController extends Controller
{
    public function __construct(
        protected InventarioService $service
    ) {}

    /* ===================== ITEMS ===================== */

    public function itemsIndex(): JsonResponse
    {
        return response()->json(
            ItemResource::collection($this->service->listarItems())
        );
    }

    public function itemsStore(ItemStoreRequest $request): JsonResponse
    {
        return response()->json(
            new ItemResource($this->service->crearItem($request->validated())),
            201
        );
    }

    public function itemsUpdate(ItemUpdateRequest $request, string $id): JsonResponse
    {
        return response()->json(
            new ItemResource($this->service->actualizarItem($id, $request->validated()))
        );
    }

    /* ===================== CATEGORÍAS ===================== */

    public function categoriasIndex(): JsonResponse
    {
        return response()->json(
            CategoriaResource::collection($this->service->listarCategorias())
        );
    }

    public function categoriasStore(CategoriaRequest $request): JsonResponse
    {
        return response()->json(
            new CategoriaResource($this->service->crearCategoria($request->validated())),
            201
        );
    }

    /* ===================== UNIDADES ===================== */

    public function unidadesIndex(): JsonResponse
    {
        return response()->json(
            UnidadResource::collection($this->service->listarUnidades())
        );
    }

    public function unidadesStore(UnidadRequest $request): JsonResponse
    {
        return response()->json(
            new UnidadResource($this->service->crearUnidad($request->validated())),
            201
        );
    }

    /* ===================== KARDEX ===================== */

    public function kardexPorItem(string $itemId): JsonResponse
    {
        return response()->json(
            KardexResource::collection($this->service->obtenerKardexPorItem($itemId))
        );
    }

    public function kardexResumen(): JsonResponse
    {
        return response()->json(
            KardexResumenResource::collection($this->service->obtenerKardexResumen())
        );
    }

    /* ===================== MOVIMIENTOS ===================== */

    public function registrarMovimiento(MovimientoStoreRequest $request): JsonResponse
    {
        $this->service->registrarMovimiento($request->validated());

        return response()->json([
            'message' => 'Movimiento de inventario registrado correctamente'
        ], 201);
    }
}
