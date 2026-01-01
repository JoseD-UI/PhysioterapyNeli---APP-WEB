<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inventario_activos', function (Blueprint $table) {

            // Normalizar estado
            $table->enum('estado_activo', [
                'ACTIVO',
                'MANTENIMIENTO',
                'BAJA'
            ])->change();

            // Índices útiles
            $table->index('estado_activo');
            $table->index('categoria');
            $table->index('proveedor_id');
        });
    }

    public function down(): void
    {
        Schema::table('inventario_activos', function (Blueprint $table) {
            $table->dropIndex(['estado_activo']);
            $table->dropIndex(['categoria']);
            $table->dropIndex(['proveedor_id']);
        });
    }
};
