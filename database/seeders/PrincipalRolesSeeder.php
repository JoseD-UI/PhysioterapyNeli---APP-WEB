<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Principal\Rol;
use Illuminate\Support\Facades\DB;

class PrincipalRolesSeeder extends Seeder
{
    public function run(): void
    {


        $roles = [
            ['rol_id' => 1,  'nombre' => 'ADMINISTRADOR',        'descripcion' => 'Control total del sistema'],
            ['rol_id' => 2,  'nombre' => 'GERENTE',              'descripcion' => 'Gestión y reportes'],
            ['rol_id' => 3,  'nombre' => 'CONTADOR',             'descripcion' => 'Facturación y contabilidad'],
            ['rol_id' => 4,  'nombre' => 'RECEPCIONISTA',        'descripcion' => 'Agenda y pagos'],
            ['rol_id' => 5,  'nombre' => 'FISIOTERAPEUTA',       'descripcion' => 'Atención clínica'],
            ['rol_id' => 6,  'nombre' => 'ALMACENERO',           'descripcion' => 'Inventario'],
            ['rol_id' => 7,  'nombre' => 'COMPRAS',              'descripcion' => 'Compras y proveedores'],
            ['rol_id' => 8,  'nombre' => 'AUDITOR',              'descripcion' => 'Lectura y control'],
            ['rol_id' => 9,  'nombre' => 'SOPORTE',              'descripcion' => 'Soporte operativo'],
            ['rol_id' => 10, 'nombre' => 'USUARIO_LIMITADO',     'descripcion' => 'Acceso mínimo'],
        ];

        foreach ($roles as $rol) {
            Rol::create($rol);
        }
    }
}
