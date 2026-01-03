# Testing Guide

## Estructura de Tests

El proyecto utiliza PHPUnit para testing. Los tests se dividen en:

### Feature Tests

Tests de integración que prueban flujos completos incluyendo base de datos.

**Ubicación**: `tests/Feature/`

### Unit Tests

Tests aislados de clases y métodos específicos.

**Ubicación**: `tests/Unit/`

## Configuración

### Instalar PHPUnit

```bash
composer require --dev phpunit/phpunit
```

### Base de Datos de Tests

Crear `.env.testing`:

```env
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

## Ejemplos de Tests

### Test de Autenticación

**Archivo**: `tests/Feature/Auth/LoginTest.php`

```php
<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type'
            ]);
    }

    public function test_user_cannot_login_with_incorrect_password()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
```

### Test de Permisos

**Archivo**: `tests/Feature/Permissions/PermisoMiddlewareTest.php`

```php
<?php

namespace Tests\Feature\Permissions;

use Tests\TestCase;
use App\Models\User;
use App\Models\Principal\Usuario;
use App\Models\Principal\Rol;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermisoMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_permission_can_access_route()
    {
        $user = User::factory()->create();

        // Crear usuario principal con permiso
        $rol = Rol::factory()->create();
        $usuario = Usuario::factory()->create([
            'user_id' => $user->id,
            'rol_id' => $rol->id
        ]);

        // Simular que tiene permiso
        $rol->permisos()->attach(...);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/principal/personas');

        $response->assertStatus(200);
    }

    public function test_user_without_permission_cannot_access_route()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/principal/personas');

        $response->assertStatus(403);
    }
}
```

### Test de Comprobantes

**Archivo**: `tests/Feature/Facturacion/ComprobanteTest.php`

```php
<?php

namespace Tests\Feature\Facturacion;

use Tests\TestCase;
use App\Models\User;
use App\Services\Facturacion\ComprobanteService;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ComprobanteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // Seeders
    }

    public function test_can_emit_factura()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/facturacion/comprobantes', [
            'tipo_comprobante' => '01',
            'cliente_id' => 'uuid-cliente',
            'ruc_cliente' => '20123456789',
            'razon_social' => 'EMPRESA SAC',
            'detalles' => [
                [
                    'descripcion' => 'Producto Test',
                    'cantidad' => 2,
                    'precio_unitario' => 50.00
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'tipo_comprobante',
                'serie',
                'correlativo',
                'total',
                'detalles'
            ]);
    }

    public function test_factura_requires_ruc()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/facturacion/comprobantes', [
            'tipo_comprobante' => '01',
            // Sin RUC
            'detalles' => [...]
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['ruc_cliente']);
    }
}
```

## Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test --filter=LoginTest

# Con coverage
php artisan test --coverage

# Solo feature tests
php artisan test tests/Feature

# Verbose
php artisan test -v
```

## Best Practices

1. ✅ Usar `RefreshDatabase` para tests con BD
2. ✅ Usar factories para crear datos
3. ✅ Un assert por test (idealmente)
4. ✅ Nombres descriptivos: `test_user_can_login_with_correct_credentials`
5. ✅ Arrange, Act, Assert (AAA pattern)
6. ✅ Mockear servicios externos
7. ✅ Tests independientes (no dependencias entre tests)

## Factories

Crear factories para facilitar tests:

```php
// database/factories/UserFactory.php
User::factory()->create([
    'email' => 'test@example.com'
]);

// Multiple
User::factory()->count(10)->create();
```

## Próximos Pasos

-   [ ] Crear factories para todos los modelos
-   [ ] Implementar tests de autenticación
-   [ ] Implementar tests de permisos
-   [ ] Implementar tests de facturación
-   [ ] Implementar tests de agenda
-   [ ] Configurar CI/CD para ejecutar tests automáticamente

---

**Ver también**:

-   [Estándares de Código](coding-standards.md)
-   [CI/CD](../deployment/ci-cd.md)
