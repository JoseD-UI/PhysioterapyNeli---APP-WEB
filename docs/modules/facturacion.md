# Módulo de Facturación

## Descripción

Gestión completa de facturación electrónica con integración SUNAT, incluyendo comprobantes de pago (facturas, boletas, tickets), pagos y notas de crédito.

## Arquitectura del Módulo

### Servicios Especializados

El módulo ha sido refactorizado en tres servicios especializados:

```
Facturacion/
├── ComprobanteService.php   → Emisión de comprobantes (01, 03, 12)
├── NotaCreditoService.php   → Notas de crédito (07)
└── PagoService.php          → Registro de pagos
```

#### ComprobanteService

**Responsabilidades**:

-   Emitir facturas (01), boletas (03), tickets (12)
-   Generar serie y correlativo automático
-   Validar stock disponible
-   Actualizar inventario con cada venta
-   Anular comprobantes

**Métodos principales**:

```php
emitir(array $data): FacturacionComprobante
anular(string $id, string $motivo): void
```

#### NotaCreditoService

**Responsabilidades**:

-   Emitir notas de crédito (07)
-   Validar motivos SUNAT (01-13)
-   Revertir inventario
-   Anular comprobante original si corresponde

**Métodos principales**:

```php
emitir(array $data): FacturacionComprobante
```

#### PagoService

**Responsabilidades**:

-   Registrar pagos
-   Calcular saldos
-   Validar comprobantes no anulados

**Métodos principales**:

```php
registrar(array $data): FacturacionPago
calcularSaldo(string $comprobanteId): float
obtenerHistorialPagos(string $comprobanteId): array
```

## Modelos

### FacturacionComprobante

-   **Primary Key**: UUID ('id')
-   **Tipos**: 01 (Factura), 03 (Boleta), 12 (Ticket), 07 (Nota Crédito)
-   **Estados**: emitida, anulada
-   **SUNAT**: pendiente_envio, aceptada, rechazada

**Relaciones**:

-   `detalles` → FacturacionDetalle (hasMany)
-   `pagos` → FacturacionPago (hasMany)
-   `cliente` → Persona (belongsTo)

### FacturacionDetalle

Líneas de comprobante con cálculo automático de IGV (18%).

### FacturacionPago

Pagos asociados a comprobantes con múltiples medios de pago.

### FacturacionSerie

Control de series y correlativos por tipo de comprobante.

### FacturacionDocumentoSunat (Nuevo)

Almacena la respuesta oficial de SUNAT (CDR, XML, Hash) para cada comprobante enviado.

-   **Fields**: xml_content, cdr_content, hash_cpe, ticket_sunat, codigo_respuesta, mensaje_respuesta
-   **Relación**: belongsTo `comprobante`

## Flujo de Emisión de Comprobante

```
1. Cliente solicita comprobante
2. Controller valida request
3. ComprobanteService:
   a. Valida tipo y requisitos fiscales
   b. Obtiene serie y correlativo
   c. Crea comprobante
   d. Procesa detalles (cantidad × precio + IGV)
   e. Actualiza inventario (SALIDA en Kardex)
   f. Actualiza totales
4. Retorna comprobante creado
```

## Flujo de Nota de Crédito

```
1. Cliente solicita NC
2. Controller valida request
3. NotaCreditoService:
   a. Valida motivo SUNAT
   b. Valida comprobante original
   c. Obtiene serie 07
   d. Crea nota de crédito
   e. Procesa detalles
   f. Revierte inventario (INGRESO en Kardex)
   g. Si motivo=01, anula comprobante original
4. Retorna nota de crédito
```

## Códigos SUNAT para Notas de Crédito

| Código | Descripción                            |
| ------ | -------------------------------------- |
| 01     | Anulación de la operación              |
| 02     | Anulación por error en el RUC          |
| 03     | Corrección por error en la descripción |
| 04     | Descuento global                       |
| 05     | Descuento por ítem                     |
| 06     | Devolución total                       |
| 07     | Devolución por ítem                    |
| 08     | Bonificación                           |
| 09     | Disminución en el valor                |
| 10     | Otros conceptos                        |

## Integración con Inventario

Cada comprobante emitido:

1. Registra SALIDA en Kardex por cada producto
2. Actualiza stock disponible
3. Calcula costo promedio

Cada nota de crédito:

1. Registra INGRESO en Kardex (devolución)
2. Actualiza stock sumando cantidad
3. Recalcula costo promedio

## Validaciones de Negocio

✅ Factura requiere RUC del cliente  
✅ No emitir sobre comprobantes anulados✅ Stock suficiente antes de emitir  
✅ Validar motivos SUNAT en NC  
✅ No registrar pagos en comprobantes anulados

## Endpoints Principales

```http
POST   /api/v1/facturacion/comprobantes
POST   /api/v1/facturacion/comprobantes/{id}/anular
POST   /api/v1/facturacion/pagos
POST   /api/v1/facturacion/notas-credito
```

Ver [api/facturacion.md](../api/facturacion.md) para detalles completos.

## Ejemplo de Uso

### Emitir Factura

```bash
curl -X POST http://localhost:8000/api/v1/facturacion/comprobantes \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "tipo_comprobante": "01",
    "cliente_id": "uuid-cliente",
    "ruc_cliente": "20123456789",
    "razon_social": "EMPRESA SAC",
    "detalles": [
      {
        "producto_id": "uuid-producto",
        "descripcion": "Sesión de fisioterapia",
        "cantidad": 1,
        "precio_unitario": 100.00
      }
    ]
  }'
```

---

**Ver también**:

-   [API Facturación](../api/facturacion.md)
-   [Integración SUNAT](../deployment/sunat-integration.md)
