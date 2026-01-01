<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrincipalPermisosTable extends Migration
{
    public function up()
    {
        Schema::create('principal_permisos', function (Blueprint $table) {
            $table->char('permiso_id',36)->primary();
            $table->string('codigo',150)->unique(); // ej. personas.create
            $table->string('nombre',150);
            $table->text('descripcion')->nullable();
            $table->timestamp('creado_en')->useCurrent();
        });

        Schema::create('principal_rol_permiso', function (Blueprint $table) {
            $table->char('rol_id',36);
            $table->char('permiso_id',36);
            $table->primary(['rol_id','permiso_id']);
            $table->timestamp('asignado_en')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('principal_rol_permiso');
        Schema::dropIfExists('principal_permisos');
    }
}

