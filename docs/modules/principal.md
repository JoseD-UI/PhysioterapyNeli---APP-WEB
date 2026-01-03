# Módulo Principal

## Descripción

El módulo Principal gestiona las entidades fundamentales del sistema: personas, usuarios, roles y permisos. Es el núcleo de la arquitectura de seguridad y autenticación.

## Modelos

### Persona

Entidad base que representa cualquier individuo en el sistema.

**Primary Key**: UUID (`persona_id`)

**Campos principales**:

-   `tipo_persona`: PACIENTE, FISIOTERAPEUTA, PROVEEDOR, EMPLEADO
-   `documento_tipo`: DNI, CE, PASAPORTE
-   `documento_numero`: Número de documento único
-   `nombres`, `apellidos`
-   `fecha_nacimiento`, `genero`
-   `email`, `telefono`, `celular`
-   `direccion`, `distrito`, `provincia`, `departamento`

**Relaciones**:

-   `usuarios` → Usuario (hasMany)
-   `citas` → Cita (hasMany)

### Usuario

Usuario del sistema vinculado a una Persona.

**Primary Key**: `usuario_id` (unsignedBigInteger) - **Sincronizado con users.id**

**Campos**:

-   `usuario_id` (PK, FK a users.id) - Mismo valor que el ID del User de Laravel
-   `persona_id` (FK)
-   `rol_id` (FK)
-   `username`
-   `activo`: boolean

**Relaciones**:

-   `persona` → Persona (belongsTo)
-   `user` → User (belongsTo) - Relación 1:1 con clave compartida
-   `rol` → Rol (belongsTo)

### Rol

Roles del sistema con permisos asignados.

**Roles predefinidos**:

1. ADMINISTRADOR - Todos los permisos
2. PACIENTE - Permisos básicos
3. CONTADOR - Facturación y contabilidad
4. RECEPCIONISTA - Agenda y comprobantes
5. FISIOTERAPEUTA - Clínico y sesiones
6. ALMACENERO - Inventario y compras

**Relaciones**:

-   `permisos` → Permiso (belongsToMany)
-   `usuarios` → Usuario (hasMany)

### Permiso

Permisos granulares del sistema.

**Estructura**: `modulo.recurso.accion`

**Ejemplos**:

-   `principal.personas.ver`
-   `agenda.citas.crear`
-   `facturacion.comprobantes.emitir`
-   `inventario.items.editar`

### Sala

Salas de atención del centro.

## Endpoints

### Personas

```http
GET    /api/v1/principal/personas
POST   /api/v1/principal/personas
GET    /api/v1/principal/personas/{id}
PUT    /api/v1/principal/personas/{id}
DELETE /api/v1/principal/personas/{id}
```

### Usuarios

```http
GET    /api/v1/principal/usuarios
POST   /api/v1/principal/usuarios
GET    /api/v1/principal/usuarios/{id}
PUT    /api/v1/principal/usuarios/{id}
DELETE /api/v1/principal/usuarios/{id}
```

### Roles

```http
GET    /api/v1/principal/roles
GET    /api/v1/principal/roles/{id}
```

### Salas

```http
GET    /api/v1/principal/salas
POST   /api/v1/principal/salas
GET    /api/v1/principal/salas/{id}
PUT    /api/v1/principal/salas/{id}
DELETE /api/v1/principal/salas/{id}
```

## Validaciones de Negocio

✅ DNI debe ser único por tipo de persona  
✅ Email debe ser único si se proporciona  
✅ Usuario requiere- **users** (Laravel Default): Autenticación (Email/Password). PK: `id` (bigint).

-   **principal_usuarios**: Perfil extendido. - PK: `usuario_id` (FK a users.id). **Relación 1:1 Estricta**. - FK: `persona_id` (FK a principal_personas.persona_id). - Roles y estado activo.
    ✅ Rol debe existir al crear usuario

## Repositorio y Servicio

**Repository**: `PrincipalRepository`  
**Service**: `PrincipalService`

## Ejemplo de Uso

### Crear Persona

```bash
curl -X POST http://localhost:8000/api/v1/principal/personas \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "tipo_persona": "PACIENTE",
    "documento_tipo": "DNI",
    "documento_numero": "12345678",
    "nombres": "Juan Carlos",
    "apellidos": "Pérez García",
    "fecha_nacimiento": "1990-05-15",
    "genero": "M",
    "email": "juan@example.com",
    "celular": "999888777"
  }'
```

### Crear Usuario

```bash
curl -X POST http://localhost:8000/api/v1/principal/usuarios \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "persona_id": "uuid-persona",
    "rol_id": 5,
    "username": "fisioterapeuta01",
    "activo": true
  }'
```

---

**Ver también**:

-   [API Principal](../api/principal.md)
-   [Autenticación](authentication.md)
