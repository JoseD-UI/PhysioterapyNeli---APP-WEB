<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendaCitaEstadosTable extends Migration
{
    public function up()
    {
        Schema::create('agenda_cita_estados', function (Blueprint $table) {
            $table->char('estado_id',36)->primary();
            $table->string('codigo',50)->unique(); // e.g. reservado, confirmado...
            $table->string('nombre',150);
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('agenda_cita_estados');
    }
}
