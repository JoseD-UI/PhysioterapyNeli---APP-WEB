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
        Schema::create('facturacion_notas_credito', function (Blueprint $table) {
            $table->char('nota_credito_id', 36)->primary();
            $table->char('factura_id', 36);
            $table->string('serie', 10);
            $table->integer('correlativo');
            $table->string('motivo_codigo', 10); // SUNAT: 01, 07, etc
            $table->string('motivo_descripcion', 255);
            $table->timestamp('fecha_emision')->useCurrent();
            $table->decimal('total', 12, 2);
            $table->string('estado', 20)->default('emitida');
        
            $table->foreign('factura_id')
                  ->references('factura_id')
                  ->on('facturacion_facturas');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturacion_notas_credito');
    }
};
