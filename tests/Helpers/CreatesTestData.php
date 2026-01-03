<?php

namespace Tests\Helpers;

use App\Models\Principal\Persona;
use App\Models\Agenda\Cita;
use App\Models\Agenda\CitaEstado;
use App\Models\Clinico\HistoriaClinica;
use App\Models\Clinico\Sesion;
use App\Models\Clinico\Servicio;
use App\Models\Compras\Compra;
use App\Models\Compras\Proveedor;
use App\Models\Inventario\InventarioItem;
use Illuminate\Support\Facades\DB;

trait CreatesTestData
{
    protected function createPaciente(array $overrides = []): Persona
    {
        return Persona::create(array_merge([
            'persona_id' => \Illuminate\Support\Str::uuid(),
            'tipo_persona' => 'PACIENTE',
            'dni' => (string)rand(10000000, 99999999),
            'nombres' => 'Paciente',
            'apellidos' => 'Test ' . rand(1, 999),
            'email' => 'paciente' . rand(1000, 9999) . '@test.com',
            'celular' => '9' . rand(10000000, 99999999),
        ], $overrides));
    }

    protected function createFisioterapeuta(array $overrides = []): Persona
    {
        return Persona::create(array_merge([
            'persona_id' => \Illuminate\Support\Str::uuid(),
            'tipo_persona' => 'FISIOTERAPEUTA',
            'dni' => (string)rand(10000000, 99999999),
            'nombres' => 'Fisioterapeuta',
            'apellidos' => 'Test ' . rand(1, 999),
            'email' => 'fisio' . rand(1000, 9999) . '@test.com',
        ], $overrides));
    }

    protected function createServicio(array $overrides = []): Servicio
    {
        return Servicio::create(array_merge([
            'servicio_id' => \Illuminate\Support\Str::uuid(),
            'nombre' => 'Terapia Test ' . rand(1, 999),
            'descripcion' => 'Descripción de prueba',
            'duracion_minutos' => 60,
            'precio' => 80.00,
            'activo' => true,
        ], $overrides));
    }

    protected function createCita(Persona $paciente, Persona $fisioterapeuta, array $overrides = []): Cita
    {
        // Ensure initial state exists
        $estado = CitaEstado::firstOrCreate(
            ['codigo' => 'RES'],
            ['nombre' => 'Reservada', 'color' => '#3B82F6', 'activo' => true]
        );

        return Cita::create(array_merge([
            'cita_id' => \Illuminate\Support\Str::uuid(),
            'paciente_id' => $paciente->persona_id,
            'fisioterapeuta_id' => $fisioterapeuta->persona_id,
            'servicio_id' => $this->createServicio()->servicio_id,
            'fecha_inicio' => now()->addDay()->setHour(10)->setMinute(0),
            'fecha_fin' => now()->addDay()->setHour(11)->setMinute(0),
            'estado_id' => $estado->estado_id,
        ], $overrides));
    }

    protected function getCitaEstadoId(string $codigo): string
    {
        return CitaEstado::where('codigo', $codigo)->value('estado_id')
            ?? CitaEstado::create(['codigo' => $codigo, 'nombre' => $codigo, 'color' => '#000000', 'activo' => true])->estado_id;
    }

    protected function createHistoriaClinica(Persona $paciente, array $overrides = []): HistoriaClinica
    {
        return HistoriaClinica::create(array_merge([
            'historia_id' => \Illuminate\Support\Str::uuid(),
            'persona_id' => $paciente->persona_id,
            'motivo_consulta' => 'Dolor de espalda',
            'diagnostico_inicial' => 'Lumbalgia',
        ], $overrides));
    }

    protected function createProveedor(array $overrides = []): Proveedor
    {
        return Proveedor::create(array_merge([
            'proveedor_id' => \Illuminate\Support\Str::uuid(),
            'nombre' => 'Proveedor Test ' . rand(100, 999),
            'ruc' => '20' . rand(100000000, 999999999),
            'email' => 'proveedor@test.com',
        ], $overrides));
    }

    protected function createCompra(Proveedor $proveedor, array $overrides = []): Compra
    {
        return Compra::create(array_merge([
            'compra_id' => \Illuminate\Support\Str::uuid(),
            'proveedor_id' => $proveedor->proveedor_id,
            'fecha_compra' => now(),
            'subtotal' => 100.00,
            'igv' => 18.00,
            'total' => 118.00,
            'estado' => 'finalizado',
        ], $overrides));
    }
}
