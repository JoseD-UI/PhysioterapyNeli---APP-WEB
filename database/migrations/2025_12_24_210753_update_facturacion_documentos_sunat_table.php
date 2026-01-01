<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facturacion_documentos_sunat', function (Blueprint $table) {

            // Eliminar columnas obsoletas
            if (Schema::hasColumn('facturacion_documentos_sunat', 'factura_id')) {
                $table->dropColumn('factura_id');
            }

            if (Schema::hasColumn('facturacion_documentos_sunat', 'nota_credito_id')) {
                $table->dropColumn('nota_credito_id');
            }

            // Nueva relación unificada
            if (!Schema::hasColumn('facturacion_documentos_sunat', 'comprobante_id')) {
                $table->char('comprobante_id',36)->after('documento_sunat_id');
            }

            // Campo técnico adicional
            if (!Schema::hasColumn('facturacion_documentos_sunat', 'fecha_respuesta')) {
                $table->timestamp('fecha_respuesta')->nullable()->after('fecha_envio');
            }
        });

        // FK y unicidad SUNAT
        Schema::table('facturacion_documentos_sunat', function (Blueprint $table) {

            $table->foreign('comprobante_id')
                ->references('id')
                ->on('facturacion_comprobantes')
                ->onDelete('cascade');

            $table->unique(
                ['tipo_comprobante','serie','correlativo'],
                'sunat_tipo_serie_correlativo_unique'
            );
        });
    }

    public function down(): void
    {
        // No se revierte por seguridad contable
    }
};

 