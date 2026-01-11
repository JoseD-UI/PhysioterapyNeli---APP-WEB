# 🗺️ ROADMAP DETALLADO DE IMPLEMENTACIÓN

**Fecha**: Enero 10, 2026  
**Duración Total**: 8 semanas  
**Componentes a crear**: ~130  
**Líneas de código**: ~50,000  

---

## 📅 SEMANA 1: FUNDACIÓN - ARCHITECTURE (10-17 Enero)

### OBJETIVO
Crear la base arquitectónica del admin panel que soportará todos los módulos.

### TAREAS

#### Lunes 10-11 Enero (8 horas)

```
TAREA 1: AdminLayout Mejorado
├─ Eliminar: MainLayout actual (obsoleto)
├─ Crear: AdminLayout.jsx (nueva arquitectura)
└─ Features:
    ├─ Sidebar dinámico (collapsible)
    ├─ Navbar con notificaciones
    ├─ Breadcrumb automático
    └─ Role-based menu

Componentes nuevos: 4
- AdminLayout.jsx
- AdminSidebar.jsx
- AdminNavbar.jsx
- BreadcrumbTrail.jsx

Tiempo: 4 horas (desarrollo)
        2 horas (testing)
        2 horas (refinement)

Entregables:
✓ Layout responsive (desktop/tablet/mobile)
✓ Sidebar con ícones
✓ Menu items varían según permisos
✓ Navbar con avatar usuario
```

#### Martes 11-12 Enero (8 horas)

```
TAREA 2: Dashboard General KPIs
├─ Crear: AdminDashboard.jsx
├─ 4 KPI Cards
│  ├─ Total citas hoy
│  ├─ Ingresos hoy
│  ├─ Sesiones completas
│  └─ Pacientes nuevos
├─ Mini Chart: Últimos 7 días
└─ Recent Activity Table

Componentes nuevos: 8
- AdminDashboard.jsx
- KPICard.jsx (4 instancias)
- MiniChart.jsx
- RecentActivityTable.jsx
- StatsGrid.jsx

API Hooks necesarios:
- useGetDashboardKPIs
- useGetDashboardActivity

Tiempo: 3 horas (componentes)
        2 horas (API hooks)
        2 horas (gráficos)
        1 hora (testing)

Entregables:
✓ Dashboard con 4 KPIs principales
✓ Mini gráfico de actividad
✓ Tabla de últimas operaciones
✓ Datos actualizados en tiempo real
```

#### Miércoles 12-13 Enero (8 horas)

```
TAREA 3: Sistema de Permisos en UI
├─ Crear: PermissionProvider (context)
├─ Hook: usePermiso(codigo)
├─ Implementar: <ProtectedComponent>
└─ Route guards: <ProtectedRoute>

Funcionalidad:
├─ Si usuario NO tiene permiso → oculta elemento
├─ Si usuario NO tiene permiso → redirige
├─ Admin ve todo
├─ Fisioterapeuta ve solo su módulo
├─ Contador ve solo facturación/contabilidad
└─ Encargado Inventario ve solo inventario

Componentes nuevos: 3
- PermissionProvider.jsx
- usePermiso.js hook
- ProtectedComponent.jsx
- ProtectedRoute.jsx (mejorado)

Tiempo: 2 horas (contexto)
        2 horas (hooks)
        2 horas (guards)
        2 horas (testing)

Entregables:
✓ Todos los elementos ocultos por permiso
✓ Routes protegidas dinámicamente
✓ No hay errores 403 en UI
✓ UX fluida según rol
```

#### Jueves 13-14 Enero (8 horas)

