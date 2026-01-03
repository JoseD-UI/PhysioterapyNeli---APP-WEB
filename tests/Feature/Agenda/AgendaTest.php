<?php

namespace Tests\Feature\Agenda;

use Tests\TestCase;
use Tests\Helpers\CreatesTestUsers;
use Tests\Helpers\CreatesTestData;
use App\Models\Agenda\Cita;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AgendaTest extends TestCase
{
    use RefreshDatabase, CreatesTestUsers, CreatesTestData;



    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Config::set('appointments.default_initial_state', 'RESERVADA');
        \Illuminate\Support\Facades\Config::set('appointments.block_statuses', ['RESERVADA','CONFIRMADA']);
        $this->seed([
            \Database\Seeders\PrincipalRolesSeeder::class,
            \Database\Seeders\PrincipalPermisosSeeder::class,
            \Database\Seeders\PrincipalRolPermisoSeeder::class,
            \Database\Seeders\CitaEstadosSeeder::class,
        ]);
        
        // Ensure Servicio and Cita timestamps handling if needed
    }

    public function test_can_create_cita(): void
    {
        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        $paciente = $this->createPaciente();
        $fisio = $this->createFisioterapeuta();
        
        // Ensure status exists matches config
        \App\Models\Agenda\CitaEstado::firstOrCreate(
            ['codigo' => 'RESERVADA'],
            ['nombre' => 'Reservada', 'estado_id' => 1, 'activo' => true]
        );

        // Create schedule for fisio (Monday)
        \App\Models\Agenda\HorarioFisioterapeuta::create([
            'fisioterapeuta_id' => $fisio->persona_id,
            'dia_semana' => 1, // Monday
            'hora_inicio' => '08:00:00',
            'hora_fin' => '20:00:00',
            'activo' => true
        ]);

        $servicio = $this->createServicio();
        // $estadoId = $this->getCitaEstadoId('RESERVADA'); // Removed to use default

        dump(\App\Models\Agenda\Cita::all()->toArray());

        $response = $this->postJson('/api/v1/agenda/citas', [
            'paciente_id' => $paciente->persona_id,
            'fisioterapeuta_id' => $fisio->persona_id,
            'servicio_id' => $servicio->servicio_id,
            'fecha_inicio' => now()->next('Monday')->setHour(14)->setMinute(0)->toDateTimeString(),
            'fecha_fin' => now()->next('Monday')->setHour(15)->setMinute(0)->toDateTimeString(),
        ]);

        if ($response->status() !== 201) {
            dump($response->json());
        }
        $response->assertStatus(201);
        $this->assertDatabaseHas('agenda_citas', [
            'paciente_id' => $paciente->persona_id,
            'fisioterapeuta_id' => $fisio->persona_id,
            'servicio_id' => $servicio->servicio_id,
        ]);
    }

    public function test_validates_cita_overlap(): void
    {
        $user = $this->createAdminUser();
        Sanctum::actingAs($user);
        
        $paciente = $this->createPaciente();
        $fisio = $this->createFisioterapeuta();

        // Create schedule for fisio (Monday)
        \App\Models\Agenda\HorarioFisioterapeuta::create([
            'fisioterapeuta_id' => $fisio->persona_id,
            'dia_semana' => 1, // Monday
            'hora_inicio' => '08:00:00',
            'hora_fin' => '20:00:00',
            'activo' => true
        ]);
        
        // Create initial appointment
        $this->postJson('/api/v1/agenda/citas', [
            'paciente_id' => $paciente->persona_id,
            'fisioterapeuta_id' => $fisio->persona_id,
            'servicio_id' => $this->createServicio()->servicio_id,
            'fecha_inicio' => now()->next('Monday')->setHour(10)->setMinute(0)->toDateTimeString(),
            'fecha_fin' => now()->next('Monday')->setHour(11)->setMinute(0)->toDateTimeString(),
        ])->assertStatus(201); // Ensure this succeeds

        $response = $this->postJson('/api/v1/agenda/citas', [
            'paciente_id' => $paciente->persona_id,
            'fisioterapeuta_id' => $fisio->persona_id, // Same fisio
            'servicio_id' => $this->createServicio()->servicio_id,
            'fecha_inicio' => now()->next('Monday')->setHour(10)->setMinute(30)->toDateTimeString(), // Overlaps
            'fecha_fin' => now()->next('Monday')->setHour(11)->setMinute(30)->toDateTimeString(),
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['fecha_inicio']); 
    }
}
