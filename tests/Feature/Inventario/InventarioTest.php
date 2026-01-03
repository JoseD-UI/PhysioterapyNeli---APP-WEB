<?php

namespace Tests\Feature\Inventario;

use Tests\TestCase;
use Tests\Helpers\CreatesTestUsers;
use App\Models\Inventario\InventarioItem;
use App\Models\Inventario\InventarioKardex;
use App\Models\Inventario\Categoria;
use App\Models\Inventario\Unidad;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventarioTest extends TestCase
{
    use RefreshDatabase, CreatesTestUsers;

    private $categoria;
    private $unidad;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->seed([
            \Database\Seeders\PrincipalRolesSeeder::class,
            \Database\Seeders\PrincipalPermisosSeeder::class,
            \Database\Seeders\PrincipalRolPermisoSeeder::class,
        ]);
        
        InventarioItem::unguard();

        // Create dependencies
        $this->categoria = Categoria::create([
            'categoria_id' => \Illuminate\Support\Str::uuid(),
            'nombre' => 'General',
            'activo' => true
        ]);

        $this->unidad = Unidad::create([
            'unidad_id' => \Illuminate\Support\Str::uuid(),
            'nombre' => 'Unidad',
            'codigo' => 'UND',
        ]);
    }

    public function test_can_create_item(): void
    {
        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/inventario/items', [
            'nombre' => 'Electrodo Adhesivo',
            'descripcion' => 'Electrodo para electroterapia',
            'tipo' => 'PRODUCTO',
            'categoria_id' => $this->categoria->categoria_id,
            'unidad_medida_id' => $this->unidad->unidad_id,
            'stock_minimo' => 10,
            'precio_unitario' => 8.00,
            'es_activo' => true,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('inventario_items', [
            'nombre' => 'Electrodo Adhesivo',
        ]);
    }

    public function test_can_list_items(): void
    {
        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        // Crear items
        for ($i = 0; $i < 3; $i++) {
            InventarioItem::create([
                'item_id' => \Illuminate\Support\Str::uuid(),
                'nombre' => "Item Test $i",
                'tipo' => 'PRODUCTO',
                'categoria_id' => $this->categoria->categoria_id,
                'unidad_medida_id' => $this->unidad->unidad_id,
                'stock_actual' => 0,
                'precio_unitario' => 10.00,
                'es_activo' => true,
            ]);
        }

        $response = $this->getJson('/api/v1/inventario/items');

        $response->assertStatus(200);
        // dump($response->json()); 
        $response->assertJsonCount(3);
    }

    public function test_can_update_item_details(): void
    {
        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        $item = InventarioItem::create([
            'item_id' => \Illuminate\Support\Str::uuid(),
            'nombre' => 'Item Test',
            'tipo' => 'PRODUCTO',
            'categoria_id' => $this->categoria->categoria_id,
            'unidad_medida_id' => $this->unidad->unidad_id,
            'stock_actual' => 10, // Stock won't change via update
            'precio_unitario' => 15.00,
            'es_activo' => true,
        ]);

        $response = $this->putJson("/api/v1/inventario/items/{$item->item_id}", [
            'precio_unitario' => 25.00, // Update price
            'nombre' => 'Item Test Updated',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('inventario_items', [
            'item_id' => $item->item_id,
            'precio_unitario' => 25.00,
            'nombre' => 'Item Test Updated',
        ]);
    }

    public function test_can_query_kardex(): void
    {
        $user = $this->createAdminUser();
        Sanctum::actingAs($user);

        $item = InventarioItem::create([
            'item_id' => \Illuminate\Support\Str::uuid(),
            'nombre' => 'Item para Kardex',
            'tipo' => 'PRODUCTO',
            'categoria_id' => $this->categoria->categoria_id,
            'unidad_medida_id' => $this->unidad->unidad_id,
            'stock_actual' => 50,
            'precio_unitario' => 20.00,
            'es_activo' => true,
        ]);

        // Simular movimiento en kardex
        InventarioKardex::create([
            'kardex_id' => \Illuminate\Support\Str::uuid(),
            'item_id' => $item->item_id,
            'tipo_movimiento' => 'entrada',
            'cantidad' => 50,
            'saldo' => 50,
            'saldo_cantidad' => 50,
            'saldo_valorizado' => 1000.00,
            'costo_unitario' => 20.00,
            'costo_promedio_resultante' => 20.00,
            'valor_total' => 1000.00,
            'fecha' => now(),
        ]);

        $response = $this->getJson("/api/v1/inventario/kardex/{$item->item_id}");

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }
}