```
TAREA 4: PersonasView (CRUD)
├─ Crear: PersonasListView.jsx
├─ Crear: PersonasFormModal.jsx
├─ Features:
│  ├─ Tabla de personas
│  ├─ Búsqueda por DNI/nombre
│  ├─ CRUD completo (crear/editar/eliminar)
│  ├─ Filtro por tipo (paciente/admin/etc)
│  └─ Exportar CSV
└─ Permisos: principal.personas.*

Componentes nuevos: 6
- PersonasListView.jsx
- PersonasFormModal.jsx
- PersonaForm.jsx
- PersonasTable.jsx
- PersonaFilters.jsx
- PersonasExportBtn.jsx

API Hooks:
- useGetPersonas
- useCreatePersona
- useUpdatePersona
- useDeletePersona

Tiempo: 3 horas (listado)
        2 horas (form)
        1 hora (modal)
        2 horas (testing)

Entregables:
✓ CRUD de personas completo
✓ Validaciones en formulario
✓ Modal reutilizable
✓ Tabla con paginación
```

#### Viernes 14-15 Enero (8 horas)

```
TAREA 5: UsuariosView (CRUD)
├─ Crear: UsuariosListView.jsx
├─ Crear: UsuariosFormModal.jsx
├─ Features:
│  ├─ Tabla de usuarios
│  ├─ Buscar por username
│  ├─ CRUD completo
│  ├─ Asignar rol
│  ├─ Activar/desactivar
│  └─ Ver último acceso
└─ Permisos: principal.usuarios.*

Componentes nuevos: 6
- UsuariosListView.jsx
- UsuariosFormModal.jsx
- UsuarioForm.jsx
- UsuariosTable.jsx
- UsuarioFilters.jsx
- RoleSelector.jsx

API Hooks:
- useGetUsuarios
- useCreateUsuario
- useUpdateUsuario
- useDeleteUsuario

Tiempo: 3 horas (listado)
        2 horas (form + role selector)
        1 hora (modal)
        2 horas (testing)

Entregables:
✓ CRUD de usuarios completo
✓ Validaciones
✓ Gestión de roles
✓ Estados de usuario (activo/inactivo)
```

#### Sábado 15-16 Enero (8 horas)

```
TAREA 6: RolesView + PermisosView
├─ Crear: RolesListView.jsx (lectura)
├─ Crear: PermisosAssignmentView.jsx
├─ Features:
│  ├─ Tabla de roles existentes
│  ├─ Visualizar permisos por rol
│  ├─ Asignar/revocar permisos
│  ├─ Matriz de permisos
│  └─ Auditoría de cambios
└─ Permisos: principal.permisos.*

Componentes nuevos: 5
- RolesListView.jsx
- PermisosAssignmentView.jsx
- RolePermissionMatrix.jsx
- PermissionToggle.jsx
- RoleDetail.jsx

API Hooks:
- useGetRoles
- useGetPermisos
- useAsignarPermiso
- useRevocarPermiso

Tiempo: 2 horas (roles listado)
        3 horas (matriz permisos)
        1 hora (toggles)
        2 horas (testing)

Entregables:
✓ Matriz de permisos visual
✓ Asignación de permisos funcional
✓ Roles con permisos actualizados
✓ Historial de cambios en auditoría

Nota: Si no se completa el sábado, continúa lunes de semana 2
```

### ENTREGABLES SEMANA 1

```
✅ AdminLayout arquitectónico
✅ Dashboard General con KPIs
✅ Sistema de permisos en UI
✅ PersonasView (CRUD)
✅ UsuariosView (CRUD)
✅ RolesView + PermisosView

Total: 32 componentes nuevos
Routes nuevas: 8
Status: ✓ LANZADO Y TESTEADO
```

---

## 📅 SEMANA 2: FUNDACIÓN - COMPLETAR (17-24 Enero)

### OBJETIVO
Completar y refinar la fundación + iniciar Facturación

### Martes 17-18 Enero (Continuación)

```
TAREA 7: SalasView (opcional, CRUD)
├─ Crear: SalasListView.jsx
├─ Crear: SalasFormModal.jsx
├─ Features:
│  ├─ Tabla de salas
│  ├─ Crear/editar/eliminar
│  ├─ Capacidad
│  ├─ Equipos
│  └─ Estado
└─ Permisos: principal.salas.*

Componentes nuevos: 4
Tiempo: 3 horas
```

#### Miércoles 18-19 Enero (8 horas)

