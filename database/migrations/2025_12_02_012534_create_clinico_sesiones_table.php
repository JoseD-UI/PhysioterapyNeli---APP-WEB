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
        Schema::create('clinico_sesiones', function (Blueprint $table) {
            $table->char('sesion_id',36)->primary();
            $table->char('cita_id',36)->nullable();
            $table->char('paciente_id',36);
            $table->char('fisioterapeuta_id',36)->nullable();
            $table->char('servicio_id',36)->nullable();
            $table->timestamp('fecha_atencion');
            $table->smallInteger('duracion_minutos')->nullable();
            $table->text('notas')->nullable();
            $table->text('ejercicios_realizados')->nullable();
            $table->json('materiales_usados')->nullable();
            $table->timestamp('creado_en')->useCurrent();

            $table->foreign('paciente_id')->references('persona_id')->on('principal_personas')->cascadeOnDelete();
            $table->foreign('fisioterapeuta_id')->references('persona_id')->on('principal_personas')->nullOnDelete();
            $table->foreign('servicio_id')->references('servicio_id')->on('clinico_servicios')->nullOnDelete();
            $table->index(['paciente_id','fecha_atencion']);
            $table->index(['fisioterapeuta_id','fecha_atencion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinico_sesiones');
    }
};
