<?php

namespace Tests\Feature\Principal;

use Tests\TestCase;
use Tests\Helpers\CreatesTestUsers;
use App\Models\Principal\Persona;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PrincipalTest extends TestCase
{
    use RefreshDatabase, CreatesTestUsers;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed([
            \Database\Seeders\PrincipalRolesSeeder::class,
            \Database\Seeders\PrincipalPermisosSeeder::class,
            \Database\Seeders\PrincipalRolPermisoSeeder::class,
        ]);
    }

    public function test_can_create_persona(): void
    {
        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/principal/personas', [
            'tipo_persona' => 'PACIENTE',
            'dni' => '12345678',
            'nombres' => 'Juan Carlos',
            'apellidos' => 'Pérez García',
            'email' => 'juan.perez@test.com',
            'celular' => '987654321',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('principal_personas', [
            'dni' => '12345678',
            'nombres' => 'Juan Carlos',
        ]);
    }

    public function test_can_list_personas(): void
    {
        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        // Crear personas
        for ($i = 0; $i < 3; $i++) {
            Persona::create([
                'persona_id' => \Illuminate\Support\Str::uuid(),
                'tipo_persona' => 'PACIENTE',
                'dni' => '1234567' . $i,
                'nombres' => "Persona $i",
                'apellidos' => "Test $i",
            ]);
        }

        $response = $this->getJson('/api/v1/principal/personas');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

}
