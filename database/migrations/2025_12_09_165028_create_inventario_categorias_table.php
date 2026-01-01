<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarioCategoriasTable extends Migration
{
    public function up()
    {
        Schema::create('inventario_categorias', function (Blueprint $table) {
            $table->char('categoria_id',36)->primary();
            $table->string('nombre',150);
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->useCurrent();
        });

        // Add categoria_id to items if not exists
        if (!Schema::hasColumn('inventario_items','categoria_id')) {
            Schema::table('inventario_items', function (Blueprint $table) {
                $table->char('categoria_id',36)->nullable()->after('item_id');
                $table->index('categoria_id','idx_items_categoria');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('inventario_items','categoria_id')) {
            Schema::table('inventario_items', function (Blueprint $table) {
                $table->dropIndex('idx_items_categoria');
                $table->dropColumn('categoria_id');
            });
        }
        Schema::dropIfExists('inventario_categorias');
    }
}
