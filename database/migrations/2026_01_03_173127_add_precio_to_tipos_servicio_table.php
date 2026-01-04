<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clinico_tipos_servicio', function (Blueprint $table) {
            $table->decimal('precio', 10, 2)->default(0)->after('descripcion');
            $table->string('imagen_url')->nullable()->after('precio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinico_tipos_servicio', function (Blueprint $table) {
            //
        });
    }
};
