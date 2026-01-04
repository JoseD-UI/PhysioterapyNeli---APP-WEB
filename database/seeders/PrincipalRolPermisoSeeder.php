<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Principal\Permiso;

class PrincipalRolPermisoSeeder extends Seeder
{
    public function run(): void
    {


        // ADMINISTRADOR → TODO
        $adminPermisos = Permiso::pluck('permiso_id');

        foreach ($adminPermisos as $permisoId) {
            DB::table('principal_rol_permiso')->insert([
                'rol_id' => 1,
                'permiso_id' => $permisoId,
                'asignado_en' => now(),
            ]);
        }

        // RECEPCIONISTA
        $this->asignar(4, [
            'agenda.citas.*',
            'facturacion.comprobantes.emitir',
            'facturacion.series.*',
        ]);

        // CONTADOR
        $this->asignar(3, [
            'facturacion.*',
            'contabilidad.libros.*',
            'sunat.*',
        ]);

        // ALMACENERO
        $this->asignar(6, [
            'inventario.items.*',
            'inventario.kardex.ver',
            'compras.*',
        ]);
    }

    private function asignar(int $rolId, array $codigos): void
    {
        $permisos = Permiso::whereIn('codigo', $codigos)->get();

        foreach ($permisos as $permiso) {
            DB::table('principal_rol_permiso')->insert([
                'rol_id' => $rolId,
                'permiso_id' => $permiso->permiso_id,
                'asignado_en' => now(),
            ]);
        }
    }
}
