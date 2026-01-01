<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('inventario_kardex', function (Blueprint $table) {

            // SUNAT core
            $table->enum('tipo_movimiento', ['entrada', 'salida'])
                  -> after('item_id');

            // Origen del movimiento
            $table->string('origen', 50)
                  -> nullable()
                  -> comment('compra | venta | nota_credito | ajuste');

            // Documento SUNAT
            $table->string('documento_tipo', 20)->nullable();
            $table->string('documento_serie', 10)->nullable();
            $table->integer('documento_correlativo')->nullable();

            // Valor total del movimiento
            $table->decimal('valor_total', 12, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('inventario_kardex', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_movimiento',
                'origen',
                'documento_tipo',
                'documento_serie',
                'documento_correlativo',
                'valor_total'
            ]);
        });
    }
};

