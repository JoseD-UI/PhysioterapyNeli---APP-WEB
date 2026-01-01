<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inventario_items', function (Blueprint $table) {
            $table->decimal('costo_promedio', 12, 2)
                  -> default(0)
                  -> after('precio_unitario');

            $table->decimal('ultimo_costo', 12, 2)
                  -> default(0)
                  -> after('costo_promedio');
        });
    }

    public function down(): void
    {
        Schema::table('inventario_items', function (Blueprint $table) {
            $table->dropColumn(['costo_promedio', 'ultimo_costo']);
        });
    }
};

