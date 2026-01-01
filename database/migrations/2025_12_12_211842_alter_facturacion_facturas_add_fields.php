<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::table('facturacion_facturas', function (Blueprint $table) {

        if (!Schema::hasColumn('facturacion_facturas', 'tipo_comprobante')) {
            $table->string('tipo_comprobante', 5)
                  ->nullable()
                  ->after('factura_id')
                  ->comment('01=factura,03=boleta,12=ticket,07=nota credito,08=nota debito');
        }

        if (!Schema::hasColumn('facturacion_facturas', 'serie')) {
            $table->string('serie', 10)
                  ->nullable()
                  ->after('tipo_comprobante');
        }

        if (!Schema::hasColumn('facturacion_facturas', 'correlativo')) {
            $table->unsignedBigInteger('correlativo')
                  ->nullable()
                  ->after('serie');
        }

        if (!Schema::hasColumn('facturacion_facturas', 'subtotal')) {
            $table->decimal('subtotal', 12, 2)
                  ->default(0)
                  ->after('direccion_fiscal');
        }

        if (!Schema::hasColumn('facturacion_facturas', 'igv')) {
            $table->decimal('igv', 12, 2)
                  ->default(0)
                  ->after('subtotal');
        }

        if (!Schema::hasColumn('facturacion_facturas', 'estado_comprobante')) {
            $table->string('estado_comprobante', 30)
                  ->default('emitido')
                  ->after('total');
        }

        if (!Schema::hasColumn('facturacion_facturas', 'hash_cpe')) {
            $table->text('hash_cpe')
                  ->nullable()
                  ->after('estado_comprobante');
        }

        if (!Schema::hasColumn('facturacion_facturas', 'formato')) {
            $table->string('formato', 10)
                  ->nullable()
                  ->after('hash_cpe')
                  ->comment('json|xml');
        }

        // ✅ ÍNDICE SOLO DESPUÉS DE CREAR LAS COLUMNAS
        $table->index(
            ['tipo_comprobante', 'serie', 'correlativo'],
            'facturacion_tipo_serie_correlativo_idx'
        );
    });
}


    public function down(): void
    {
        Schema::table('facturacion_facturas', function (Blueprint $table) {
            $table->dropIndex('facturacion_tipo_serie_correlativo_idx');
            $table->dropColumn(['tipo_comprobante','serie','correlativo','subtotal','igv','estado_comprobante','hash_cpe','formato']);
        });
    }
};
