# Módulo de Inventario

## Descripción

Gestión completa de inventario con sistema Kardex de valorización promedio, control de stock y gestión de activos fijos.

## Modelos

### Item

Productos, insumos o activos del centro.

**Campos**:

-   `item_id` (UUID, PK)
-   `categoria_id` (FK)
-   `unidad_id` (FK)
-   `codigo` (único)
-   `nombre`
-   `descripcion`
-   `stock_actual`
-   `stock_minimo`
-   `costo_promedio`
-   `es_activo` (distingue productos de activos fijos)

### Kardex

Registro de movimientos de inventario con valorización.

**Campos**:

-   `kardex_id` (UUID, PK)
-   `item_id` (FK)
-   `fecha`
-   `tipo_movimiento` (INGRESO, SALIDA)
-   `cantidad`
-   `costo_unitario`
-   `saldo_cantidad`
-   `saldo_valorizado`
-   `documento_tipo`, `documento_serie`, `documento_correlativo`
-   `referencia_tipo`, `referencia_id`

**Método de valorización**: PROMEDIO PONDERADO

### Categoria

Categorías de productos (medicamentos, insumos, equipamiento).

### Unidad

Unidades de medida (unidad, caja, frasco, etc.).

### Activo

Activos fijos con control adicional.

**Campos**:

-   `activo_id` (UUID, PK)
-   `item_id` (FK)
-   `codigo_activo`
-   `fecha_adquisicion`
-   `valor_adquisicion`
-   `vida_util_años`
-   `estado` (operativo, mantenimiento, baja)
-   `ubicacion`

## Endpoints

```http
# Items
GET    /api/v1/inventario/items
POST   /api/v1/inventario/items
GET    /api/v1/inventario/items/{id}
PUT    /api/v1/inventario/items/{id}
DELETE /api/v1/inventario/items/{id}

# Kardex
GET    /api/v1/inventario/kardex/{itemId}
GET    /api/v1/inventario/kardex-resumen

# Categorías
GET    /api/v1/inventario/categorias
POST   /api/v1/inventario/categorias

# Unidades
GET    /api/v1/inventario/unidades
POST   /api/v1/inventario/unidades

# Activos
GET    /api/v1/inventario/activos
POST   /api/v1/inventario/activos
GET    /api/v1/inventario/activos/{id}
PUT    /api/v1/inventario/activos/{id}
```

## Integración con Facturación

Cada venta automáticamente:

### 3.2 Lógica de Kardex

-   **Movimientos**: Inmutables.
-   **Tipos Normalizados**:
    -   **API Entrada**: Acepta `ENTRADA`, `INGRESO`, `AJUSTE_ENTRADA`.
    -   **API Salida**: Acepta `SALIDA`, `VENTA`, `AJUSTE_SALIDA`.
    -   **Base de Datos**: Se almacena estrictamente como `entrada` o `salida` (lowercase) según el Enum de MySQL.
-   **Cálculo de Costos**:
    -   Método: **Promedio Ponderado**.
    -   Fórmula: `((SaldoAnterior * CostoPromedioAnterior) + (CantidadNueva * CostoUnitarioNuevo)) / NuevoSaldoTotal`.
    -   Salidas: Se valorizan al costo promedio del momento.
-   **Validación de Stock**:
    -   No permite movimientos que dejen el stock físico negativo.

## Repositorio y Servicio

**Repository**: `InventarioRepository`  
**Service**: `InventarioService`

---

**Ver también**:

-   [API Inventario](../api/inventario.md)
-   [Facturación](facturacion.md)
