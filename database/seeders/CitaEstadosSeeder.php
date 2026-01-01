<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CitaEstadosSeeder extends Seeder
{
    public function run()
    {
        $estados = [
            ['estado_id'=>(string) Str::uuid(),'codigo'=>'reservado','nombre'=>'Reservado','activo'=>true,'creado_en'=>now()],
            ['estado_id'=>(string) Str::uuid(),'codigo'=>'confirmado','nombre'=>'Confirmado','activo'=>true,'creado_en'=>now()],
            ['estado_id'=>(string) Str::uuid(),'codigo'=>'atendido','nombre'=>'Atendido','activo'=>true,'creado_en'=>now()],
            ['estado_id'=>(string) Str::uuid(),'codigo'=>'cancelado','nombre'=>'Cancelado','activo'=>true,'creado_en'=>now()],
            ['estado_id'=>(string) Str::uuid(),'codigo'=>'no_asistio','nombre'=>'No asistió','activo'=>true,'creado_en'=>now()],
        ];

        DB::table('agenda_cita_estados')->insert($estados);
    }
}
