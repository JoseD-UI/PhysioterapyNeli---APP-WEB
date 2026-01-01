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
        Schema::create('facturacion_documentos_sunat', function (Blueprint $table) {
            $table->char('documento_sunat_id', 36)->primary();
        
            $table->char('factura_id', 36)->nullable();
            $table->char('nota_credito_id', 36)->nullable();
        
            $table->string('tipo_comprobante', 2);
            $table->string('serie', 10);
            $table->integer('correlativo');
        
            $table->longText('xml_enviado')->nullable();
            $table->longText('xml_respuesta')->nullable();
            $table->string('cdr_estado')->nullable();
            $table->text('cdr_descripcion')->nullable();
        
            $table->timestamp('fecha_envio')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturacion_documentos_sunat');
    }
};
