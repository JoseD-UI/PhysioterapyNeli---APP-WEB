<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('agenda_citas', function (Blueprint $table) {
            $table->char('cita_id',36)->primary();
            $table->char('paciente_id',36);
            $table->char('fisioterapeuta_id',36)->nullable();
            $table->char('servicio_id',36)->nullable();
            $table->dateTime('fecha_inicio')->nullable(false);
$table->dateTime('fecha_fin')->nullable(false);
            $table->string('estado',30)->default('reservado');
            $table->unsignedBigInteger('creado_por')->nullable();
            $table->timestamp('creado_en')->useCurrent();

            $table->foreign('paciente_id')->references('persona_id')->on('principal_personas')->cascadeOnDelete();
            $table->foreign('fisioterapeuta_id')->references('persona_id')->on('principal_personas')->nullOnDelete();
            $table->foreign('servicio_id')->references('servicio_id')->on('clinico_servicios')->nullOnDelete();
            $table->foreign('creado_por')->references('id')->on('principal_usuarios')->nullOnDelete();
            $table->index(['paciente_id','fecha_inicio']);
            $table->index(['fisioterapeuta_id','fecha_inicio']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('agenda_citas');
    }
};
