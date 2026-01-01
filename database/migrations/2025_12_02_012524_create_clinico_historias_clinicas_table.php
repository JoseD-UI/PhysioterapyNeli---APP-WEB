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
        Schema::create('clinico_historias_clinicas', function (Blueprint $table) {
            $table->char('historia_id',36)->primary();
            $table->char('persona_id',36);
            $table->text('motivo_consulta')->nullable();
            $table->text('antecedentes')->nullable();
            $table->text('alergias')->nullable();
            $table->text('diagnostico_inicial')->nullable();
            $table->text('recomendaciones')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
    
            $table->foreign('persona_id')->references('persona_id')->on('principal_personas')->cascadeOnDelete();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinico_historias_clinicas');
    }
};
