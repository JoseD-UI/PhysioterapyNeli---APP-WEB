<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inventario_items', function (Blueprint $table) {
            $table->boolean('es_activo')
                ->default(false)
                ->after('activo')
                ->comment('Indica si el item es un activo fijo SUNAT');
        });
    }

    public function down()
    {
        Schema::table('inventario_items', function (Blueprint $table) {
            $table->dropColumn('es_activo');
        });
    }

};
