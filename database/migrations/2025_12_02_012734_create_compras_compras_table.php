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
        Schema::create('compras_compras', function (Blueprint $table) {
            $table->char('compra_id',36)->primary();
            $table->char('proveedor_id',36)->nullable();
            $table->timestamp('fecha_compra')->useCurrent();
            $table->decimal('total',12,2)->unsigned();
            $table->string('estado',30)->default('finalizado');
            $table->unsignedBigInteger('usuario_id')->nullable();

            $table->foreign('proveedor_id')->references('proveedor_id')->on('compras_proveedores')->nullOnDelete();
            $table->foreign('usuario_id')->references('id')->on('principal_usuarios')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('compras_compras');
    }
};
