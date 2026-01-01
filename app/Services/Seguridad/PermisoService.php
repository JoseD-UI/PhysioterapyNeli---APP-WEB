<?php

namespace App\Services\Seguridad;

use App\Models\Principal\Usuario;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PermisoService
{
    /**
     * Validar si un usuario tiene un permiso específico
     *
     * @param Usuario $usuario
     * @param string  $permisoCodigo Ej: facturacion.comprobantes.emitir
     */
    public function validar(Usuario $usuario, string $permisoCodigo): bool
    {
        // 1️⃣ Usuario activo
        if ((int) $usuario->activo !== 1) {
            return false;
        }

        // 2️⃣ Debe tener rol
        if (!$usuario->rol) {
            return false;
        }

        // 3️⃣ ADMINISTRADOR = acceso total
        if ($this->esAdministrador($usuario)) {
            return true;
        }

        // 4️⃣ Obtener permisos (cacheados)
        $permisos = $this->obtenerPermisosDelRol(
            (int) $usuario->rol->rol_id
        );

        // 5️⃣ Permiso exacto
        if (in_array($permisoCodigo, $permisos, true)) {
            return true;
        }

        // 6️⃣ Permisos comodín progresivos
        // Ej: facturacion.comprobantes.emitir
        // prueba:
        // - facturacion.comprobantes.*
        // - facturacion.*
        $partes = explode('.', $permisoCodigo);

        while (count($partes) > 1) {
            array_pop($partes);
            $comodin = implode('.', $partes) . '.*';

            if (in_array($comodin, $permisos, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica si el usuario es administrador
     */
    protected function esAdministrador(Usuario $usuario): bool
    {
        return strtoupper(trim($usuario->rol->nombre)) === 'ADMINISTRADOR';
    }

    /**
     * Obtener permisos del rol (con cache)
     */
    protected function obtenerPermisosDelRol(int $rolId): array
    {
        $cacheKey = "seguridad.rol_permisos.{$rolId}";

        return Cache::remember(
            $cacheKey,
            now()->addMinutes(60),
            function () use ($rolId) {

                return DB::table('principal_rol_permiso as rp')
                    ->join(
                        'principal_permisos as p',
                        'rp.permiso_id',
                        '=',
                        'p.permiso_id'
                    )
                    ->where('rp.rol_id', $rolId)
                    ->pluck('p.codigo')
                    ->values()
                    ->toArray();
            }
        );
    }

    /**
     * Limpiar cache de permisos del rol
     * (usar cuando se asignan / quitan permisos)
     */
    public function limpiarCacheRol(int $rolId): void
    {
        Cache::forget("seguridad.rol_permisos.{$rolId}");
    }
}