```
TAREA 8: TESTING DE SEMANA 1-2
├─ Unit tests: Componentes
├─ Integration tests: Flows
├─ E2E tests: Rutas principales
└─ Coverage: 70% mínimo

Tiempo: 8 horas
```

#### Jueves 19-20 Enero (8 horas)

```
TAREA 9: BUG FIXES + REFINEMENT
├─ Arreglar issues encontrados en testing
├─ Performance optimization
├─ Responsive design adjustments
├─ UX polish
└─ Documentación

Tiempo: 8 horas
```

---

## 📅 SEMANA 3: FACTURACIÓN (24-31 Enero)

### OBJETIVO
Implementar módulo completo de Facturación (CRÍTICO)

### Lunes 24-25 Enero (8 horas)

```
TAREA 1: FacturacionDashboard
├─ Crear: FacturacionDashboard.jsx
├─ KPIs:
│  ├─ Comprobantes emitidos hoy
│  ├─ Total ingreso hoy
│  ├─ Pendientes de pago
│  └─ Alertas SUNAT
├─ Chart: Ingresos últimos 7 días
└─ Recent: Últimos comprobantes

Componentes nuevos: 5
- FacturacionDashboard.jsx
- FacturacionKPI.jsx (4)
- FacturacionChart.jsx

API Hooks:
- useGetFacturacionKPIs
- useGetIngresosTrend

Tiempo: 4 horas
        2 horas (testing)
        2 horas (refinement)
```

### Martes 25-26 Enero (8 horas)

```
TAREA 2: ComprobantesListView
├─ Crear: ComprobantesListView.jsx
├─ Features:
│  ├─ Tabla de comprobantes
│  ├─ Filtro por estado (emitida/anulada/sunat)
│  ├─ Búsqueda por número
│  ├─ Búsqueda por cliente
│  ├─ Fechas (desde/hasta)
│  ├─ Exportar CSV/PDF
│  ├─ Ver detalle
│  ├─ Descargar PDF
│  ├─ Anular (con confirmación)
│  └─ Acciones (ver SUNAT status)
└─ Permisos: facturacion.comprobantes.*

Componentes nuevos: 5
- ComprobantesListView.jsx
- ComprobantesTable.jsx
- ComprobanteFilters.jsx
- ComprobantesExport.jsx
- SunatStatusBadge.jsx

API Hooks:
- useGetComprobantes
- useGetComprobantesFiltered

Tiempo: 4 horas
        2 horas (filters)
        2 horas (testing)
```

### Miércoles 26-27 Enero (8 horas)

```
TAREA 3: ComprobantesFormModal (Emitir)
├─ Crear: ComprobantesFormModal.jsx
├─ Features:
│  ├─ Seleccionar cliente
│  ├─ Seleccionar tipo (01=Factura, 03=Boleta)
│  ├─ Agregar ítems (servicios)
│  ├─ Cantidades + Precios
│  ├─ Cálculo automático (subtotal, IGV, total)
│  ├─ Dirección fiscal del cliente
│  ├─ Series automática
│  ├─ Guardar (y enviar a SUNAT)
│  └─ Ver preview antes de emitir
└─ Validaciones SUNAT-compliant

Componentes nuevos: 8
- ComprobantesFormModal.jsx
- ComprobantesForm.jsx
- ClienteSelector.jsx
- TipoComprobanteSelector.jsx
- ItemsAgregarTable.jsx
- ComprobantePreview.jsx
- TotalesCalculator.jsx
- ConfirmarEmisionModal.jsx

API Hooks:
- useCreateComprobante
- useCalcularTotales
- useEmitirSUNAT

Tiempo: 4 horas
        2 horas (validaciones)
        2 horas (testing)
```

### Jueves 27-28 Enero (8 horas)

