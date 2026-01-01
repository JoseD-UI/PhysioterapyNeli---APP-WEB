<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('principal_personas', function (Blueprint $table) {
            $table->char('persona_id', 36)->primary();
            $table->string('tipo_persona', 30); // paciente, fisioterapeuta, proveedor, empleado, otro
            $table->string('nombres', 150);
            $table->string('apellidos', 150)->nullable();
            $table->string('dni', 20)->nullable();
            $table->string('ruc', 20)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('email', 200)->nullable();
            $table->text('direccion')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();
            $table->index('dni');
            $table->index('ruc');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('principal_personas');
    }
};
