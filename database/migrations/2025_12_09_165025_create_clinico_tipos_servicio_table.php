<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicoTiposServicioTable extends Migration
{
    public function up()
    {
        Schema::create('clinico_tipos_servicio', function (Blueprint $table) {
            $table->char('tipo_id',36)->primary();
            $table->string('nombre',150);
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clinico_tipos_servicio');
    }
}
