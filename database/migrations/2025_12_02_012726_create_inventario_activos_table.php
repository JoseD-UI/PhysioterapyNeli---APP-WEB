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
        Schema::create('inventario_activos', function (Blueprint $table) {
            $table->char('activo_id',36)->primary();
            $table->string('nombre_activo',200);
            $table->string('categoria',100)->nullable();
            $table->string('estado_activo',50)->default('operativo');
            $table->date('fecha_compra')->nullable();
            $table->decimal('valor_compra',12,2)->nullable();
            $table->char('proveedor_id',36)->nullable();
            $table->integer('vida_util_meses')->nullable();
            $table->timestamp('creado_en')->useCurrent();

            $table->foreign('proveedor_id')->references('proveedor_id')->on('compras_proveedores')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_activos');
    }
};
