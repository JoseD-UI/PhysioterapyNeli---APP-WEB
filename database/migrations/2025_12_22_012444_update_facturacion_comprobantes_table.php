<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        // Eliminar tabla antigua si existe
        Schema::disableForeignKeyConstraints(); // Desactivar temporalmente
        Schema::dropIfExists('facturacion_detalles');
        Schema::dropIfExists('facturacion_factura_detalles');
        Schema::dropIfExists('facturacion_pagos');
        Schema::dropIfExists('facturacion_series');
        Schema::dropIfExists('facturacion_facturas');
        Schema::enableForeignKeyConstraints(); // Activar de nuevo
        Schema::dropIfExists('facturacion_comprobantes');

        // Crear nueva tabla
        Schema::create('facturacion_comprobantes', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('tipo_comprobante', 2)->default('01'); // 01=Factura
            $table->char('cliente_id', 36)->nullable();
            $table->string('ruc_cliente', 20)->nullable();
            $table->string('razon_social', 200)->nullable();
            $table->text('direccion_fiscal')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('igv', 12, 2)->default(0.00);
            $table->decimal('total', 12, 2)->default(0.00);
            $table->enum('estado', ['emitida','anulada'])->default('emitida');
            $table->timestamp('fecha_emision')->useCurrent();
            $table->timestamp('fecha_anulacion')->nullable();
            $table->string('motivo_anulacion', 255)->nullable();
            $table->string('estado_comprobante', 30)->default('emitido');
            $table->longText('xml_enviado')->nullable();
            $table->longText('xml_respuesta')->nullable();
            $table->string('cdr_estado', 255)->nullable();
            $table->text('cdr_descripcion')->nullable();
            $table->string('formato', 10)->nullable();
            $table->string('serie', 4)->nullable();
            $table->integer('correlativo')->default(0);

            // Índices
            $table->unique(['tipo_comprobante','serie','correlativo'], 'facturacion_tipo_serie_correlativo_idx');
            $table->index('cliente_id', 'facturacion_cliente_idx');
        });
    }

    public function down(): void {
        Schema::dropIfExists('facturacion_comprobantes');
    }
};
