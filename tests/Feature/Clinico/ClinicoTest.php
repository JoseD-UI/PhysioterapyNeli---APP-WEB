<?php

namespace Tests\Feature\Clinico;

use Tests\TestCase;
use Tests\Helpers\CreatesTestUsers;
use Tests\Helpers\CreatesTestData;
use App\Models\Clinico\HistoriaClinica;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClinicoTest extends TestCase
{
    use RefreshDatabase, CreatesTestUsers, CreatesTestData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            \Database\Seeders\PrincipalRolesSeeder::class,
            \Database\Seeders\PrincipalPermisosSeeder::class,
            \Database\Seeders\PrincipalRolPermisoSeeder::class,
        ]);
    }

    public function test_can_create_historia_clinica(): void
    {
        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        $paciente = $this->createPaciente();

        $response = $this->postJson('/api/v1/clinico/historias', [
            'persona_id' => $paciente->persona_id,
            'motivo_consulta' => 'Dolor cervical crónico',
            'antecedentes' => 'Trabajo sedentario',
            'alergias' => 'Ninguna conocida',
            'diagnostico_inicial' => 'Cervicalgia tensional',
            'recomendaciones' => 'Ejercicios de estiramiento',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('clinico_historias_clinicas', [
            'persona_id' => $paciente->persona_id,
            'motivo_consulta' => 'Dolor cervical crónico',
        ]);
    }

    public function test_can_update_historia_clinica(): void
    {
        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        $paciente = $this->createPaciente();
        $historia = $this->createHistoriaClinica($paciente);

        $response = $this->putJson("/api/v1/clinico/historias/{$historia->historia_id}", [
            'persona_id' => $paciente->persona_id, // Required by Request
            'motivo_consulta' => 'Dolor cervical agudo', // Update
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('clinico_historias_clinicas', [
            'historia_id' => $historia->historia_id,
            'motivo_consulta' => 'Dolor cervical agudo',
        ]);
    }
}
