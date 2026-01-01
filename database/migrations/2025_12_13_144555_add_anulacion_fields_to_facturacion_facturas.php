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
        Schema::table('facturacion_facturas', function (Blueprint $table) {
            $table->string('estado', 20)->default('emitida')->after('total');
            $table->timestamp('fecha_anulacion')->nullable()->after('estado');
            $table->string('motivo_anulacion', 255)->nullable()->after('fecha_anulacion');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facturacion_facturas', function (Blueprint $table) {
            //
        });
    }
};
