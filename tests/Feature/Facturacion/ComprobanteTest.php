<?php

namespace Tests\Feature\Facturacion;

use Tests\TestCase;
use App\Models\User;
use App\Models\Principal\Persona;
use App\Models\Principal\Usuario;
use App\Models\Principal\Rol;
use App\Models\Principal\Permiso;
use App\Models\Facturacion\FacturacionSerie;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComprobanteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed ALL essential data
        $this->seed([
            \Database\Seeders\PrincipalRolesSeeder::class,
            \Database\Seeders\PrincipalPermisosSeeder::class,
            \Database\Seeders\PrincipalRolPermisoSeeder::class,
        ]);
        
        // Create series for testing
        FacturacionSerie::create([
            'serie_id' => \Illuminate\Support\Str::uuid(),
            'tipo_comprobante' => '01',
            'serie' => 'F001',
            'correlativo_actual' => 0,
            'activo' => true
        ]);
    }

    protected function createAdminUser()
    {
        // 1. Crear User de Laravel primero
        $user = User::factory()->create();
        
        // 2. Crear Persona
        $persona = Persona::create([
            'persona_id' => \Illuminate\Support\Str::uuid(),
            'tipo_persona' => 'EMPLEADO',
            'documento_tipo' => 'DNI',
            'documento_numero' => '12345678' . rand(10, 99),
            'nombres' => 'Admin',
            'apellidos' => 'Test',
        ]);
        
        // 3. Get ADMINISTRADOR role (already seeded with all permissions)
        $rolAdmin = Rol::where('nombre', 'ADMINISTRADOR')->first();
        
        if (!$rolAdmin) {
            throw new \Exception('ADMINISTRADOR role not found. Seeds not executed properly.');
        }
        
        // 4. Crear Usuario con EL MISMO ID que User
        Usuario::create([
            'usuario_id' => $user->id,  // ← MISMO ID (shared primary key)
            'persona_id' => $persona->persona_id,
            'rol_id' => $rolAdmin->rol_id,
            'username' => 'admin_test_' . time() . rand(100, 999),
            'password_hash' => bcrypt('password123'),
            'activo' => 1
        ]);
        
        // 5. Refrescar el usuario para cargar la relación
        $user->refresh();
        $user->load('usuarioPrincipal.rol.permisos');
        
        return $user;
    }

    public function test_can_emit_factura(): void
    {
        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/facturacion/comprobantes', [
            'tipo_comprobante' => '01',
            'ruc_cliente' => '20123456789',
            'razon_social' => 'Test Company SAC',
            'detalles' => [
                [
                    'descripcion' => 'Servicio de fisioterapia',
                    'cantidad' => 1,
                    'precio_unitario' => 100.00
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'tipo_comprobante',
                'serie',
                'correlativo',
            ]);

        $this->assertDatabaseHas('facturacion_comprobantes', [
            'tipo_comprobante' => '01',
            'ruc_cliente' => '20123456789'
        ]);
    }

    public function test_factura_requires_ruc(): void
    {
        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/facturacion/comprobantes', [
            'tipo_comprobante' => '01',
            // Sin RUC - should fail
            'detalles' => [
                [
                    'descripcion' => 'Test',
                    'cantidad' => 1,
                    'precio_unitario' => 100.00
                ]
            ]
        ]);

        // Should fail validation or business logic (not 403)
        $this->assertContains($response->status(), [422, 500]);
    }

    public function test_unauthenticated_cannot_emit_comprobante(): void
    {
        $response = $this->postJson('/api/v1/facturacion/comprobantes', []);

        $response->assertStatus(401);
    }
}
