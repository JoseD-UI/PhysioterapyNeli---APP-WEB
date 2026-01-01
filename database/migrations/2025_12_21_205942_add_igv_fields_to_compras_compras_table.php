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
        Schema::table('compras_compras', function (Blueprint $table) {
            $table->decimal('subtotal', 12, 2)->after('fecha_compra');
            $table->decimal('igv', 12, 2)->after('subtotal');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compras_compras', function (Blueprint $table) {
            //
        });
    }
};
