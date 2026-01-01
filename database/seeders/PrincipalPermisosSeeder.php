<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Principal\Permiso;

class PrincipalPermisosSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [

            // PRINCIPAL
            ['codigo' => 'principal.personas.ver',     'nombre' => 'Ver personas'],
            ['codigo' => 'principal.personas.crear',   'nombre' => 'Crear personas'],
            ['codigo' => 'principal.usuarios.*',       'nombre' => 'Administrar usuarios'],
            ['codigo' => 'principal.permisos.*',       'nombre' => 'Administrar permisos'],

            // AGENDA
            ['codigo' => 'agenda.citas.*',              'nombre' => 'Gestionar citas'],
            ['codigo' => 'agenda.horarios.*',           'nombre' => 'Gestionar horarios'],

            // CLÍNICO
            ['codigo' => 'clinico.servicios.*',         'nombre' => 'Gestionar servicios clínicos'],

            // INVENTARIO
            ['codigo' => 'inventario.items.*',          'nombre' => 'Gestionar items'],
            ['codigo' => 'inventario.kardex.ver',       'nombre' => 'Ver kardex'],

            // COMPRAS
            ['codigo' => 'compras.*',                   'nombre' => 'Gestión de compras'],

            // FACTURACIÓN
            ['codigo' => 'facturacion.comprobantes.emitir', 'nombre' => 'Emitir comprobantes'],
            ['codigo' => 'facturacion.comprobantes.anular', 'nombre' => 'Anular comprobantes'],
            ['codigo' => 'facturacion.series.*',            'nombre' => 'Series de comprobantes'],

            // SUNAT
            ['codigo' => 'sunat.enviar',                'nombre' => 'Enviar a SUNAT'],
            ['codigo' => 'sunat.estado',                'nombre' => 'Consultar estado SUNAT'],

            // CONTABILIDAD
            ['codigo' => 'contabilidad.libros.*',       'nombre' => 'Libros contables'],

            // REPORTES
            ['codigo' => 'reportes.*',                  'nombre' => 'Reportes gerenciales'],
        ];

        foreach ($permisos as $p) {
            Permiso::firstOrCreate(
                ['codigo' => $p['codigo']],
                [
                    'nombre' => $p['nombre'],
                    'descripcion' => $p['nombre']
                ]
            );
        }
    }
}
