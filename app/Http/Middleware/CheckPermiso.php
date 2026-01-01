<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\Seguridad\PermisoService;
use App\Models\Principal\Usuario;


class CheckPermiso
{
    public function __construct(
        protected PermisoService $service
    ) {}

    public function handle(Request $request, Closure $next, string $permiso)
    {
        /** @var Usuario|null $usuario */
        $usuario = Auth::user();

        if (!$usuario) {
            return response()->json([
                'message' => 'No autenticado'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ((int) $usuario->activo !== 1) {
            return response()->json([
                'message' => 'Usuario inactivo'
            ], Response::HTTP_FORBIDDEN);
        }

        // 🔹 aquí NO hay error, sigue siendo correcto
        if (!$this->service->validar($usuario, $permiso)) {
            return response()->json([
                'message' => 'No tiene permisos suficientes',
                'permiso_requerido' => $permiso
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
