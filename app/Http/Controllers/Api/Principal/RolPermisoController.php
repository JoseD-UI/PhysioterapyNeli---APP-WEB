<?php

namespace App\Http\Controllers\Api\Principal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Seguridad\PermisoService;

class RolPermisoController extends Controller
{
    public function asignar(Request $request, PermisoService $service)
    {
        $data = $request->validate([
            'rol_id'     => 'required|integer|exists:principal_roles,rol_id',
            'permiso_id' => 'required|uuid|exists:principal_permisos,permiso_id',
        ]);

        DB::table('principal_rol_permiso')->updateOrInsert(
            [
                'rol_id' => $data['rol_id'],
                'permiso_id' => $data['permiso_id'],
            ],
            [
                'asignado_en' => now(),
            ]
        );

        $service->limpiarCacheRol($data['rol_id']);

        return response()->json([
            'message' => 'Permiso asignado correctamente'
        ], 201);
    }

    public function revocar(Request $request, PermisoService $service)
    {
        $data = $request->validate([
            'rol_id'     => 'required|integer',
            'permiso_id' => 'required|uuid',
        ]);

        DB::table('principal_rol_permiso')
            ->where($data)
            ->delete();

        $service->limpiarCacheRol($data['rol_id']);

        return response()->json([
            'message' => 'Permiso revocado correctamente'
        ]);
    }
}
