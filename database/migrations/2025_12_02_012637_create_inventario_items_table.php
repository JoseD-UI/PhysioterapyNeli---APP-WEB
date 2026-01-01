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
        Schema::create('inventario_items', function (Blueprint $table) {
            $table->char('item_id',36)->primary();
            $table->string('nombre',200);
            $table->text('descripcion')->nullable();
            $table->string('tipo',50)->default('insumo');
            $table->string('unidad_medida',30)->nullable();
            $table->decimal('precio_unitario',12,2)->default(0);
            $table->decimal('stock_actual',12,3)->default(0);
            $table->decimal('stock_minimo',12,3)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->useCurrent();

            $table->index('nombre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('inventario_items');
    }
};
