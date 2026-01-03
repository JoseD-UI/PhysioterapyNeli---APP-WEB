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
        Schema::create('principal_usuarios', function (Blueprint $table) {
            // Primary key que coincide con users.id
            $table->unsignedBigInteger('usuario_id')->primary();
            $table->char('persona_id',36)->nullable();
            $table->string('username',80)->unique();
            $table->string('password_hash'); // usaremos hash bcrypt
            $table->unsignedSmallInteger('rol_id')->nullable();
            $table->boolean('activo')->default(true);
            $table->smallInteger('intento_fallido')->default(0);
            $table->timestamp('ultimo_acceso')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            // Foreign keys
            $table->foreign('usuario_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('persona_id')->references('persona_id')->on('principal_personas')->nullOnDelete();
            $table->foreign('rol_id')->references('rol_id')->on('principal_roles')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('principal_usuarios');
    }
};
