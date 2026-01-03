<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// MODELOS A AUDITAR
use App\Models\Principal\Usuario;
use App\Models\Facturacion\FacturacionComprobante;
use App\Models\Inventario\InventarioItem;

// OBSERVER
use App\Observers\AuditObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Aquí normalmente no necesitas nada para auditoría
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | REGISTRO DE OBSERVERS DE AUDITORÍA
        |--------------------------------------------------------------------------
        | Cada modelo observado generará registros automáticos
        | en seguridad_audit_log para INSERT / UPDATE / DELETE
        */

        Usuario::observe(AuditObserver::class);
        FacturacionComprobante::observe(AuditObserver::class);
        InventarioItem::observe(AuditObserver::class);

        // 👉 Agrega más modelos aquí cuando lo necesites
        // Ejemplo:
        // Compra::observe(AuditObserver::class);
        // AgendaCita::observe(AuditObserver::class);
    }
}
