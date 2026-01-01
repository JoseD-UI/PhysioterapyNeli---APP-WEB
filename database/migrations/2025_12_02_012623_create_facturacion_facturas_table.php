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
        Schema::create('facturacion_facturas', function (Blueprint $table) {
            $table->char('factura_id',36)->primary();
            $table->char('pago_id',36)->nullable();
            $table->string('ruc_cliente',20)->nullable();
            $table->string('razon_social',200)->nullable();
            $table->text('direccion_fiscal')->nullable();
            $table->timestamp('fecha_emision')->useCurrent();
            $table->decimal('total',12,2)->unsigned();

            $table->foreign('pago_id')->references('pago_id')->on('facturacion_pagos')->nullOnDelete();
        });
}

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('facturacion_facturas');
    }
};