```
TAREA 4: PagosView (CRUD)
├─ Crear: PagosListView.jsx
├─ Crear: PagosFormModal.jsx
├─ Features:
│  ├─ Tabla de pagos
│  ├─ Método de pago (efectivo, transferencia, tarjeta)
│  ├─ Estado (pendiente, completado, fallido)
│  ├─ Registrar pago
│  ├─ Ver comprobante de pago
│  ├─ Cambio de estado
│  └─ Reporte por método
└─ Permisos: facturacion.pagos.*

Componentes nuevos: 6
- PagosListView.jsx
- PagosFormModal.jsx
- PagoForm.jsx
- PagosTable.jsx
- MetodoPagoSelector.jsx
- ComprobantePagoView.jsx

API Hooks:
- useGetPagos
- useCreatePago
- useUpdatePago

Tiempo: 3 horas
        2 horas (form)
        1 hora (modal)
        2 horas (testing)
```

### Viernes 28-29 Enero (8 horas)

```
TAREA 5: FacturacionReportes
├─ Crear: FacturacionReportes.jsx
├─ Reportes:
│  ├─ Ventas diarias
│  ├─ Ventas por servicio (pie chart)
│  ├─ Clientes con más compras
│  ├─ Ingresos por método de pago
│  └─ Tendencias (línea)
├─ Filtros: Fecha (desde/hasta)
├─ Exportar: CSV/PDF/Excel
└─ Permisos: facturacion.reportes.*

Componentes nuevos: 6
- FacturacionReportes.jsx
- VentasDiariasChart.jsx
- VentasPorServicioChart.jsx
- ClientesFrecuentesTable.jsx
- IngresosPorMetodoChart.jsx
- TendenciasChart.jsx
- ReportesExport.jsx

API Hooks:
- useGetReporteVentas
- useGetReporteServicios
- useGetReporteClientes

Tiempo: 4 horas
        2 horas (charts)
        2 horas (testing)
```

### Sábado 29-30 Enero (8 horas)

```
TAREA 6: Integración SUNAT
├─ Verificar: endpoints SUNAT funcional
├─ Crear: SunatStatusView.jsx
├─ Features:
│  ├─ Ver estado de comprobante
│  ├─ Ticket SUNAT
│  ├─ Mensajes de error
│  ├─ Reenviar si falla
│  └─ Log de intentos
└─ Permisos: sunat.comprobantes.*

Componentes nuevos: 3
- SunatStatusView.jsx
- SunatStatusModal.jsx
- SunatErrorDetail.jsx

API Hooks:
- useGetSunatStatus
- useReenviarSunat

Tiempo: 3 horas
        2 horas (manejo errores)
        3 horas (testing + debug)
```

### ENTREGABLES SEMANA 3

```
✅ FacturacionDashboard
✅ ComprobantesView (CRUD completo)
✅ PagosView (CRUD)
✅ FacturacionReportes (3 reportes)
✅ Integración SUNAT

Total: 33 componentes nuevos
Status: ✓ CRÍTICO COMPLETADO
```

---

## 📅 SEMANA 4-5: INVENTARIO (31 Enero - 14 Febrero)

### OBJETIVO
Implementar módulo completo de Inventario

### SEMANA 4: INVENTORY VIEWS

#### Lunes 31 Enero - Martes 1 Febrero (16 horas)

```
TAREA 1: InventarioDashboard
├─ Crear: InventarioDashboard.jsx
├─ KPIs:
│  ├─ Total items en stock
│  ├─ Items bajo stock
│  ├─ Valor total inventario
│  └─ Rotación promedio
├─ Charts:
│  ├─ Stock por categoría (pie)
│  └─ Movimientos últimos 7 días
├─ Alerts: Items críticos
└─ Recent: Últimos movimientos

Componentes nuevos: 8
- InventarioDashboard.jsx
- InventarioKPI.jsx (4)
- StockPorCategoriaChart.jsx
- MovimientosChart.jsx
- StockBajoAlert.jsx

API Hooks:
- useGetInventarioKPIs
- useGetStockBajo
- useGetMovimientosTrend

Tiempo: 8 horas
        4 horas (charts)
        4 horas (testing)
```

#### Miércoles 2-3 Febrero (16 horas)

