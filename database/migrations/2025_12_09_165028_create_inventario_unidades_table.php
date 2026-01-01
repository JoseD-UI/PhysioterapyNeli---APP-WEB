<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarioUnidadesTable extends Migration
{
    public function up()
    {
        Schema::create('inventario_unidades', function (Blueprint $table) {
            $table->char('unidad_id',36)->primary();
            $table->string('codigo',50);
            $table->string('nombre',100);
            $table->timestamp('creado_en')->useCurrent();
        });

        if (!Schema::hasColumn('inventario_items','unidad_medida_id')) {
            Schema::table('inventario_items', function (Blueprint $table) {
                $table->char('unidad_medida_id',36)->nullable()->after('categoria_id');
                $table->index('unidad_medida_id','idx_items_unidad');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('inventario_items','unidad_medida_id')) {
            Schema::table('inventario_items', function (Blueprint $table) {
                $table->dropIndex('idx_items_unidad');
                $table->dropColumn('unidad_medida_id');
            });
        }
        Schema::dropIfExists('inventario_unidades');
    }
}

