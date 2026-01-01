<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgendaDiasNoLaborablesTable extends Migration
{
    public function up()
    {
        Schema::create('agenda_dias_no_laborables', function (Blueprint $table) {
            $table->char('nolaborable_id',36)->primary();
            $table->date('fecha');
            $table->char('fisioterapeuta_id',36)->nullable(); // null => aplica a todo el centro
            $table->string('motivo')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->nullable()->useCurrentOnUpdate();

            $table->index(['fecha','fisioterapeuta_id'],'idx_nolaborable_fecha');
        });
    }

    public function down()
    {
        Schema::dropIfExists('agenda_dias_no_laborables');
    }
}
