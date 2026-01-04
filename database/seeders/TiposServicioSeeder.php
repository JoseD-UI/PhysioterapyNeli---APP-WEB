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
            [
                'tipo_id' => (string) Str::uuid(),
                'nombre' => 'Evaluación Fisioterapéutica',
                'descripcion' => 'Evaluación completa del estado físico, rango de movimiento y dolor. Incluye diagnóstico y plan de tratamiento.',
                'precio' => 80.00,
                'activo' => true
            ],
            [
                'tipo_id' => (string) Str::uuid(),
                'nombre' => 'Terapia Manual Ortopédica',
                'descripcion' => 'Técnicas manuales específicas para tratar dolor musculoesquelético y mejorar la movilidad articular.',
                'precio' => 120.00,
                'activo' => true
            ],
            [
                'tipo_id' => (string) Str::uuid(),
                'nombre' => 'Masaje Descontracturante',
                'descripcion' => 'Sesión intensiva para liberar tensión muscular acumulada, estrés y contracturas severas.',
                'precio' => 100.00,
                'activo' => true
            ],
            [
                'tipo_id' => (string) Str::uuid(),
                'nombre' => 'Electroterapia Avanzada',
                'descripcion' => 'Uso de corrientes terapéuticas (TENS, EMS) para alivio del dolor y fortalecimiento muscular.',
                'precio' => 60.00,
                'activo' => true
            ],
            [
                'tipo_id' => (string) Str::uuid(),
                'nombre' => 'Readaptación Deportiva',
                'descripcion' => 'Programa especializado para retorno seguro al deporte post-lesión. Incluye ejercicios funcionales.',
                'precio' => 150.00,
                'activo' => true
            ],
        ];

        DB::table('clinico_tipos_servicio')->insert($tipos);
    }
}

