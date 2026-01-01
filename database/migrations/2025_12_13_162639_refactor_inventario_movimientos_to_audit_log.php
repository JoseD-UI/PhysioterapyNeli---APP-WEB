<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inventario_movimientos', function (Blueprint $table) {

            // Tipo evento
            $table->string('evento', 50)
                  -> after('item_id')
                  -> comment('creacion | modificacion | eliminacion');

            // Relación polimórfica
            $table->string('entidad_tipo', 100)
                  -> nullable()
                  -> after('evento');

            $table->char('entidad_id', 36)
                  -> nullable()
                  -> after('entidad_tipo');

            // Snapshot
            $table->json('datos_anteriores')->nullable();
            $table->json('datos_nuevos')->nullable();

            // Campo legado
            $table->string('tipo_movimiento')->nullable()->change();
            $table->decimal('cantidad', 12, 3)->nullable()->change();
        });
    }

    public function down(): void
    {
        // reversión parcial (no recomendado en prod)
    }
};