```
TAREA 2: ItemsView (CRUD)
├─ Crear: ItemsListView.jsx
├─ Crear: ItemsFormModal.jsx
├─ Features:
│  ├─ Tabla de items
│  ├─ Búsqueda por nombre/código
│  ├─ Filtro por categoría
│  ├─ Filtro por estado (activo/inactivo)
│  ├─ CRUD (crear/editar/eliminar)
│  ├─ Ver stock actual
│  ├─ Campos:
│  │  ├─ Código
│  │  ├─ Nombre
│  │  ├─ Categoría
│  │  ├─ Unidad medida
│  │  ├─ Precio unitario
│  │  ├─ Costo promedio
│  │  ├─ Stock mínimo
│  │  ├─ Stock actual (readonly)
│  │  └─ Estado (activo/inactivo)
│  └─ Validaciones
└─ Permisos: inventario.items.*

Componentes nuevos: 7
- ItemsListView.jsx
- ItemsFormModal.jsx
- ItemsForm.jsx
- ItemsTable.jsx
- ItemsFilters.jsx
- CategoriaSelector.jsx
- UnidadMedidaSelector.jsx

API Hooks:
- useGetItems
- useGetItemsFiltered
- useCreateItem
- useUpdateItem
- useDeleteItem
- useGetCategorias
- useGetUnidades

Tiempo: 8 horas
        4 horas (form validation)
        4 horas (testing)
```

#### Jueves 3-4 Febrero (16 horas)

```
TAREA 3: KardexView
├─ Crear: KardexView.jsx
├─ Features:
│  ├─ Seleccionar item
│  ├─ Tabla de movimientos históricos
│  ├─ Columnas:
│  │  ├─ Fecha
│  │  ├─ Tipo movimiento (E/S/A)
│  │  ├─ Cantidad
│  │  ├─ Costo unitario
│  │  ├─ Costo total
│  │  ├─ Stock resultante
│  │  ├─ Usuario que movió
│  │  ├─ Número de referencia (OC, etc)
│  │  └─ Notas
│  ├─ Gráfico: Stock over time
│  ├─ Cálculo: PEPS/FIFO visible
│  ├─ Validaciones de integridad
│  └─ Auditoría de cambios
└─ Permisos: inventario.kardex.*

Componentes nuevos: 6
- KardexView.jsx
- ItemKardexSelector.jsx
- KardexTable.jsx
- KardexChart.jsx (stock over time)
- KardexDetail.jsx (row detail)
- KardexExport.jsx

API Hooks:
- useGetKardexPorItem
- useGetKardexResumen
- useCalcularCostoPromedio

Tiempo: 8 horas
        4 horas (gráficos + cálculos)
        4 horas (testing + validaciones)
```

#### Viernes 4-5 Febrero (16 horas)

```
TAREA 4: ActivosView (CRUD)
├─ Crear: ActivosListView.jsx
├─ Crear: ActivosFormModal.jsx
├─ Features:
│  ├─ Tabla de activos fijos
│  ├─ CRUD (crear/editar/eliminar)
│  ├─ Campos:
│  │  ├─ Código activo
│  │  ├─ Descripción
│  │  ├─ Categoría (equipo médico, etc)
│  │  ├─ Valor inicial
│  │  ├─ Fecha adquisición
│  │  ├─ Vida útil (años)
│  │  ├─ Depreciación calculada
│  │  ├─ Valor residual
│  │  ├─ Estado (activo/inactivo/depreciado)
│  │  ├─ Ubicación
│  │  └─ Responsable
│  ├─ Cálculo automático de depreciación
│  └─ Gráfico de valor over time
└─ Permisos: inventario.activos.*

Componentes nuevos: 6
- ActivosListView.jsx
- ActivosFormModal.jsx
- ActivosForm.jsx
- ActivosTable.jsx
- ActivoDepreciacionChart.jsx
- ActivoDetail.jsx

API Hooks:
- useGetActivos
- useCreateActivo
- useUpdateActivo
- useDeleteActivo
- useCalcularDepreciacion

Tiempo: 8 horas
        4 horas (depreciación calc)
        4 horas (testing)
```

