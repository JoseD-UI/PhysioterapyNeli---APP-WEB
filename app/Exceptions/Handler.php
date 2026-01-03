<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Solo aplicar formato JSON para rutas API
        if ($request->is('api/*')) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Manejo centralizado de excepciones para API
     */
    protected function handleApiException($request, Throwable $e)
    {
        // Excepción de validación
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors(),
                'codigo' => 'VALIDATION_ERROR'
            ], 422);
        }

        // Excepción de autenticación
        if ($e instanceof AuthenticationException) {
            return response()->json([
                'message' => 'No autenticado',
                'codigo' => 'UNAUTHENTICATED'
            ], 401);
        }

        // Modelo no encontrado
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Recurso no encontrado',
                'codigo' => 'RESOURCE_NOT_FOUND'
            ], 404);
        }

        // Ruta no encontrada
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Endpoint no encontrado',
                'codigo' => 'ENDPOINT_NOT_FOUND'
            ], 404);
        }

        // HTTP Exception general
        if ($e instanceof HttpException) {
            return response()->json([
                'message' => $e->getMessage() ?: 'Error en la solicitud',
                'codigo' => 'HTTP_ERROR'
            ], $e->getStatusCode());
        }

        // Error de base de datos
        if ($e instanceof \Illuminate\Database\QueryException) {
            // No exponer detalles de base de datos en producción
            $message = config('app.debug') 
                ? $e->getMessage() 
                : 'Error al procesar la solicitud en base de datos';

            return response()->json([
                'message' => $message,
                'codigo' => 'DATABASE_ERROR'
            ], 500);
        }

        // Error genérico
        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
        $message = config('app.debug') 
            ? $e->getMessage() 
            : 'Error interno del servidor';

        return response()->json([
            'message' => $message,
            'codigo' => 'SERVER_ERROR'
        ], $statusCode);
    }
}
