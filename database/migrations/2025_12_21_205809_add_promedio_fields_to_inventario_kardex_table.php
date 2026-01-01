<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventario_kardex', function (Blueprint $table) {

            $table->decimal('saldo_cantidad', 12, 3)
                  ->after('saldo');
        
            $table->decimal('saldo_valorizado', 12, 2)
                  ->after('saldo_cantidad');
        
            $table->decimal('costo_promedio_resultante', 12, 2)
                  ->after('costo_unitario');
        
            $table->enum('metodo_valorizacion', ['PROMEDIO'])
                  ->default('PROMEDIO');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventario_kardex', function (Blueprint $table) {
            //
        });
    }
};
