# Arquitectura General - API Fisioterapia

## Visión General

Sistema backend construido con **Laravel 12** siguiendo arquitectura en capas con separación clara de responsabilidades.

## Capas de la Aplicación

```
┌─────────────────────────────────────────────┐
│           HTTP Layer (Routes/Controllers)   │  ← Request/Response
├─────────────────────────────────────────────┤
│           Services Layer                    │  ← Business Logic
├─────────────────────────────────────────────┤
│           Repositories Layer                │  ← Data Access
├─────────────────────────────────────────────┤
│           Models Layer (Eloquent)          │  ← ORM
├─────────────────────────────────────────────┤
│           Database (MySQL/SQLite)          │  ← Persistence
└─────────────────────────────────────────────┘
```

## Flujo de Request

1. **Cliente** → Envía request HTTP con token Sanctum
2. **Middleware** → Valida autenticación (`auth:sanctum`) y permisos (`permiso`)
3. **Controller** → Valida request (FormRequest), delega a Service
4. **Service** → Lógica de negocio, coordina Repositories
5. **Repository** → Acceso a datos, queries optimizadas
6. **Model** → Eloquent ORM, relaciones
7. **Response** → API Resource transforma y devuelve JSON

## Patr ones de Diseño Implementados

### Repository Pattern

Abstracción de acceso a datos, facilita testing y cambios de BD.

**Ejemplo**:

```php
FacturacionRepository → Queries de facturación
PrincipalRepository → Queries de usuarios/personas
```

### Service Pattern

Encapsula lógica de negocio compleja, coordina entre múltiples repositories.

**Ejemplo**:

```php
ComprobanteService → Emite facturas, valida stock, actualiza kardex
AgendaService → Valida horarios, evita solapamientos
```

### Observer Pattern

Auditoría automática de cambios en modelos críticos.

**Ejemplo**:

```php
AuditObserver → Registra INSERT/UPDATE/DELETE automáticamente
```

### Resource Pattern

Transformación consistente de modelos a JSON para respuestas API.

## Módulos del Sistema

| Módulo             | Propósito                           |
| ------------------ | ----------------------------------- |
| **Authentication** | Login, registro, tokens Sanctum     |
| **Principal**      | Personas, usuarios, roles, permisos |
| **Agenda**         | Citas, horarios, validaciones       |
| **Clínico**        | Historias, sesiones, servicios      |
| **Facturación**    | Comprobantes, pagos, SUNAT          |
| **Inventario**     | Items, kardex, activos              |
| **Compras**        | Compras, proveedores                |
| **Seguridad**      | Auditoría, logs                     |

## Tecnologías Clave

-   **Framework**: Laravel 12
-   **Autenticación**: Laravel Sanctum (token-based)
-   **Base de Datos**: SQLite (dev), MySQL/PostgreSQL (prod)
-   **ORM**: Eloquent
-   **Validación**: Form Requests
-   **Transformación**: API Resources
-   **Testing**: PHPUnit (pendiente implementación completa)

## Seguridad

-   ✅ Tokens Sanctum para autenticación
-   ✅ Middleware de permisos granular
-   ✅ Validación centralizada de requests
-   ✅ Auditoría automática de operaciones críticas
-   ✅ Manejo centralizado de excepciones
-   ✅ Protección CSRF (para web)
-   ⚠️ Rate limiting (pendiente configurar)
-   ⚠️ CORS (configurar según necesidad)

## Escalabilidad

El sistema está preparado para:

-   ✅ Múltiples bases de datos (MySQL, PostgreSQL)
-   ✅ Caching (Redis/Memcached)
-   ✅ Queues para tareas pesadas
-   ✅ Horizontal scaling con load balancers
-   ✅ Microservicios (separación por módulo posible)

## Dependencias Principales

```json
{
    "laravel/framework": "^12.0",
    "laravel/sanctum": "^4.2",
    "doctrine/dbal": "^4.4"
}
```

---

**Ver también**:

-   [Patrones de Diseño](patterns.md)
-   [Diagrama de BD](database-diagram.md)
-   [Seguridad](security.md)
