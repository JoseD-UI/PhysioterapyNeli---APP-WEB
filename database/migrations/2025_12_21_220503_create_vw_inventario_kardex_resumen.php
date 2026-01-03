<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite doesn't support CREATE OR REPLACE, so DROP first
            DB::statement("DROP VIEW IF EXISTS vw_inventario_kardex_resumen");
            
            DB::statement("
                CREATE VIEW vw_inventario_kardex_resumen AS
                SELECT
                    k.item_id,
                    k.saldo_cantidad        AS stock_actual,
                    k.costo_promedio_resultante AS costo_promedio,
                    k.saldo_valorizado,
                    k.fecha                 AS fecha_ultimo_movimiento,
                    k.metodo_valorizacion
                FROM inventario_kardex k
                INNER JOIN (
                    SELECT item_id, MAX(fecha) AS max_fecha
                    FROM inventario_kardex
                    GROUP BY item_id
                ) ult
                    ON ult.item_id = k.item_id
                   AND ult.max_fecha = k.fecha
            ");
        } else {
            // MySQL/PostgreSQL support CREATE OR REPLACE
            DB::statement("
                CREATE OR REPLACE VIEW vw_inventario_kardex_resumen AS
                SELECT
                    k.item_id,
                    k.saldo_cantidad        AS stock_actual,
                    k.costo_promedio_resultante AS costo_promedio,
                    k.saldo_valorizado,
                    k.fecha                 AS fecha_ultimo_movimiento,
                    k.metodo_valorizacion
                FROM inventario_kardex k
                INNER JOIN (
                    SELECT item_id, MAX(fecha) AS max_fecha
                    FROM inventario_kardex
                    GROUP BY item_id
                ) ult
                    ON ult.item_id = k.item_id
                   AND ult.max_fecha = k.fecha
            ");
        }
    }

    public function down(): void
    {
        DB::statement("
            DROP VIEW IF EXISTS vw_inventario_kardex_resumen
        ");
    }
};

