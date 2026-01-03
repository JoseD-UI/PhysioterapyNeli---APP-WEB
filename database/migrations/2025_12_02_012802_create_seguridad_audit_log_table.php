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
        Schema::create('seguridad_audit_log', function (Blueprint $table) {
            $table->bigIncrements('audit_id');
            $table->string('esquema',100);
            $table->string('tabla_nombre',100);
            $table->enum('operacion',['INSERT','UPDATE','DELETE','FUNC_ERROR']);
            $table->char('registro_id',36)->nullable();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->json('datos_previos')->nullable();
            $table->json('datos_nuevos')->nullable();
            $table->text('descripcion')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('seguridad_audit_log');
    }
};
