# Módulo de Agenda

## Descripción

Gestión completa de citas médicas con validaciones de disponibilidad, horarios de fisioterapeutas, días no laborables y prevención de solapamientos.

## Modelos

### Cita

Cita agendada entre paciente y fisioterapeuta.

**Primary Key**: UUID (`cita_id`)

**Campos**:

-   `paciente_id` (FK → Persona)
-   `fisioterapeuta_id` (FK → Persona)
-   `servicio_id` (FK → Servicio)
-   `fecha_inicio`, `fecha_fin` (UTC)
-   `estado` (reservado, confirmado, cancelado, completado)
-   `creado_por` (FK → Usuario)

**Relaciones**:

-   `paciente` → Persona (belongsTo)
-   `fisioterapeuta` → Persona (belongsTo)
-   `servicio` → Servicio (belongsTo)

### CitaEstado

Estados posibles de las citas.

### HorarioFisioterapeuta

Horarios de disponibilidad semanales.

**Campos**:

-   `fisioterapeuta_id`
-   `dia_semana` (0-6, domingo=0)
-   `hora_inicio`, `hora_fin`
-   `activo`

### DiaNoLaborable

Días festivos o no laborables.

**Campos**:

-   `fecha`
-   `descripcion`
-   `fisioterapeuta_id` (null = global)

## Validaciones de Negocio (AgendaService)

El servicio implementa validaciones robustas:

### Al crear cita:

1. ✅ **Paciente y fisioterapeuta requeridos**
2. ✅ **No es día no laborable** (global o del fisioterapeuta)
3. ✅ **Horario del fisioterapeuta cubre el intervalo**
4. ✅ **No hay solapamiento** con otras citas del fisioterapeuta
    - Ocurre si:
        - El Fisioterapeuta ya tiene cita en el intervalo (buffer configurable, default 10min).
        - LA SALA ya está ocupada en el intervalo (Nuevo: validación por recurso físico).
    - Exclusión: Se excluye la propia cita al editar.
    - Estados bloqueantes: `RESERVADA`, `CONFIRMADA`.
5. ✅ **Estado inicial configurado**

### Al actualizar cita:

1. ✅ Todas las validaciones de creación
2. ✅ **Excluye la misma cita** al validar solapamiento
3. ✅ **Estado válido** si se actualiza

### Al crear horario:

1. ✅ **Hora fin > Hora inicio**
2. ✅ **Día semana válido** (0-6)

### Al crear día no laborable:

1. ✅ **No duplicar fecha** para mismo fisioterapeuta

## Endpoints

### Citas

```http
GET    /api/v1/agenda/citas
POST   /api/v1/agenda/citas
GET    /api/v1/agenda/citas/{id}
PUT    /api/v1/agenda/citas/{id}
DELETE /api/v1/agenda/citas/{id}
```

### Estados

```http
GET    /api/v1/agenda/estados
```

### Horarios

```http
GET    /api/v1/agenda/horarios
POST   /api/v1/agenda/horarios
GET    /api/v1/agenda/horarios/{id}
PUT    /api/v1/agenda/horarios/{id}
DELETE /api/v1/agenda/horarios/{id}
```

### Días No Laborables

```http
GET    /api/v1/agenda/dias-no-laborables
POST   /api/v1/agenda/dias-no-laborables
GET    /api/v1/agenda/dias-no-laborables/{id}
DELETE /api/v1/agenda/dias-no-laborables/{id}
```

## Flujo de Creación de Cita

```
1.- `agenda_citas`: Tabla central.
    - PK `cita_id` (UUID).
    - FK `paciente_id`, `fisioterapeuta_id`, `servicio_id`.
    - FK `sala_id`: Gestión de recursos físicos (Agregado en migración de hotfix).
    - `estado_id`: FK a `agenda_cita_estados`.
1. Usuario envía solicitud con:
   - paciente_id
   - fisioterapeuta_id
   - servicio_id (opcional)
   - fecha_inicio, fecha_fin
   - timezone (opcional)

2. AgendaService valida:
   ✓ Paciente y fisio existen
   ✓ No es día no laborable
   ✓ Fisio trabaja en ese horario
   ✓ No hay solapamiento

3. Si todo OK:
   - Crea cita con estado inicial
   - Retorna cita creada

4. Si error:
   - Lanza ValidationException con mensaje específico
```

## Zona Horaria

Las fechas se almacenan en UTC. El cliente puede enviar `timezone`:

```json
{
    "fecha_inicio": "2026-01-15 09:00:00",
    "fecha_fin": "2026-01-15 10:00:00",
    "timezone": "America/Lima"
}
```

El servicio convierte a UTC automáticamente.

## Ejemplo de Uso

### Crear Cita

```bash
curl -X POST http://localhost:8000/api/v1/agenda/citas \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "paciente_id": "uuid-paciente",
    "fisioterapeuta_id": "uuid-fisio",
    "servicio_id": "uuid-servicio",
    "fecha_inicio": "2026-01-15 09:00:00",
    "fecha_fin": "2026-01-15 10:00:00",
    "timezone": "America/Lima"
  }'
```

### Respuesta Exitosa

```json
{
    "cita_id": "uuid",
    "paciente_id": "uuid",
    "fisioterapeuta_id": "uuid",
    "fecha_inicio": "2026-01-15 14:00:00",
    "fecha_fin": "2026-01-15 15:00:00",
    "estado": "reservado"
}
```

### Respuesta Error (Solapamiento)

```json
{
    "message": "Error de validación",
    "errors": {
        "fecha_inicio": [
            "El horario se solapa con otra cita (fisioterapeuta o sala)."
        ]
    },
    "codigo": "VALIDATION_ERROR"
}
```

---

**Ver también**:

-   [API Agenda](../api/agenda.md)
-   [AgendaService](../../app/Services/Agenda/AgendaService.php)
