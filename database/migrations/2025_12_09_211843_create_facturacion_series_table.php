<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('facturacion_series', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_comprobante',5); // 01/03/07/08/12
            $table->string('serie',10); // F001
            $table->unsignedBigInteger('ultimo_correlativo')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps(0);

            $table->unique(['tipo_comprobante','serie'],'facturacion_series_tipo_serie_uq');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturacion_series');
    }
};
