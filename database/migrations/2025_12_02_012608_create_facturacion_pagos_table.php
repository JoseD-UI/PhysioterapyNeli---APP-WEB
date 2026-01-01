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
        Schema::create('facturacion_pagos', function (Blueprint $table) {
            $table->char('pago_id',36)->primary();
            $table->char('paciente_id',36)->nullable();
            $table->char('sesion_id',36)->nullable();
            $table->decimal('monto',12,2)->unsigned();
            $table->string('metodo_pago',50);
            $table->string('estado_pago',30)->default('pagado');
            $table->timestamp('fecha_pago')->useCurrent();
            $table->string('recibo',120)->nullable();

            $table->foreign('paciente_id')->references('persona_id')->on('principal_personas')->nullOnDelete();
            $table->foreign('sesion_id')->references('sesion_id')->on('clinico_sesiones')->nullOnDelete();
            $table->index(['paciente_id','fecha_pago']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('facturacion_pagos');
    }
};
