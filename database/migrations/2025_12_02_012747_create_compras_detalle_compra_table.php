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
        Schema::create('compras_detalle_compra', function (Blueprint $table) {
            $table->char('detalle_id',36)->primary();
            $table->char('compra_id',36);
            $table->char('item_id',36)->nullable();
            $table->decimal('cantidad',12,3);
            $table->decimal('precio_unitario',12,2);
            $table->decimal('subtotal',12,2);

            $table->foreign('compra_id')->references('compra_id')->on('compras_compras')->cascadeOnDelete();
            $table->foreign('item_id')->references('item_id')->on('inventario_items')->nullOnDelete();
            $table->index('compra_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras_detalle_compra');
    }
};
