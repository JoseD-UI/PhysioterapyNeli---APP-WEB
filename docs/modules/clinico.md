# Módulo Clínico

## Descripción

Gestión de la información clínica del centro: historias clínicas, sesiones de fisioterapia y servicios ofrecidos.

## Modelos

### TipoServicio

Categorías de servicios (masajes, terapias, rehabilitación).

### Servicio

Servicios específicos ofrecidos por el centro.

**Campos**:

-   `servicio_id` (UUID, PK)
-   `tipo_servicio_id` (FK)
-   `nombre`
-   `descripcion`
-   `duracion_minutos`
-   `precio`
-   `activo`

### HistoriaClinica

Expediente médico del paciente.

**Campos**:

-   `historia_id` (UUID, PK)
-   `persona_id` (FK → Persona)
-   `antecedentes_personales`
-   `antecedentes_familiares`
-   `motivo_consulta`
-   `diagnostico`
-   `tratamiento_sugerido`
-   `observaciones`

### Sesion

Registro de cada sesión de fisioterapia realizada.

**Campos**:

-   `sesion_id` (UUID, PK)
-   `paciente_id` (FK → Persona)
-   `fisioterapeuta_id` (FK → Persona)
-   `servicio_id` (FK → Servicio)
-   `fecha_sesion`
-   `duracion_minutos`
-   `observaciones`
-   `evolucion`

## Endpoints

```http
# Servicios
GET    /api/v1/clinico/servicios
POST   /api/v1/clinico/servicios
GET    /api/v1/clinico/servicios/{id}
PUT    /api/v1/clinico/servicios/{id}
DELETE /api/v1/clinico/servicios/{id}

# Tipos Servicio
GET    /api/v1/clinico/tipos-servicio
POST   /api/v1/clinico/tipos-servicio
GET    /api/v1/clinico/tipos-servicio/{id}
PUT    /api/v1/clinico/tipos-servicio/{id}
DELETE /api/v1/clinico/tipos-servicio/{id}

# Historias Clínicas
GET    /api/v1/clinico/historias
POST   /api/v1/clinico/historias
GET    /api/v1/clinico/historias/{id}
PUT    /api/v1/clinico/historias/{id}
DELETE /api/v1/clinico/historias/{id}

# Sesiones
GET    /api/v1/clinico/sesiones
POST   /api/v1/clinico/sesiones
GET    /api/v1/clinico/sesiones/{id}
PUT    /api/v1/clinico/sesiones/{id}
DELETE /api/v1/clinico/sesiones/{id}
```

## Repositorio y Servicio

**Repository**: `ClinicoRepository`  
**Service**: `ClinicoService`

---

**Ver también**:

-   [API Clínico](../api/clinico.md)
