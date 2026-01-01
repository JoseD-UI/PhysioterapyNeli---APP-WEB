<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contabilidad_libros_resumen', function (Blueprint $table) {

            // Unicidad contable por periodo
            $table->unique(
                ['tipo_libro','periodo'],
                'libro_tipo_periodo_unique'
            );
        });
    }

    public function down(): void
    {
        // No se revierte por integridad contable
    }
};
