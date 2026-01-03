<?php

namespace App\Services\Seguridad;

use App\Models\Seguridad\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    public function registrar(array $data): void
    {
        AuditLog::create([
            'esquema'        => $data['esquema'] ?? 'public',
            'tabla_nombre'   => $data['tabla_nombre'],
            'operacion'      => $data['operacion'],
            'registro_id'    => $data['registro_id'] ?? null,
            'usuario_id'     => $data['usuario_id']
                                ?? Auth::user()?->usuario_id,
            'fecha'          => now(),
            'datos_previos'  => $data['datos_previos'] ?? null,
            'datos_nuevos'   => $data['datos_nuevos'] ?? null,
            'descripcion'    => $data['descripcion'] ?? null,
        ]);
    }
}
