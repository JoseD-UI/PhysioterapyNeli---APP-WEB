<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturacion_pagos', function (Blueprint $table) {
            $table->char('id', 36)->primary(); // UUID
            $table->char('comprobante_id', 36); // FK a facturacion_comprobantes
            $table->char('paciente_id', 36)->nullable(); // FK a personas, nullable
            $table->char('sesion_id', 36)->nullable(); // FK a sesiones, nullable
            $table->string('medio_pago', 50); // Efectivo, tarjeta, etc.
            $table->decimal('monto', 12, 2)->default(0.00);
            $table->timestamp('fecha_pago')->useCurrent();
            $table->timestamps();

            // Foreign keys
            $table->foreign('comprobante_id')
                ->references('id')
                ->on('facturacion_comprobantes')
                ->onDelete('cascade');

            $table->foreign('paciente_id')
                ->references('persona_id')
                ->on('principal_personas')
                ->onDelete('set null')
                ;

            $table->foreign('sesion_id')
                ->references('sesion_id')
                ->on('clinico_sesiones')
                ->onDelete('set null')
                ;
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturacion_pagos');
    }
};
