# API - Facturación

## Endpoints

### Emitir Comprobante

```http
POST /api/v1/facturacion/comprobantes
Authorization: Bearer {token}
Content-Type: application/json
```

**Request**:

```json
{
    "tipo_comprobante": "01",
    "cliente_id": "uuid-cliente",
    "ruc_cliente": "20123456789",
    "razon_social": "EMPRESA SAC",
    "direccion_fiscal": "Av. Principal 123",
    "detalles": [
        {
            "producto_id": "uuid-producto",
            "descripcion": "Sesión de fisioterapia",
            "cantidad": 2,
            "precio_unitario": 100.0
        },
        {
            "descripcion": "Servicio adicional",
            "cantidad": 1,
            "precio_unitario": 50.0
        }
    ]
}
```

**Response** (201):

```json
{
  "id": "uuid",
  "tipo_comprobante": "01",
  "serie": "F001",
  "correlativo": 123,
  "ruc_cliente": "20123456789",
  "razon_social": "EMPRESA SAC",
  "subtotal": 250.00,
  "igv": 45.00,
  "total": 295.00,
  "estado": "emitida",
  "estado_comprobante": "pendiente_envio",
  "detalles": [...]
}
```

### Registrar Pago

```http
POST /api/v1/facturacion/pagos
Authorization: Bearer {token}
```

**Request**:

```json
{
    "comprobante_id": "uuid",
    "medio_pago": "efectivo",
    "monto": 295.0,
    "recibo": "REC-001"
}
```

### Anular Comprobante

```http
POST /api/v1/facturacion/comprobantes/{id}/anular
Authorization: Bearer {token}
```

**Request**:

```json
{
    "motivo": "Error en emisión"
}
```

### Emitir Nota de Crédito

```http
POST /api/v1/facturacion/notas-credito
Authorization: Bearer {token}
```

**Request**:

```json
{
    "comprobante_referencia_id": "uuid-factura",
    "motivo_codigo": "01",
    "motivo_descripcion": "Anulación de la operación",
    "detalles": []
}
```

## Códigos de Error

| Código                | Descripción                    |
| --------------------- | ------------------------------ |
| `VALIDATION_ERROR`    | Error de validación            |
| `STOCK_INSUFICIENTE`  | Stock insuficiente para emitir |
| `COMPROBANTE_ANULADO` | Comprobante ya anulado         |
| `RUC_REQUERIDO`       | Factura requiere RUC           |

---

**Ver también**:

-   [Módulo Facturación](../modules/facturacion.md)
-   [Códigos de Error](error-codes.md)