### SEMANA 5: INVENTORY ADVANCED

#### Lunes 7-8 Febrero (16 horas)

```
TAREA 5: MovimientosView (crear entrada/salida)
├─ Crear: MovimientosView.jsx
├─ Features:
│  ├─ Registrar entrada de compra
│  ├─ Registrar salida (consumo)
│  ├─ Registrar ajuste de inventario
│  ├─ Referencia a OC / número de documento
│  ├─ Validaciones de stock
│  ├─ Alertas de stock bajo
│  └─ Reversión de movimientos
└─ Permisos: inventario.movimientos.*

Componentes nuevos: 5
- MovimientosView.jsx
- MovimientoForm.jsx
- EntradaForm.jsx
- SalidaForm.jsx
- AjusteForm.jsx

API Hooks:
- useRegistrarMovimiento
- useReverseMovimiento

Tiempo: 8 horas
        4 horas (validaciones)
        4 horas (testing)
```

#### Martes 8-9 Febrero (16 horas)

```
TAREA 6: InventarioReportes
├─ Crear: InventarioReportes.jsx
├─ Reportes:
│  ├─ Stock actual por item
│  ├─ Items bajo stock (alerta)
│  ├─ Rotación de productos
│  ├─ Valor total inventario
│  ├─ Kardex resumen por mes
│  └─ Activos y depreciación
├─ Filtros: Categoría, fecha
├─ Exportar: CSV/PDF/Excel
└─ Permisos: inventario.reportes.*

Componentes nuevos: 7
- InventarioReportes.jsx
- StockActualReport.jsx
- ItemsBajoStockReport.jsx
- RotacionProductosReport.jsx
- ValorInventarioReport.jsx
- KardexResumenReport.jsx
- ActivosDepreciacionReport.jsx

API Hooks:
- useGetReporteStock
- useGetReporteRotacion
- useGetReporteValor

Tiempo: 8 horas
        4 horas (charts + exports)
        4 horas (testing)
```

#### Miércoles 9-10 Febrero (16 horas)

```
TAREA 7: StockAlerts (Sistema de Alertas)
├─ Crear: StockAlertsView.jsx
├─ Features:
│  ├─ Items con stock <= stock mínimo
│  ├─ Items sin stock
│  ├─ Items próximos a vencer
│  ├─ Crear OC automática (sugerencia)
│  ├─ Marcar alerta como resuelta
│  ├─ Historial de alertas
│  └─ Configurar umbrales por item
└─ Notificaciones en tiempo real

Componentes nuevos: 4
- StockAlertsView.jsx
- StockAlertCard.jsx
- StockAlertDetail.jsx
- CrearOCAutomatica.jsx

API Hooks:
- useGetStockAlerts
- useMarkAlertResolved

Tiempo: 8 horas
        4 horas (real-time updates)
        4 horas (testing)
```

#### Jueves 10-11 Febrero (16 horas)

```
TAREA 8: Categorías + Unidades (CRUD)
├─ Crear: CategoriasView.jsx
├─ Crear: UnidadesView.jsx
├─ Features:
│  ├─ CRUD de categorías
│  ├─ CRUD de unidades de medida
│  ├─ Validaciones
│  └─ Uso en otros módulos
└─ Permisos: inventario.configuracion.*

Componentes nuevos: 4
- CategoriasView.jsx
- CategoriasFormModal.jsx
- UnidadesView.jsx
- UnidadesFormModal.jsx

API Hooks:
- useGetCategorias
- useGetUnidades
- useCRUDCategorias
- useCRUDUnidades

Tiempo: 4 horas
        2 horas (forms)
        2 horas (testing)
```

#### Viernes 11-12 Febrero (16 horas)

```
TAREA 9: Testing + Bug Fixes
├─ Unit tests: Todos los componentes
├─ Integration tests: Flujos
├─ E2E tests: Rutas principales
└─ Coverage: 75% mínimo

Tiempo: 8 horas
        4 horas (fixes)
        4 horas (refinement)
```

