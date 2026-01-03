# API - Autenticación

## Descripción

Endpoints públicos y protegidos para autenticación de usuarios usando Laravel Sanctum.

## Rutas Públicas

### Registro de Usuario

```http
POST /api/v1/auth/register
Content-Type: application/json
```

**Request**:

```json
{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123",
    "password_confirmation": "password123",

    "nombres": "Juan",
    "apellidos": "Pérez",
    "dni": "12345678",
    "telefono": "999888777"
}
```

**Response** (201):

```json
{
    "message": "Usuario registrado exitosamente",
    "user": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@example.com"
    },
    "access_token": "1|abc123xyz...",
    "token_type": "Bearer"
}
```

### Login

```http
POST /api/v1/auth/login
Content-Type: application/json
```

**Request**:

```json
{
    "email": "juan@example.com",
    "password": "password123"
}
```

**Response** (200):

```json
{
    "message": "Login exitoso",
    "user": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@example.com",
        "usuario_principal": {
            "rol": "FISIOTERAPEUTA",
            "persona": {
                "nombres": "Juan",
                "apellidos": "Pérez"
            }
        }
    },
    "access_token": "2|xyz789abc...",
    "token_type": "Bearer"
}
```

**Error** (422):

```json
{
    "message": "Error de validación",
    "errors": {
        "email": ["Las credenciales son incorrectas."]
    },
    "codigo": "VALIDATION_ERROR"
}
```

## Rutas Protegidas

### Logout

```http
POST /api/v1/auth/logout
Authorization: Bearer {token}
```

**Response** (200):

```json
{
    "message": "Sesión cerrada exitosamente"
}
```

### Usuario Actual

```http
GET /api/v1/auth/me
Authorization: Bearer {token}
```

**Response** (200):

```json
{
    "user": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@example.com",
        "email_verified_at": null,
        "created_at": "2026-01-01T10:00:00.000000Z",
        "usuario_principal": {
            "usuario_id": "uuid",
            "activo": 1,
            "rol": {
                "id": 2,
                "nombre": "FISIOTERAPEUTA",
                "descripcion": "Fisioterapeuta del centro"
            },
            "persona": {
                "persona_id": "uuid",
                "tipo_persona": "FISIOTERAPEUTA",
                "nombres": "Juan",
                "apellidos": "Pérez",
                "dni": "12345678",
                "telefono": "999888777"
            },
            "permisos": [
                "agenda.citas.ver",
                "agenda.citas.crear",
                "clinico.sesiones.crear"
            ]
        }
    }
}
```

## Uso del Token

Incluir en header `Authorization`:

```bash
curl -H "Authorization: Bearer 2|xyz789abc..." \
  http://localhost:8000/api/v1/principal/personas
```

## Códigos de Error

| Código               | Status | Descripción                 |
| -------------------- | ------ | --------------------------- |
| `VALIDATION_ERROR`   | 422    | Error de validación         |
| `UNAUTHENTICATED`    | 401    | No autenticado              |
| `USUARIO_INACTIVO`   | 403    | Usuario desactivado         |
| `USUARIO_SIN_PERFIL` | 403    | Usuario sin perfil asignado |

## Seguridad

-   ✅ Tokens almacenados en tabla `personal_access_tokens`
-   ✅ Password hasheado con bcrypt
-   ✅ Validate email único
-   ✅ Rate limiting (configurar en prod)
-   ⚠️ Implementar verificación de email (opcional)
-   ⚠️ Implementar reset de password (opcional)

## Ejemplo Completo (Bash)

```bash
# 1. Login
TOKEN=$(curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@test.com","password":"password"}' \
  | jq -r '.access_token')

# 2. Usar token
curl -H "Authorization: Bearer $TOKEN" \
  http://localhost:8000/api/v1/auth/me

# 3. Logout
curl -X POST http://localhost:8000/api/v1/auth/logout \
  -H "Authorization: Bearer $TOKEN"
```

---

**Ver también**:

-   [Módulo de Autenticación](../modules/authentication.md)
-   [Códigos de Error](error-codes.md)
