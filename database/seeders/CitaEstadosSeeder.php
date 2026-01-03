<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitaEstadosSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['estado_id' => 1, 'codigo' => 'RESERVADA', 'nombre' => 'Reservada', 'descripcion' => 'Cita reservada'],
            ['estado_id' => 2, 'codigo' => 'CONFIRMADA', 'nombre' => 'Confirmada', 'descripcion' => 'Cita confirmada'],
            ['estado_id' => 3, 'codigo' => 'FINALIZADA', 'nombre' => 'Finalizada', 'descripcion' => 'Cita finalizada'],
            ['estado_id' => 4, 'codigo' => 'CANCELADA', 'nombre' => 'Cancelada', 'descripcion' => 'Cita cancelada'],
            ['estado_id' => 5, 'codigo' => 'NO_ASISTIO', 'nombre' => 'No Asistió', 'descripcion' => 'Paciente no asistió'],
        ];

        foreach ($estados as $estado) {
            DB::table('agenda_cita_estados')->updateOrInsert(
                ['estado_id' => $estado['estado_id']],
                $estado
            );
        }
    }
}
