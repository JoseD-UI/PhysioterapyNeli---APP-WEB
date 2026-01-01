<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendaHorariosFisioterapeutaTable extends Migration
{
    public function up()
    {
        Schema::create('agenda_horarios_fisioterapeuta', function (Blueprint $table) {
            $table->char('horario_id',36)->primary();
            $table->char('fisioterapeuta_id',36);
            $table->tinyInteger('dia_semana'); // 0=domingo ... 6=sabado
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->nullable()->useCurrentOnUpdate();

            $table->index(['fisioterapeuta_id','dia_semana'],'idx_horario_fisio_dia');
        });
    }

    public function down()
    {
        Schema::dropIfExists('agenda_horarios_fisioterapeuta');
    }
}
