<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrincipalSalasTable extends Migration
{
    public function up()
    {
        Schema::create('principal_salas', function (Blueprint $table) {
            $table->char('sala_id', 36)->primary();
            $table->string('nombre_sala', 120);
            $table->text('descripcion')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->nullable()->useCurrentOnUpdate();
        });
    }

    public function down()
    {
        Schema::dropIfExists('principal_salas');
    }
}
