<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarioKardexTable extends Migration
{
    public function up()
    {
        Schema::create('inventario_kardex', function (Blueprint $table) {
            $table->char('kardex_id',36)->primary();
            $table->char('item_id',36);
            $table->char('referencia_id',36)->nullable(); // e.g., compra_id, movimiento_id, etc.
            $table->string('referencia_tipo',100)->nullable(); // 'compra','movimiento','ajuste'
            $table->decimal('cantidad',12,3);
            $table->decimal('saldo',12,3);
            $table->decimal('costo_unitario',12,2)->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->char('usuario_id',36)->nullable();
            $table->text('nota')->nullable();

            $table->index(['item_id','fecha'],'idx_kardex_item_fecha');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventario_kardex');
    }
}
