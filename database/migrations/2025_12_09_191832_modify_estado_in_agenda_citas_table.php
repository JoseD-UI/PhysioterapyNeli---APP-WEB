<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agenda_citas', function (Blueprint $table) {
            // 1. Eliminar columna antigua
            if (Schema::hasColumn('agenda_citas', 'estado')) {
                $table->dropColumn('estado');
            }

            // 2. Nueva columna FK
            $table->char('estado_id', 36)->after('fecha_fin');

            // 3. FK
            $table->foreign('estado_id')
                  ->references('estado_id')
                  ->on('agenda_cita_estados')
                  ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::table('agenda_citas', function (Blueprint $table) {
            // Revertir FK
            $table->dropForeign(['estado_id']);

            // Eliminar nueva columna
            $table->dropColumn('estado_id');

            // Restaurar antigua columna
            $table->string('estado', 30)->default('reservado');
        });
    }
};

