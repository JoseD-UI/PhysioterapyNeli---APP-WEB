<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contabilidad_libros_resumen', function (Blueprint $table) {
            $table->char('libro_id', 36)->primary();
        
            $table->string('tipo_libro'); // ventas | compras
            $table->string('periodo');    // YYYYMM
        
            $table->decimal('total_gravado', 12, 2);
            $table->decimal('total_igv', 12, 2);
            $table->decimal('total_general', 12, 2);
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contabilidad_libros_resumen');
    }
};
