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
        Schema::create('facturacion_nota_credito_detalle', function (Blueprint $table) {
            $table->char('detalle_id', 36)->primary();
            $table->char('nota_credito_id', 36);
            $table->char('item_id', 36)->nullable();
            $table->string('descripcion', 255);
            $table->decimal('cantidad', 12, 3);
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('subtotal', 12, 2);
        
            $table->foreign('nota_credito_id')
                  ->references('nota_credito_id')
                  ->on('facturacion_notas_credito')
                  ->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturacion_nota_credito_detalle');
    }
};
