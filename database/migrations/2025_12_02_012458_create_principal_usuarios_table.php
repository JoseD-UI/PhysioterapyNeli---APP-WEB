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
            // New Primary Key Auto Increment
            $table->id();

            // Foreign Key to users authentication table
            $table->unsignedBigInteger('user_id')->unique(); // 1:1 Relationship

            $table->char('persona_id',36)->nullable();
            $table->string('username',80)->unique();
            // password_hash removed, auth is in users table
            
            $table->unsignedSmallInteger('rol_id')->nullable();
            $table->boolean('activo')->default(true);
            $table->smallInteger('intento_fallido')->default(0);
            $table->timestamp('ultimo_acceso')->nullable();
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->useCurrent()->useCurrentOnUpdate();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
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
