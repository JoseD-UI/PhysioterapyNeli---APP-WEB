<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturacion_series', function (Blueprint $table) {
            $table->id(); // auto-incremental
            $table->string('tipo_comprobante', 2); // 01=factura, 03=boleta, 12=ticket
            $table->string('serie', 10); // F001, B001...
            $table->integer('ultimo_correlativo')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            // Índices
            $table->unique(['tipo_comprobante','serie'], 'facturacion_series_tipo_serie_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturacion_series');
    }
};



