<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\Seguridad\AuditLogService;

class AuditActionMiddleware
{
public function handle($request, Closure $next)
{
    $response = $next($request);

    if (in_array($request->method(), ['POST','PUT','DELETE'])) {
        app(AuditLogService::class)->registrar([
            'tabla_nombre' => 'API',
            'operacion'    => $request->method(),
            'descripcion'  => $request->path(),
        ]);
    }

    return $response;
}
}