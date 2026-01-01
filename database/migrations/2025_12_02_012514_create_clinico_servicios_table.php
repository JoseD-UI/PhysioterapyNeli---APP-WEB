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
        Schema::create('clinico_servicios', function (Blueprint $table) {
            $table->char('servicio_id',36)->primary();
            $table->string('nombre',150);
            $table->text('descripcion')->nullable();
            $table->smallInteger('duracion_minutos')->unsigned();
            $table->decimal('precio',12,2)->unsigned();
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinico_servicios');
    }
};
