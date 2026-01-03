<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Agregar índices para optimizar queries frecuentes
     */
    public function up(): void
    {
        // Principal - Personas
        Schema::table('principal_personas', function (Blueprint $table) {
            $table->index('dni', 'idx_personas_dni');
            $table->index('ruc', 'idx_personas_ruc');
            $table->index('email', 'idx_personas_email');
            $table->index('tipo_persona', 'idx_personas_tipo');
        });

        // Principal - Usuarios
        Schema::table('principal_usuarios', function (Blueprint $table) {
            $table->index('persona_id', 'idx_usuarios_persona');
            $table->index('rol_id', 'idx_usuarios_rol');
            $table->index('activo', 'idx_usuarios_activo');
        });

        // Agenda - Citas
        Schema::table('agenda_citas', function (Blueprint $table) {
            $table->index('paciente_id', 'idx_citas_paciente');
            $table->index('fisioterapeuta_id', 'idx_citas_fisioterapeuta');
            // 'estado' fue renombrado a 'estado_id' en migración 2025_12_09_191832
            $table->index('estado_id', 'idx_citas_estado');
            $table->index('fecha_inicio', 'idx_citas_fecha_inicio');
            $table->index(['fisioterapeuta_id', 'fecha_inicio'], 'idx_citas_fisio_fecha');
        });

        // Agenda - Horarios
        Schema::table('agenda_horarios_fisioterapeuta', function (Blueprint $table) {
            $table->index('fisioterapeuta_id', 'idx_horarios_fisioterapeuta');
            $table->index('dia_semana', 'idx_horarios_dia');
        });

        // Clínico - Historias Clínicas
        Schema::table('clinico_historias_clinicas', function (Blueprint $table) {
            $table->index('persona_id', 'idx_historias_persona');
            $table->index('creado_en', 'idx_historias_fecha');
        });

        // Clínico - Sesiones
        Schema::table('clinico_sesiones', function (Blueprint $table) {
            $table->index('paciente_id', 'idx_sesiones_paciente');
            $table->index('fisioterapeuta_id', 'idx_sesiones_fisioterapeuta');
            $table->index('servicio_id', 'idx_sesiones_servicio');
            // fecha_atencion is the correct column name
            $table->index('fecha_atencion', 'idx_sesiones_fecha');
        });

        // Facturación - Comprobantes
        Schema::table('facturacion_comprobantes', function (Blueprint $table) {
            $table->index('cliente_id', 'idx_comprobantes_cliente');
            $table->index('tipo_comprobante', 'idx_comprobantes_tipo');
            $table->index('serie', 'idx_comprobantes_serie');
            // correlativo is the correct column name
            $table->index('correlativo', 'idx_comprobantes_numero');
            // fecha_emision exists in the table
            $table->index('fecha_emision', 'idx_comprobantes_fecha');
            // estado_comprobante is the correct column name
            $table->index('estado_comprobante', 'idx_comprobantes_estado_sunat');
            $table->index(['serie', 'correlativo'], 'idx_comprobantes_serie_numero');
        });

        // Facturación - Pagos
        Schema::table('facturacion_pagos', function (Blueprint $table) {
            $table->index('comprobante_id', 'idx_pagos_comprobante');
            // fecha_pago exists in the table
            $table->index('fecha_pago', 'idx_pagos_fecha');
            // medio_pago is the correct column name
            $table->index('medio_pago', 'idx_pagos_metodo');
        });

        // Inventario - Items
        // NOTA: Los índices de inventario_items ya existen en la migración original
        // Schema::table('inventario_items', function (Blueprint $table) {
        //     $table->index('categoria_id', 'idx_items_categoria');
        //     $table->index('unidad_id', 'idx_items_unidad');
        //     $table->index('codigo', 'idx_items_codigo');
        //     $table->index('es_activo', 'idx_items_es_activo');
        // });

        // Inventario - Kardex
        // NOTA: Algunos índices ya existen en migraciones previas
        Schema::table('inventario_kardex', function (Blueprint $table) {
            $table->index('item_id', 'idx_kardex_item');
            $table->index('fecha', 'idx_kardex_fecha');
            // Este índice ya existe en la migración original
            // $table->index(['item_id', 'fecha'], 'idx_kardex_item_fecha');
        });

        // Compras
        Schema::table('compras_compras', function (Blueprint $table) {
            $table->index('proveedor_id', 'idx_compras_proveedor');
            // fecha_compra exists in the table
            $table->index('fecha_compra', 'idx_compras_fecha');
        });

        // Seguridad - Audit Log
        Schema::table('seguridad_audit_log', function (Blueprint $table) {
            $table->index('usuario_id', 'idx_audit_usuario');
            $table->index('tabla_nombre', 'idx_audit_tabla');
            $table->index('operacion', 'idx_audit_operacion');
            $table->index('fecha', 'idx_audit_fecha');
            $table->index(['tabla_nombre', 'fecha'], 'idx_audit_tabla_fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Principal - Personas
        Schema::table('principal_personas', function (Blueprint $table) {
            $table->dropIndex('idx_personas_dni');
            $table->dropIndex('idx_personas_ruc');
            $table->dropIndex('idx_personas_email');
            $table->dropIndex('idx_personas_tipo');
        });

        // Principal - Usuarios
        Schema::table('principal_usuarios', function (Blueprint $table) {
            $table->dropIndex('idx_usuarios_persona');
            $table->dropIndex('idx_usuarios_rol');
            $table->dropIndex('idx_usuarios_activo');
        });

        // Agenda - Citas
        Schema::table('agenda_citas', function (Blueprint $table) {
            $table->dropIndex('idx_citas_paciente');
            $table->dropIndex('idx_citas_fisioterapeuta');
            // sala_id no existe en todas las instalaciones
            $table->dropIndex('idx_citas_estado');
            $table->dropIndex('idx_citas_fecha_inicio');
            $table->dropIndex('idx_citas_fisio_fecha');
        });

        // Agenda - Horarios
        Schema::table('agenda_horarios_fisioterapeuta', function (Blueprint $table) {
            $table->dropIndex('idx_horarios_fisioterapeuta');
            $table->dropIndex('idx_horarios_dia');
        });

        // Clínico - Historias Clínicas
        Schema::table('clinico_historias_clinicas', function (Blueprint $table) {
            $table->dropIndex('idx_historias_persona');
            $table->dropIndex('idx_historias_fecha');
        });

        // Clínico - Sesiones
        Schema::table('clinico_sesiones', function (Blueprint $table) {
            $table->dropIndex('idx_sesiones_paciente');
            $table->dropIndex('idx_sesiones_fisioterapeuta');
            $table->dropIndex('idx_sesiones_servicio');
            $table->dropIndex('idx_sesiones_fecha');
        });

        // Facturación - Comprobantes
        Schema::table('facturacion_comprobantes', function (Blueprint $table) {
            $table->dropIndex('idx_comprobantes_cliente');
            $table->dropIndex('idx_comprobantes_tipo');
            $table->dropIndex('idx_comprobantes_serie');
            $table->dropIndex('idx_comprobantes_numero');
            $table->dropIndex('idx_comprobantes_fecha');
            $table->dropIndex('idx_comprobantes_estado_sunat');
            $table->dropIndex('idx_comprobantes_serie_numero');
        });

        // Facturación - Pagos
        Schema::table('facturacion_pagos', function (Blueprint $table) {
            $table->dropIndex('idx_pagos_comprobante');
            $table->dropIndex('idx_pagos_fecha');
            $table->dropIndex('idx_pagos_metodo');
        });

        // Inventario - Items
        Schema::table('inventario_items', function (Blueprint $table) {
            $table->dropIndex('idx_items_categoria');
            $table->dropIndex('idx_items_unidad');
            $table->dropIndex('idx_items_codigo');
            $table->dropIndex('idx_items_es_activo');
        });

        // Inventario - Kardex
        Schema::table('inventario_kardex', function (Blueprint $table) {
            $table->dropIndex('idx_kardex_item');
            $table->dropIndex('idx_kardex_fecha');
            $table->dropIndex('idx_kardex_item_fecha');
        });

        // Compras
        Schema::table('compras_compras', function (Blueprint $table) {
            $table->dropIndex('idx_compras_proveedor');
            $table->dropIndex('idx_compras_fecha');
        });

        // Seguridad - Audit Log
        Schema::table('seguridad_audit_log', function (Blueprint $table) {
            $table->dropIndex('idx_audit_usuario');
            $table->dropIndex('idx_audit_tabla');
            $table->dropIndex('idx_audit_operacion');
            $table->dropIndex('idx_audit_fecha');
            $table->dropIndex('idx_audit_tabla_fecha');
        });
    }
};
