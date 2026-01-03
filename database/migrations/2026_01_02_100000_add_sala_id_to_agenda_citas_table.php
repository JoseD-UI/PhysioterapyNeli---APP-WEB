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
        Schema::table('agenda_citas', function (Blueprint $table) {
            if (!Schema::hasColumn('agenda_citas', 'sala_id')) {
                $table->char('sala_id', 36)->nullable()->after('servicio_id');
                $table->foreign('sala_id')->references('sala_id')->on('principal_salas')->nullOnDelete();
                $table->index(['sala_id', 'fecha_inicio']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agenda_citas', function (Blueprint $table) {
            if (Schema::hasColumn('agenda_citas', 'sala_id')) {
                $table->dropForeign(['sala_id']);
                $table->dropColumn('sala_id');
            }
        });
    }
};
