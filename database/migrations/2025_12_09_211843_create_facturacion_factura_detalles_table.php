<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('facturacion_factura_detalles', function (Blueprint $table) {
            $table->char('detalle_id',36)->primary();
            $table->char('factura_id',36);
            $table->string('descripcion',255)->nullable();
            $table->decimal('cantidad',12,3)->default(1);
            $table->decimal('precio_unitario',12,2)->default(0);
            $table->decimal('subtotal',12,2)->default(0);
            $table->decimal('igv',12,2)->default(0);
            $table->decimal('total',12,2)->default(0);
            $table->timestamps(0);

            $table->foreign('factura_id')->references('factura_id')->on('facturacion_facturas')->onDelete('cascade');
            $table->index('factura_id','factura_detalle_factura_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturacion_factura_detalles');
    }
};