### ENTREGABLES SEMANA 4-5

```
✅ InventarioDashboard
✅ ItemsView (CRUD)
✅ KardexView (movimientos)
✅ ActivosView (CRUD)
✅ MovimientosView (entrada/salida)
✅ InventarioReportes (6 reportes)
✅ StockAlertsView
✅ CategoriasView + UnidadesView

Total: 39 componentes nuevos
Status: ✓ CRÍTICO COMPLETADO
```

---

## 📅 SEMANA 6: COMPRAS (14-21 Febrero)

### OBJETIVO
Implementar módulo de Compras

#### Lunes 14-15 Febrero (8 horas)

```
TAREA 1: ComprasDashboard
├─ KPIs: OC pendientes, monto total, etc.
├─ Charts: Gasto por proveedor
├─ Recent: Últimas compras

Componentes: 5
Tiempo: 4 horas (desarrollo) + 2 testing + 2 refinement
```

#### Martes 15-16 Febrero (8 horas)

```
TAREA 2: ComprasListView + ComprasFormModal
├─ CRUD de compras
├─ Crear OC
├─ Ver estado
├─ Ver historial

Componentes: 7
Tiempo: 4 horas + 2 testing + 2 refinement
```

#### Miércoles 16-17 Febrero (8 horas)

```
TAREA 3: ProveedoresView + Contactos
├─ CRUD proveedores
├─ Historial de compras
├─ Evaluación de proveedores

Componentes: 6
Tiempo: 4 horas + 2 testing + 2 refinement
```

#### Jueves 17-18 Febrero (8 horas)

```
TAREA 4: ComprasReportes
├─ Reporte de compras por proveedor
├─ Gastos mensuales
├─ Evaluación de proveedores

Componentes: 4
Tiempo: 4 horas + 2 testing + 2 refinement
```

#### Viernes 18-19 Febrero (8 horas)

```
TAREA 5: Integración con Inventario
├─ Al registrar compra → actualizar kardex
├─ Validaciones
├─ Testing

Tiempo: 4 horas + 2 testing + 2 refinement
```

### ENTREGABLES SEMANA 6

```
✅ ComprasDashboard
✅ ComprasView (CRUD)
✅ ProveedoresView (CRUD)
✅ ComprasReportes
✅ Integración Inventario

Total: 22 componentes nuevos
Status: ✓ COMPLETADO
```

---

## 📅 SEMANA 7: CONTABILIDAD (21-28 Febrero)

### OBJETIVO
Implementar módulo de Contabilidad

#### Lunes 21-22 Febrero (8 horas)

```
TAREA 1: ContabilidadDashboard
├─ KPIs: Balance, ingresos, gastos
├─ Charts: P&L, flujo de caja

Componentes: 4
Tiempo: 4 + 2 + 2
```

#### Martes 22-23 Febrero (8 horas)

```
TAREA 2: LibrosResumenView
├─ Ver libros resumen
├─ Generar libro
├─ Detalles

Componentes: 4
Tiempo: 4 + 2 + 2
```

#### Miércoles 23-24 Febrero (8 horas)

```
TAREA 3: ReportesContables
├─ Balance general
├─ Estado de resultados
├─ Flujo de caja

Componentes: 4
Tiempo: 4 + 2 + 2
```

#### Jueves 24-25 Febrero (8 horas)

```
TAREA 4: Testing
├─ Unit + integration + E2E
└─ Coverage 70%

Tiempo: 8
```

### ENTREGABLES SEMANA 7

```
✅ ContabilidadDashboard
✅ LibrosResumenView
✅ ReportesContables
✅ Testing completo

Total: 12 componentes nuevos
Status: ✓ COMPLETADO
```

---

## 📅 SEMANA 8: AUDITORÍA + QA (28 Feb - 7 Marzo)

### OBJETIVO
Auditoría, notificaciones, perfil usuario, testing final

#### Lunes 28 Feb - Martes 1 Marzo (16 horas)

