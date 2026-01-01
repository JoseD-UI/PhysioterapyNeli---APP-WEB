<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TiposServicioSeeder extends Seeder
{
    public function run()
    {
        $tipos = [
            ['tipo_id'=> (string) Str::uuid(),'nombre'=>'Terapia manual','descripcion'=>'Terapia manual general','activo'=>true],
            ['tipo_id'=> (string) Str::uuid(),'nombre'=>'Masaje descontracturante','descripcion'=>'Masaje para contracturas','activo'=>true],
            ['tipo_id'=> (string) Str::uuid(),'nombre'=>'Electroterapia','descripcion'=>'TENS, EMS, etc.','activo'=>true],
        ];

        DB::table('clinico_tipos_servicio')->insert($tipos);
    }
}

