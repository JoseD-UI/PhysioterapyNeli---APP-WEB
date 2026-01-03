# Diagrama de Base de Datos

## Vista General del Sistema

El sistema está organizado en 8 módulos principales con sus respectivas tablas.

## Diagrama ERD Completo

```mermaid
erDiagram
    %% PRINCIPAL
    principal_personas ||--o{ principal_usuarios : "tiene"
    principal_personas ||--o{ agenda_citas : "es paciente"
    principal_personas ||--o{ agenda_citas : "es fisioterapeuta"
    principal_usuarios }o--|| principal_roles : "tiene rol"
    principal_roles ||--o{ principal_rol_permiso : "tiene"
    principal_permisos ||--o{ principal_rol_permiso : "asignado a"
    users ||--|| principal_usuarios : "auth"

    %% AGENDA
    principal_personas ||--o{ agenda_citas : "paciente"
    principal_personas ||--o{ agenda_citas : "fisioterapeuta"
    clinico_servicios ||--о{ agenda_citas : "servicio"
    agenda_citas }o--|| agenda_cita_estados : "estado"
    principal_personas ||--o{ agenda_horarios_fisioterapeuta : "horarios"
    principal_personas ||--o{ agenda_dias_no_laborables : "dias no laborables"

    %% CLINICO
    principal_personas ||--o{ clinico_historias_clinicas : "tiene"
    principal_personas ||--o{ clinico_sesiones : "paciente"
    principal_personas ||--o{ clinico_sesiones : "fisioterapeuta"
    clinico_servicios ||--o{ clinico_sesiones : "servicio"
    clinico_tipos_servicio ||--o{ clinico_servicios : "tipo"

    %% FACTURACION
    principal_personas ||--o{ facturacion_comprobantes : "cliente"
    facturacion_comprobantes ||--o{ facturacion_detalles : "detalles"
    facturacion_comprobantes ||--o{ facturacion_pagos : "pagos"
    inventario_items ||--o{ facturacion_detalles : "producto"
    facturacion_series ||--o{ facturacion_comprobantes : "serie"

    %% INVENTARIO
    inventario_categorias ||--o{ inventario_items : "categoria"
    inventario_unidades ||--o{ inventario_items : "unidad"
    inventario_items ||--o{ inventario_kardex : "movimientos"
    inventario_items ||--o{ inventario_activos : "activo"

    %% COMPRAS
    principal_personas ||--o{ compras_compras : "proveedor"
    compras_compras ||--o{ compras_detalles : "detalles"
    inventario_items ||--o{ compras_detalles : "item"

    %% SEGURIDAD
    principal_usuarios ||--o{ seguridad_audit_log : "usuario"

    %% Definiciones de Tablas Principales

    users {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
    }

    principal_personas {
        uuid persona_id PK
        string tipo_persona
        string documento_tipo
        string documento_numero UK
        string nombres
        string apellidos
        date fecha_nacimiento
        string genero
        string email UK
        string telefono
        string celular
        text direccion
    }

    principal_usuarios {
        uuid usuario_id PK
        uuid persona_id FK
        bigint user_id FK
        int rol_id FK
        string username UK
        boolean activo
    }

    principal_roles {
        int rol_id PK
        string nombre UK
        string descripcion
    }

    principal_permisos {
        int permiso_id PK
        string codigo UK
        string modulo
        string descripcion
    }

    agenda_citas {
        uuid cita_id PK
        uuid paciente_id FK
        uuid fisioterapeuta_id FK
        uuid servicio_id FK
        datetime fecha_inicio
        datetime fecha_fin
        string estado
    }

    clinico_historias_clinicas {
        uuid historia_id PK
        uuid persona_id FK
        text antecedentes
        text diagnostico
        text tratamiento
    }

    clinico_sesiones {
        uuid sesion_id PK
        uuid paciente_id FK
        uuid fisioterapeuta_id FK
        uuid servicio_id FK
        datetime fecha_sesion
        text observaciones
    }

    facturacion_comprobantes {
        uuid id PK
        string tipo_comprobante
        string serie
        int correlativo
        uuid cliente_id FK
        string ruc_cliente
        decimal subtotal
        decimal igv
        decimal total
        string estado
        string estado_comprobante
    }

    facturacion_detalles {
        uuid id PK
        uuid comprobante_id FK
        uuid producto_id FK
        string descripcion
        decimal cantidad
        decimal precio_unitario
        decimal subtotal
        decimal igv
        decimal total
    }

    facturacion_pagos {
        uuid id PK
        uuid comprobante_id FK
        string medio_pago
        decimal monto
        datetime fecha_pago
        string estado_pago
    }

    inventario_items {
        uuid item_id PK
        int categoria_id FK
        int unidad_id FK
        string codigo UK
        string nombre
        text descripcion
        decimal stock_actual
        decimal costo_promedio
        boolean es_activo
    }

    inventario_kardex {
        uuid kardex_id PK
        uuid item_id FK
        date fecha
        string tipo_movimiento
        decimal cantidad
        decimal costo_unitario
        decimal saldo_cantidad
        decimal saldo_valorizado
        string referencia_tipo
        uuid referencia_id
    }

    compras_compras {
        uuid compra_id PK
        uuid proveedor_id FK
        date fecha_compra
        decimal total
        string estado
    }

    seguridad_audit_log {
        uuid log_id PK
        uuid usuario_id FK
        string tabla_nombre
        string operacion
        text datos_anteriores
        text datos_nuevos
        datetime fecha
    }
```

## Módulos y Tablas

### Principal (Core)

-   `users` - Autenticación Laravel
-   `principal_personas` - Individuos del sistema
-   `principal_usuarios` - Usuarios con roles
-   `principal_roles` - Roles del sistema
-   `principal_permisos` - Permisos granulares
-   `principal_rol_permiso` - Pivot roles-permisos
-   `principal_salas` - Salas de atención

### Agenda

-   `agenda_citas` - Citas médicas
-   `agenda_cita_estados` - Estados de citas
-   `agenda_horarios_fisioterapeuta` - Disponibilidad
-   `agenda_dias_no_laborables` - Festivos

### Clínico

-   `clinico_tipos_servicio` - Tipos de servicios
-   `clinico_servicios` - Servicios ofrecidos
-   `clinico_historias_clinicas` - Historias clínicas
-   `clinico_sesiones` - Sesiones realizadas

### Facturación

-   `facturacion_comprobantes` - Facturas/Boletas/NC
-   `facturacion_detalles` - Líneas de comprobante
-   `facturacion_pagos` - Pagos recibidos
-   `facturacion_series` - Control de numeración

### Inventario

-   `inventario_categorias` - Categorías de productos
-   `inventario_unidades` - Unidades de medida
-   `inventario_items` - Productos/Insumos
-   `inventario_kardex` - Movimientos de inventario
-   `inventario_activos` - Activos fijos

### Compras

-   `compras_compras` - Compras realizadas
-   `compras_detalles` - Líneas de compra

### Contabilidad

-   `contabilidad_libros_resumen` - Resúmenes contables

### Seguridad

-   `seguridad_audit_log` - Auditoría de operaciones

## Índices Principales

Todos los módulos tienen índices en:

-   Primary keys (UUID)
-   Foreign keys
-   Campos de búsqueda frecuente (email, DNI, código)
-   Campos de filtrado (fecha, estado, tipo)
-   Índices compuestos para queries complejas

Ver [add_indexes_to_tables.php](../../database/migrations/2026_01_02_003327_add_indexes_to_tables.php)

---

**Ver también**:

-   [Arquitectura](overview.md)
-   [Migraciones](../../database/migrations/)
