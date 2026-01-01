<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UnidadesInventarioSeeder extends Seeder
{
    public function run()
    {
        $units = [
            ['unidad_id'=> (string) Str::uuid(),'codigo'=>'u','nombre'=>'Unidad','creado_en'=>now()],
            ['unidad_id'=> (string) Str::uuid(),'codigo'=>'ml','nombre'=>'Mililitro','creado_en'=>now()],
        ];
        DB::table('inventario_unidades')->insert($units);
    }
}
