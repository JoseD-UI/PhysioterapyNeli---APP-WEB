<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {

        // Eliminar tabla antigua si existe
        Schema::dropIfExists('facturacion_factura_detalles');
        Schema::dropIfExists('facturacion_detalles');

        // Crear nueva tabla
        Schema::create('facturacion_detalles', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('comprobante_id', 36);
            $table->char('producto_id', 36)->nullable();
            $table->string('descripcion', 255)->nullable();
            $table->decimal('cantidad', 12, 2)->default(1);
            $table->decimal('precio_unitario', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('igv', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            // Relaciones
            $table->foreign('comprobante_id')->references('id')->on('facturacion_comprobantes')->onDelete('cascade');

            // Índices
            $table->index('producto_id', 'facturacion_detalle_producto_idx');
        });
    }

    public function down(): void {
        Schema::dropIfExists('facturacion_detalles');
    }
};
