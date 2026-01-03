<?php

/*
|--------------------------------------------------------------------------
| API Routes - Fisioterapia API
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Principal\PrincipalController;
use App\Http\Controllers\Api\Clinico\ClinicoController;
use App\Http\Controllers\Api\Agenda\AgendaController;
use App\Http\Controllers\Api\Compras\ComprasController;
use App\Http\Controllers\Api\Inventario\InventarioController;
use App\Http\Controllers\Api\Inventario\ActivoController;
use App\Http\Controllers\Api\Facturacion\FacturacionController;
use App\Http\Controllers\Api\Facturacion\Sunat\SunatController;
use App\Http\Controllers\Api\Contabilidad\LibroResumenController;
use App\Http\Controllers\Api\Reportes\ReportesController;
use App\Http\Controllers\Api\Principal\RolPermisoController;
use App\Http\Controllers\Api\Seguridad\AuditController;



/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS - Autenticación
|--------------------------------------------------------------------------
*/
Route::prefix('v1/auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS - Requieren autenticación
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // Auth (usuario autenticado)
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me', [AuthController::class, 'me']);


    /*
    |--------------------------------------------------------------------------
    | PRINCIPAL - Personas, Usuarios, Salas, Permisos
    |--------------------------------------------------------------------------
    */
    Route::prefix('principal')->group(function () {

        // Personas
        Route::get('personas', [PrincipalController::class, 'personasIndex'])
            ->middleware('permiso:principal.personas.ver');

        Route::post('personas', [PrincipalController::class, 'personasStore'])
            ->middleware('permiso:principal.personas.crear');

        Route::get('personas/{id}', [PrincipalController::class, 'personasShow'])
            ->middleware('permiso:principal.personas.ver');

        Route::put('personas/{id}', [PrincipalController::class, 'personasUpdate'])
            ->middleware('permiso:principal.personas.editar');

        Route::delete('personas/{id}', [PrincipalController::class, 'personasDelete'])
            ->middleware('permiso:principal.personas.eliminar');

        // Usuarios
        Route::get('usuarios', [PrincipalController::class, 'usuariosIndex'])
            ->middleware('permiso:principal.usuarios.ver');

        Route::post('usuarios', [PrincipalController::class, 'usuariosStore'])
            ->middleware('permiso:principal.usuarios.crear');

        Route::get('usuarios/{id}', [PrincipalController::class, 'usuariosShow'])
            ->middleware('permiso:principal.usuarios.ver');

        Route::put('usuarios/{id}', [PrincipalController::class, 'usuariosUpdate'])
            ->middleware('permiso:principal.usuarios.editar');

        // Salas
        Route::get('salas', [PrincipalController::class, 'salasIndex'])
            ->middleware('permiso:principal.salas.ver');

        Route::post('salas', [PrincipalController::class, 'salasStore'])
            ->middleware('permiso:principal.salas.crear');

        Route::get('salas/{id}', [PrincipalController::class, 'salasShow'])
            ->middleware('permiso:principal.salas.ver');

        Route::put('salas/{id}', [PrincipalController::class, 'salasUpdate'])
            ->middleware('permiso:principal.salas.editar');

        Route::delete('salas/{id}', [PrincipalController::class, 'salasDelete'])
            ->middleware('permiso:principal.salas.eliminar');

        // Permisos (solo admin / seguridad)
        Route::get('permisos', [PrincipalController::class, 'permisosIndex'])
            ->middleware('permiso:seguridad.permisos.ver');

        Route::post('permisos', [PrincipalController::class, 'permisosStore'])
            ->middleware('permiso:seguridad.permisos.crear');

        Route::get('permisos/{id}', [PrincipalController::class, 'permisosShow'])
            ->middleware('permiso:seguridad.permisos.ver');

        Route::put('permisos/{id}', [PrincipalController::class, 'permisosUpdate'])
            ->middleware('permiso:seguridad.permisos.editar');

        Route::delete('permisos/{id}', [PrincipalController::class, 'permisosDelete'])
            ->middleware('permiso:seguridad.permisos.eliminar');

        
            
    });

    /*
    |--------------------------------------------------------------------------
    | CLÍNICO
    |--------------------------------------------------------------------------
    */
    Route::prefix('clinico')->group(function () {

        Route::get('tipos-servicio', [ClinicoController::class, 'tiposIndex'])
            ->middleware('permiso:clinico.servicios.ver');

        Route::post('tipos-servicio', [ClinicoController::class, 'tiposStore'])
            ->middleware('permiso:clinico.servicios.crear');

        Route::get('tipos-servicio/{id}', [ClinicoController::class, 'tiposShow'])
            ->middleware('permiso:clinico.servicios.ver');

        Route::put('tipos-servicio/{id}', [ClinicoController::class, 'tiposUpdate'])
            ->middleware('permiso:clinico.servicios.editar');

        Route::delete('tipos-servicio/{id}', [ClinicoController::class, 'tiposDelete'])
            ->middleware('permiso:clinico.servicios.eliminar');

        // Historias Clínicas
        Route::get('historias', [ClinicoController::class, 'historiasIndex'])
            ->middleware('permiso:clinico.historias.ver');

        Route::post('historias', [ClinicoController::class, 'historiasStore'])
            ->middleware('permiso:clinico.historias.crear');

        Route::get('historias/{id}', [ClinicoController::class, 'historiasShow'])
            ->middleware('permiso:clinico.historias.ver');

        Route::put('historias/{id}', [ClinicoController::class, 'historiasUpdate'])
            ->middleware('permiso:clinico.historias.editar');

        Route::delete('historias/{id}', [ClinicoController::class, 'historiasDelete'])
            ->middleware('permiso:clinico.historias.eliminar');

        // Sesiones
        Route::get('sesiones', [ClinicoController::class, 'sesionesIndex'])
            ->middleware('permiso:clinico.sesiones.ver');

        Route::post('sesiones', [ClinicoController::class, 'sesionesStore'])
            ->middleware('permiso:clinico.sesiones.crear');

        Route::get('sesiones/{id}', [ClinicoController::class, 'sesionesShow'])
            ->middleware('permiso:clinico.sesiones.ver');

        Route::put('sesiones/{id}', [ClinicoController::class, 'sesionesUpdate'])
            ->middleware('permiso:clinico.sesiones.editar');

        Route::delete('sesiones/{id}', [ClinicoController::class, 'sesionesDelete'])
            ->middleware('permiso:clinico.sesiones.eliminar');
    });

    /*
    |--------------------------------------------------------------------------
    | AGENDA
    |--------------------------------------------------------------------------
    */
    Route::prefix('agenda')->group(function () {

        Route::get('citas', [AgendaController::class, 'citasIndex'])
            ->middleware('permiso:agenda.citas.ver');

        Route::post('citas', [AgendaController::class, 'citasStore'])
            ->middleware('permiso:agenda.citas.crear');

        Route::get('citas/{id}', [AgendaController::class, 'citasShow'])
            ->middleware('permiso:agenda.citas.ver');

        Route::put('citas/{id}', [AgendaController::class, 'citasUpdate'])
            ->middleware('permiso:agenda.citas.editar');

        Route::delete('citas/{id}', [AgendaController::class, 'citasDelete'])
            ->middleware('permiso:agenda.citas.eliminar');

        Route::get('estados', [AgendaController::class, 'estadosIndex'])
            ->middleware('permiso:agenda.estados.ver');

        Route::post('estados', [AgendaController::class, 'estadosStore'])
            ->middleware('permiso:agenda.estados.crear');

        Route::get('horarios', [AgendaController::class, 'horariosIndex'])
            ->middleware('permiso:agenda.horarios.ver');

        Route::post('horarios', [AgendaController::class, 'horariosStore'])
            ->middleware('permiso:agenda.horarios.crear');
    });

    /*
    |--------------------------------------------------------------------------
    | COMPRAS
    |--------------------------------------------------------------------------
    */
    Route::prefix('compras')->group(function () {

        Route::get('/', [ComprasController::class, 'index'])
            ->middleware('permiso:compras.compras.ver');

        Route::post('/', [ComprasController::class, 'store'])
            ->middleware('permiso:compras.compras.crear');

        Route::get('proveedores', [ComprasController::class, 'proveedoresIndex'])
            ->middleware('permiso:compras.proveedores.ver');

        Route::post('proveedores', [ComprasController::class, 'proveedoresStore'])
            ->middleware('permiso:compras.proveedores.crear');
    });

    /*
    |--------------------------------------------------------------------------
    | INVENTARIO
    |--------------------------------------------------------------------------
    */
    Route::prefix('inventario')->group(function () {

        Route::get('items', [InventarioController::class, 'itemsIndex'])
            ->middleware('permiso:inventario.items.ver');

        Route::post('items', [InventarioController::class, 'itemsStore'])
            ->middleware('permiso:inventario.items.crear');

        Route::put('items/{id}', [InventarioController::class, 'itemsUpdate'])
            ->middleware('permiso:inventario.items.editar');

        Route::get('kardex/{itemId}', [InventarioController::class, 'kardexPorItem'])
            ->middleware('permiso:inventario.kardex.ver');

        Route::get('kardex-resumen', [InventarioController::class, 'kardexResumen'])
            ->middleware('permiso:inventario.kardex.ver');
    });

    /*
    |--------------------------------------------------------------------------
    | ACTIVOS FIJOS
    |--------------------------------------------------------------------------
    */
    Route::prefix('activos')->group(function () {

        Route::get('/', [ActivoController::class, 'index'])
            ->middleware('permiso:inventario.activos.ver');

        Route::post('/', [ActivoController::class, 'store'])
            ->middleware('permiso:inventario.activos.crear');

        Route::put('{id}/estado', [ActivoController::class, 'cambiarEstado'])
            ->middleware('permiso:inventario.activos.editar');
    });

    /*
    |--------------------------------------------------------------------------
    | FACTURACIÓN
    |--------------------------------------------------------------------------
    */
    Route::prefix('facturacion')->group(function () {

        Route::post('comprobantes', [FacturacionController::class, 'emitirComprobante'])
            ->middleware('permiso:facturacion.comprobantes.emitir');

        Route::post('comprobantes/{id}/anular', [FacturacionController::class, 'anularComprobante'])
            ->middleware('permiso:facturacion.comprobantes.anular');

        Route::post('pagos', [FacturacionController::class, 'registrarPago'])
            ->middleware('permiso:facturacion.pagos.registrar');
    });

    /*
    |--------------------------------------------------------------------------
    | SUNAT
    |--------------------------------------------------------------------------
    */
    Route::prefix('sunat')->group(function () {

        Route::post('comprobantes/{comprobanteId}/enviar', [SunatController::class, 'enviar'])
            ->middleware('permiso:sunat.comprobantes.enviar');

        Route::get('comprobantes/{comprobanteId}/estado', [SunatController::class, 'estado'])
            ->middleware('permiso:sunat.comprobantes.ver');
    });

    /*
    |--------------------------------------------------------------------------
    | CONTABILIDAD
    |--------------------------------------------------------------------------
    */
    Route::prefix('contabilidad')->group(function () {

        Route::get('libros-resumen', [LibroResumenController::class, 'index'])
            ->middleware('permiso:contabilidad.libros.ver');

        Route::post('libros-resumen', [LibroResumenController::class, 'generar'])
            ->middleware('permiso:contabilidad.libros.generar');
    });

    /*
    |--------------------------------------------------------------------------
    | REPORTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('reportes')->controller(ReportesController::class)->group(function () {

        Route::get('sesiones-mensuales', 'sesionesMensuales')
            ->middleware('permiso:reportes.sesiones.ver');

        Route::get('kardex-resumen', 'kardexResumen')
            ->middleware('permiso:reportes.inventario.ver');

        Route::get('ventas-mensuales', 'ventasMensuales')
            ->middleware('permiso:reportes.ventas.ver');
    });

    /*
    |--------------------------------------------------------------------------
    | ROLES Y PERMISOS
    |--------------------------------------------------------------------------
    */
Route::prefix('v1/principal/roles')
            ->middleware(['auth', 'permiso:principal.permisos.*'])
            ->group(function () {
        
                Route::post('asignar-permiso', [RolPermisoController::class, 'asignar']);
                Route::post('revocar-permiso', [RolPermisoController::class, 'revocar']);
        
            });

    

    Route::prefix('v1/seguridad')
        ->middleware(['auth:sanctum'])
        ->group(function () {

            // Listado de auditoría
            Route::get('auditoria', [AuditController::class, 'index'])
                ->middleware('permiso:seguridad.auditoria.ver');

            // Exportar CSV
            Route::get('auditoria/exportar', [AuditController::class, 'exportarCsv'])
                ->middleware('permiso:seguridad.auditoria.exportar');
        });

});