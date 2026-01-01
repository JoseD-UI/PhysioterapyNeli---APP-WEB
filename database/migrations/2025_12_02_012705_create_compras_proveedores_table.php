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
        Schema::create('compras_proveedores', function (Blueprint $table) {
        $table->char('proveedor_id',36)->primary();
        $table->string('nombre',200);
        $table->string('ruc',20)->nullable();
        $table->string('telefono',50)->nullable();
        $table->string('email',200)->nullable();
        $table->text('direccion')->nullable();
        $table->timestamp('creado_en')->useCurrent();

        $table->index('nombre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras_proveedores');
    }
};
