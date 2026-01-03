# Módulo de Compras

## Descripción

Gestión de compras a proveedores con integración automática al inventario.

## Modelos

### Compra

¡Registro de compra a proveedor.

**Campos**:

-   `compra_id` (UUID, PK)
-   `proveedor_id` (FK → Persona)
-   `numero_comprobante`
-   `fecha_compra`
-   `total`
-   `estado` (pendiente, completada, anulada)

### CompraDetalle

Líneas de la compra.

**Campos**:

-   `detalle_id` (UUID, PK)
-   `compra_id` (FK)
-   `item_id` (FK → Item)
-   `descripcion`
-   `cantidad`
-   `costo_unitario`
-   `subtotal`

## Endpoints

```http
GET    /api/v1/compras
POST   /api/v1/compras
GET    /api/v1/compras/{id}
PUT    /api/v1/compras/{id}
DELETE /api/v1/compras/{id}
```

## Integración con Inventario

Cada compra completada:

1. Registra INGRESO en Kardex por cada item
2. Actualiza `stock_actual`
3. Recalcula `costo_promedio`

## Repositorio y Servicio

**Repository**: `ComprasRepository`  
**Service**: `ComprasService`

---

**Ver también**:

-   [API Compras](../api/compras.md)
-   [Inventario](inventario.md)
