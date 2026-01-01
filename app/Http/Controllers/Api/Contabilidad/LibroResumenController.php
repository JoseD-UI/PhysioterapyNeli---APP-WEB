<?php

namespace App\Http\Controllers\Api\Contabilidad;

use App\Http\Controllers\Controller;
use App\Services\Contabilidad\LibroContableService;
use App\Http\Requests\Contabilidad\GenerarLibroResumenRequest;
use App\Http\Resources\Contabilidad\LibroResumenResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LibroResumenController extends Controller
{
    public function __construct(
        protected LibroContableService $service
    ) {}

    /**
     * Listar libros resumen
     */
    public function index(Request $request): JsonResponse
    {
        $filtros = $request->only(['tipo_libro', 'periodo']);
        
        $libros = $this->service->listar($filtros);

        return response()->json(
            LibroResumenResource::collection($libros)
        );
    }

    /**
     * Generar libro resumen para un periodo
     */
    public function generar(GenerarLibroResumenRequest $request): JsonResponse
    {
        $libro = $this->service->generar(
            $request->tipo_libro,
            $request->periodo
        );

        return response()->json(
            new LibroResumenResource($libro),
            201
        );
    }

    /**
     * Mostrar un libro resumen específico
     */
    public function show(string $id): JsonResponse
    {
        $libro = $this->service->consultar(
            $id,
            request('periodo')
        );

        if (!$libro) {
            return response()->json([
                'message' => 'Libro resumen no encontrado'
            ], 404);
        }

        return response()->json(
            new LibroResumenResource($libro)
        );
    }
}
