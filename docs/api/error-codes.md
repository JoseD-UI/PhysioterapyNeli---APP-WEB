# Códigos de Error - API

## Formato de Respuesta de Error

Todas las respuestas de error en la API siguen el formato:

```json
{
    "message": "Descripción legible del error",
    "codigo": "CODIGO_ERROR",
    "errors": {} // Opcional, para errores de validación
}
```

## Códigos de Error Globales

### Autenticación y Autorización

| Código               | HTTP | Descripción                               |
| -------------------- | ---- | ----------------------------------------- |
| `UNAUTHENTICATED`    | 401  | No autenticado. Token inválido o expirado |
| `USUARIO_SIN_PERFIL` | 403  | Usuario sin perfil de negocio asignado    |
| `USUARIO_INACTIVO`   | 403  | Usuario desactivado                       |
| `PERMISO_DENEGADO`   | 403  | Sin permisos para la acción               |

### Validación

| Código             | HTTP | Descripción                   |
| ------------------ | ---- | ----------------------------- |
| `VALIDATION_ERROR` | 422  | Error de validación de campos |

### Recursos

| Código               | HTTP | Descripción           |
| -------------------- | ---- | --------------------- |
| `RESOURCE_NOT_FOUND` | 404  | Recurso no encontrado |
| `ENDPOINT_NOT_FOUND` | 404  | Endpoint no existe    |

### Sistema

| Código           | HTTP | Descripción                |
| ---------------- | ---- | -------------------------- |
| `DATABASE_ERROR` | 500  | Error en base de datos     |
| `SERVER_ERROR`   | 500  | Error interno del servidor |

## Códigos por Módulo

### Facturación

| Código                  | HTTP | Descripción                                  |
| ----------------------- | ---- | -------------------------------------------- |
| `RUC_REQUERIDO`         | 422  | Factura requiere RUC del cliente             |
| `COMPROBANTE_ANULADO`   | 422  | No se puede operar sobre comprobante anulado |
| `STOCK_INSUFICIENTE`    | 422  | Stock insuficiente para la venta             |
| `SERIE_NO_ENCONTRADA`   | 404  | No hay serie configurada para el tipo        |
| `MOTIVO_SUNAT_INVALIDO` | 422  | Código de motivo SUNAT inválido              |

### Agenda

| Código               | HTTP | Descripción                              |
| -------------------- | ---- | ---------------------------------------- |
| `DIA_NO_LABORABLE`   | 422  | Día no laborable para el fisioterapeuta  |
| `FUERA_DE_HORARIO`   | 422  | Fisioterapeuta no trabaja en ese horario |
| `CITA_SOLAPADA`      | 422  | Hay solapamiento con otra cita           |
| `CITA_NO_ENCONTRADA` | 404  | Cita no existe                           |

### Inventario

| Código               | HTTP | Descripción                            |
| -------------------- | ---- | -------------------------------------- |
| `ITEM_NO_ENCONTRADO` | 404  | Item no existe                         |
| `STOCK_NEGATIVO`     | 422  | Operación resultaría en stock negativo |
| `CODIGO_DUPLICADO`   | 422  | Código de item ya existe               |

## Ejemplos de Respuestas

### Error de Validación

```json
{
    "message": "Error de validación",
    "codigo": "VALIDATION_ERROR",
    "errors": {
        "email": ["El campo email es requerido"],
        "password": ["La contraseña debe tener al menos 8 caracteres"]
    }
}
```

### Usuario No Autenticado

```json
{
    "message": "No autenticado",
    "codigo": "UNAUTHENTICATED"
}
```

### Sin Permisos

```json
{
    "message": "No tienes permiso para realizar esta acción",
    "codigo": "PERMISO_DENEGADO"
}
```

### Error de Negocio

```json
{
    "message": "El horario se solapa con otra cita (fisioterapeuta o sala).",
    "codigo": "CITA_SOLAPADA",
    "errors": {
        "fecha_inicio": ["El horario se solapa con otra cita"]
    }
}
```

### Error de Servidor

```json
{
    "message": "Error interno del servidor",
    "codigo": "SERVER_ERROR"
}
```

## Manejo de Errores en Cliente

```javascript
// Ejemplo con fetch
fetch("/api/v1/facturacion/comprobantes", {
    method: "POST",
    headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
}).then((response) => {
    if (!response.ok) {
        return response.json().then((error) => {
            // Handle error based on codigo
            switch (error.codigo) {
                case "UNAUTHENTICATED":
                    // Redirect to login
                    break;
                case "VALIDATION_ERROR":
                    // Show validation errors
                    break;
                case "RUC_REQUERIDO":
                    // Show specific business error
                    break;
            }
            throw error;
        });
    }
    return response.json();
});
```

---

**Ver también**:

-   [Manejo de Errores](../architecture/overview.md#seguridad)
-   [Handler.php](../../app/Exceptions/Handler.php)
