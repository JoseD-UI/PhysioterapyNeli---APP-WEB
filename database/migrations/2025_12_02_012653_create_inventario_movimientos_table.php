<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('inventario_movimientos', function (Blueprint $table) {
            $table->char('movimiento_id',36)->primary();
            $table->char('item_id',36);
            $table->string('tipo_movimiento',20);
            $table->decimal('cantidad',12,3);
            $table->timestamp('fecha_movimiento')->useCurrent();
            $table->string('referencia',200)->nullable();
            $table->char('usuario_id',36)->nullable();

            $table->foreign('item_id')->references('item_id')->on('inventario_items')->cascadeOnDelete();
            $table->foreign('usuario_id')->references('usuario_id')->on('principal_usuarios')->nullOnDelete();
            $table->index(['item_id','fecha_movimiento']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('inventario_movimientos');
    }
};
