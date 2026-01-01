<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('facturacion_pagos', function (Blueprint $table) {
            if (!Schema::hasColumn('facturacion_pagos','referencia_externa')) {
                $table->string('referencia_externa',200)->nullable()->after('recibo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('facturacion_pagos', function (Blueprint $table) {
            $table->dropColumn('referencia_externa');
        });
    }
};
