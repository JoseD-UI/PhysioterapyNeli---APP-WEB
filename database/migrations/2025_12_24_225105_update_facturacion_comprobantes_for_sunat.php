<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facturacion_comprobantes', function (Blueprint $table) {

            /**
             * 1️⃣ tipo_comprobante
             * - Eliminar DEFAULT
             * - Mantener NOT NULL
             * SUNAT exige que venga explícito
             */
            $table->string('tipo_comprobante', 2)
                ->nullable(false)
                ->change();

            /**
             * 2️⃣ serie
             * - NO puede ser NULL
             * - SUNAT exige serie obligatoria
             */
            $table->string('serie', 4)
                ->nullable(false)
                ->change();

            /**
             * 3️⃣ correlativo
             * - Se mantiene como INT
             * - El padding se hará en Service
             */
            $table->integer('correlativo')
                ->nullable(false)
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('facturacion_comprobantes', function (Blueprint $table) {

            // Volver a estado anterior (solo para rollback)
            $table->string('tipo_comprobante', 2)
                ->default('01')
                ->change();

            $table->string('serie', 4)
                ->nullable()
                ->change();
        });
    }
};
