<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\Seguridad\PermisoService;


class CheckPermiso
{
    public function __construct(
        protected PermisoService $service
    ) {}

    public function handle(Request $request, Closure $next, string $permiso)
    {
        // Obtener usuario autenticado (modelo User)
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'No autenticado'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Obtener usuario principal del negocio
        $usuarioPrincipal = $user->usuarioPrincipal;

        if (!$usuarioPrincipal) {
            return response()->json([
                'message' => 'Usuario sin perfil asignado. Contacte al administrador.',
                'codigo' => 'USUARIO_SIN_PERFIL'
            ], Response::HTTP_FORBIDDEN);
        }

        if ((int) $usuarioPrincipal->activo !== 1) {
            return response()->json([
                'message' => 'Usuario inactivo',
                'codigo' => 'USUARIO_INACTIVO'
            ], Response::HTTP_FORBIDDEN);
        }

        // Validar permiso usando el servicio
        if (!$this->service->validar($usuarioPrincipal, $permiso)) {
            return response()->json([
                'message' => 'No tiene permisos suficientes',
                'permiso_requerido' => $permiso,
                'codigo' => 'PERMISO_DENEGADO'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