```
TAREA 1: AuditoriaView + Logs
├─ Visualizar logs de auditoría
├─ Filtros avanzados
├─ Exportar CSV/PDF

Componentes: 5
Tiempo: 8 + 4 testing + 4 refinement
```

#### Miércoles 2-3 Marzo (16 horas)

```
TAREA 2: ProfileView + Cambiar Contraseña
├─ Ver/editar perfil
├─ Cambiar contraseña
├─ Foto de perfil (upload)

Componentes: 4
Tiempo: 8 + 4 testing + 4 refinement
```

#### Jueves 3-4 Marzo (16 horas)

```
TAREA 3: NotificationsSystem
├─ Notification center
├─ Toast notifications
├─ Sistema de alertas

Componentes: 6
Tiempo: 8 + 4 testing + 4 refinement
```

#### Viernes 4-5 Marzo (16 horas)

```
TAREA 4: Testing COMPLETO
├─ Unit tests: 80% coverage
├─ Integration: Critical paths
├─ E2E: Main flows
├─ Performance: <2s load time

Tiempo: 16 horas
```

#### Sábado 5-6 Marzo (16 horas)

```
TAREA 5: Final QA + Deployment Ready
├─ Bug fixes
├─ Performance optimization
├─ Security scan
├─ Documentation

Tiempo: 16 horas
```

### ENTREGABLES SEMANA 8

```
✅ AuditoriaView
✅ ProfileView
✅ NotificationsSystem
✅ 80% test coverage
✅ Production ready

Total: 15 componentes nuevos
Status: ✓ SISTEMA COMPLETO
```

---

## 📊 RESUMEN TOTAL

```
SEMANA 1-2: FUNDACIÓN
├─ Componentes: 32
├─ Estado: ✓ Completado
└─ Duración: 2 semanas

SEMANA 3: FACTURACIÓN
├─ Componentes: 33
├─ Estado: ✓ Crítico completado
└─ Duración: 1 semana

SEMANA 4-5: INVENTARIO
├─ Componentes: 39
├─ Estado: ✓ Crítico completado
└─ Duración: 2 semanas

SEMANA 6: COMPRAS
├─ Componentes: 22
├─ Estado: ✓ Completado
└─ Duración: 1 semana

SEMANA 7: CONTABILIDAD
├─ Componentes: 12
├─ Estado: ✓ Completado
└─ Duración: 1 semana

SEMANA 8: AUDITORÍA + QA
├─ Componentes: 15
├─ Estado: ✓ Completado
└─ Duración: 1 semana

────────────────────────────────────
TOTAL: 8 SEMANAS | 153 COMPONENTES | ~50,000 líneas código
```

---

## ✅ CRITERIOS DE ÉXITO

```
Por fase:

Fundación (Semana 1-2):
☐ Dashboard muestra KPIs en tiempo real
☐ RBAC funcionando (solo ves lo tuyo)
☐ Personas/Usuarios/Roles/Permisos CRUD
☐ 70% test coverage

Facturación (Semana 3):
☐ Emitir comprobante
☐ SUNAT responde OK
☐ PDF se descarga
☐ Pagos registrados
☐ 75% test coverage

Inventario (Semana 4-5):
☐ Stock visible en tiempo real
☐ Movimientos se registran
☐ Kardex calcula correctamente
☐ Alertas de bajo stock
☐ 75% test coverage

Compras (Semana 6):
☐ OC se crean
☐ Se vinculan a inventario
☐ Proveedores gestionados
☐ 70% test coverage

Contabilidad (Semana 7):
☐ Libros resumen generan
☐ Reportes muestran datos
☐ Balance cuadra
☐ 70% test coverage

Auditoría + QA (Semana 8):
☐ Logs auditoría visibles
☐ 80% test coverage
☐ <2s response time
☐ 0 vulnerabilidades críticas
☐ Documentación completada
```

---

**Roadmap completo y detallado**  
**Listo para iniciar: Lunes 10 de Enero 2026**  
**Fecha de lanzamiento esperado: Sábado 7 de Marzo 2026**  

---
