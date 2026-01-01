<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoriasInventarioSeeder extends Seeder
{
    public function run()
    {
        $cats = [
            ['categoria_id'=> (string) Str::uuid(),'nombre'=>'Insumos','descripcion'=>'Consumibles','activo'=>true,'creado_en'=>now()],
            ['categoria_id'=> (string) Str::uuid(),'nombre'=>'Equipos','descripcion'=>'Equipos y aparatos','activo'=>true,'creado_en'=>now()],
        ];
        DB::table('inventario_categorias')->insert($cats);
    }
}
