# 🏗️ ARQUITECTURA DE UI/UX - Fisioterapia Sistema

**Fecha**: Enero 10, 2026  
**Versión**: v1.0 - Plan Arquitectónico  

---

## 📐 ESTRUCTURA JERÁRQUICA DE LA UI

```
APP ROOT
│
├── 🌍 BLOQUE PÚBLICO (sin autenticación)
│   ├─ / (Landing Page)
│   ├─ /servicios (Catálogo de servicios)
│   ├─ /sobre-nosotros (Información)
│   ├─ /contacto (Formulario de contacto)
│   ├─ /login (Formulario de inicio de sesión)
│   └─ /registro (Formulario de registro)
│
├── 👤 BLOQUE CLIENTE (con autenticación - paciente)
│   ├─ /dashboard (Mi panel principal)
│   │  ├─ Próximas citas
│   │  ├─ Mi historia clínica (resumen)
│   │  ├─ Últimas sesiones
│   │  └─ Mis comprobantes
│   ├─ /mis-citas (Mis reservas)
│   │  ├─ Listado con calendario
│   │  ├─ Agendar nueva cita
│   │  └─ Cancelar/reprogramar
│   ├─ /mi-historia-clinica (Detalle completo)
│   ├─ /mis-sesiones (Historial de atenciones)
│   ├─ /mis-comprobantes (Facturas/Boletas)
│   ├─ /mis-reportes (Progreso de tratamiento)
│   ├─ /perfil (Editar datos personales)
│   └─ /descargar-app (App mobile)
│
└── ⚙️ BLOQUE ADMINISTRATIVO (con autenticación - admin/roles)
    │
    ├─ /admin (Dashboard principal)
    │  ├─ KPIs generales
    │  ├─ Gráficos de actividad
    │  ├─ Notificaciones
    │  └─ Acceso rápido a módulos
    │
    ├─ 📅 SECCIÓN CLÍNICA
    │  ├─ /admin/clinico
    │  │  ├─ Dashboard clínico
    │  │  ├─ Mis citas (hoy/semana)
    │  │  ├─ Mis sesiones (crear/editar)
    │  │  ├─ Mi agenda
    │  │  ├─ Historial de pacientes
    │  │  └─ Mis reportes
    │  │
    │  ├─ /admin/clinico/gestor (solo admin)
    │  │  ├─ Todas las citas (CRUD)
    │  │  ├─ Todas las historias clínicas (CRUD)
    │  │  ├─ Todas las sesiones (CRUD)
    │  │  ├─ Gestión de servicios
    │  │  ├─ Gestión de horarios
    │  │  └─ Reportes clínicos
    │  │
    │  └─ /admin/clinico/especialista (solo especialista)
    │     ├─ Supervisar tratamientos
    │     ├─ Validar sesiones
    │     └─ Reportes especializados
    │
    ├─ 📦 SECCIÓN INVENTARIO
    │  ├─ /admin/inventario
    │  │  ├─ Dashboard de stock
    │  │  ├─ Ítems (CRUD)
    │  │  ├─ Categorías (CRUD)
    │  │  ├─ Kardex (movimientos)
    │  │  ├─ Activos fijos (CRUD)
    │  │  ├─ Stock bajo (alertas)
    │  │  └─ Reportes de inventario
    │
    ├─ 💰 SECCIÓN FACTURACIÓN
    │  ├─ /admin/facturacion
    │  │  ├─ Dashboard de ingresos
    │  │  ├─ Emitir comprobante
    │  │  ├─ Listado de comprobantes (CRUD)
    │  │  ├─ Gestión de pagos
    │  │  ├─ Estado SUNAT
    │  │  ├─ Series de facturación
    │  │  └─ Reportes de ventas
    │
    ├─ 🛒 SECCIÓN COMPRAS
    │  ├─ /admin/compras
    │  │  ├─ Dashboard de compras
    │  │  ├─ Crear orden de compra (OC)
    │  │  ├─ Listado de compras (CRUD)
    │  │  ├─ Gestión de proveedores (CRUD)
    │  │  ├─ Historial de compras
    │  │  └─ Reportes de compras
    │
    ├─ 📊 SECCIÓN CONTABILIDAD
    │  ├─ /admin/contabilidad
    │  │  ├─ Dashboard financiero
    │  │  ├─ Libros resumen
    │  │  ├─ Asientos contables
    │  │  ├─ Balance general
    │  │  ├─ Estado de resultados
    │  │  └─ Flujo de caja
    │
    ├─ 📈 SECCIÓN REPORTES
    │  ├─ /admin/reportes
    │  │  ├─ Sesiones mensuales (gráfico)
    │  │  ├─ Ingresos por servicio
    │  │  ├─ Clientes más frecuentes
    │  │  ├─ Inventario (gráfico)
    │  │  ├─ Compras (gráfico)
    │  │  ├─ Análisis de proveedores
    │  │  └─ Exportar reportes (PDF/CSV)
    │
    ├─ 👥 SECCIÓN USUARIOS
    │  ├─ /admin/usuarios
    │  │  ├─ Personas (CRUD)
    │  │  ├─ Usuarios (CRUD)
    │  │  ├─ Roles (CRUD)
    │  │  ├─ Asignación de permisos
    │  │  ├─ Activos/Inactivos
    │  │  └─ Historial de accesos
    │
    ├─ 🔒 SECCIÓN SEGURIDAD
    │  ├─ /admin/seguridad
    │  │  ├─ Auditoría (logs)
    │  │  ├─ Filtros avanzados
    │  │  ├─ Exportar auditoría
    │  │  ├─ Gestión de permisos
    │  │  └─ Políticas de seguridad
    │
    ├─ ⚡ SECCIÓN CONFIGURACIÓN
    │  ├─ /admin/configuracion
    │  │  ├─ Parámetros del sistema
    │  │  ├─ Salas disponibles
    │  │  ├─ Días no laborables
    │  │  ├─ Series de facturación
    │  │  ├─ Backup & Export
    │  │  ├─ Email configuration
    │  │  └─ Integraciones externas
    │
    └─ 👤 PERFIL PERSONAL
       ├─ /admin/mi-perfil
       │  ├─ Editar perfil
       │  ├─ Cambiar contraseña
       │  ├─ Foto de perfil
       │  └─ Preferencias
       │
       └─ /logout
```

---

## 🎨 LAYOUT TEMPLATES (3 tipos)

### TEMPLATE 1: Público (Sin Navbar Admin)

```
┌─────────────────────────────────────────────┐
│         HEADER (Logo + Auth buttons)        │
├─────────────────────────────────────────────┤
│                                             │
│              MAIN CONTENT                   │
│              (Landing / Login)              │
│                                             │
├─────────────────────────────────────────────┤
│              FOOTER (Links)                 │
└─────────────────────────────────────────────┘
```

### TEMPLATE 2: Cliente (Navbar Simple)

```
┌────────────────────────────────────────────────┐
│  NAVBAR (Logo + Menu + Perfil + Logout)       │
├────────────────────────────────────────────────┤
│  BREADCRUMB                                    │
├──────────────────────────────────────────────┬─┤
│  MAIN CONTENT                                 │ │
│                                               │ │
│                                               │ │
├──────────────────────────────────────────────┴─┤
│              FOOTER                           │
└────────────────────────────────────────────────┘
```

### TEMPLATE 3: Admin (Sidebar + Navbar)

```
┌────────────────────────────────────────────────┐
│        NAVBAR (Notificaciones + Perfil)        │
├────────────────┬────────────────────────────────┤
│  SIDEBAR       │  MAIN CONTENT                  │
│  (Menú dinám)  │  (Dashboard/Módulos)          │
│  según        │  BREADCRUMB                    │
│  permisos     │                                │
│                │                                │
│                │                                │
│                │                                │
│                │                                │
└────────────────┴────────────────────────────────┘
```

---

## 📱 RESPONSIVE BREAKPOINTS

```
Desktop (> 1200px)      → 3 columnas
Tablet (768px - 1199px) → 2 columnas  
Mobile (< 767px)        → 1 columna (stack)

Sidebar:
Desktop:  Expandido (250px)
Tablet:   Colapsable (toggle)
Mobile:   Modal/Drawer (overlay)
```

---

## 🎯 COMPONENTES POR SECCIÓN

### COMPONENTES COMUNES

```
✓ Navbar
✓ Sidebar (admin only)
✓ Footer
✓ Breadcrumb
✓ Card
✓ Button (variants: primary, secondary, danger, loading)
✓ Input (text, email, password, number, date, etc)
✓ Select / Dropdown
✓ Checkbox
✓ Radio
✓ Toggle Switch
✓ Modal (confirm, form, alert)
✓ Toast (success, error, warning, info)
✓ Loading Spinner
✓ Table (sortable, filterable, paginated)
✓ Pagination
✓ Tabs
✓ Accordion
✓ Badge
✓ Avatar
✓ Alert
✓ Progress Bar
✓ Skeleton Loading
✓ Empty State
✓ Error Boundary
✓ Tooltip
```

### COMPONENTES ESPECÍFICOS POR MÓDULO

#### Clínico

```
- HistoriaClinicaCard (display)
- HistoriaClinicaForm (CRUD)
- SesionCard (display)
- SesionForm (CRUD)
- TipoServicioCard
- TipoServicioModal
- PacienteSelector
- FisioterapeutaSelector
- SesionTimeline
- ProgressChart (gráfico de progreso)
```

#### Agenda

```
- CalendarioView (grid/agenda)
- CitaCard
- CitaForm (CRUD)
- HorarioForm
- DiaNoLaborableForm
- TimeSlotPicker
- AvailableSlots
- CitaEstadoTag
```

#### Facturación

```
- ComprobanteForm
- ComprobanteCard
- ComprobanteTable
- PagoForm
- PagoCard
- SunatStatusBadge
- SeriesManager
- FacturaPreview (PDF)
- VentasChart
```

#### Inventario

```
- StockDashboard
- ItemForm
- ItemCard
- KardexTable
- MovementForm
- ActivoCard
- ActivoForm
- StockAlertBanner
- InventarioChart
- KardexChart
```

#### Compras

```
- CompraForm
- CompraCard
- CompraTable
- ProveedorForm
- ProveedorCard
- CompraTimeline
- ComprasChart
```

#### Contabilidad

```
- LibroResumenForm
- LibroResumenCard
- AsientoTable
- BalanceSheet
- FinancialChart
```

#### Usuarios

```
- PersonaForm
- PersonaCard
- UsuarioForm
- UsuarioCard
- RolForm
- RolCard
- PermisoAssignmentTable
- RolePermissionMatrix
```

#### Auditoría

```
- AuditTable
- AuditFilters
- AuditDetail
- AuditExportButton
```

---

## 🎨 TEMA DE DISEÑO

### COLORES (Dark Mode First)

```
Primary:     #3B82F6 (Azul)
Secondary:   #8B5CF6 (Púrpura)
Success:     #10B981 (Verde)
Warning:     #F59E0B (Naranja)
Danger:      #EF4444 (Rojo)
Info:        #06B6D4 (Cian)

Background Dark:  #0F172A
Surface Dark:     #1E293B
Border Dark:      #334155

Background Light: #FFFFFF
Surface Light:    #F1F5F9
Border Light:     #E2E8F0

Text Dark:        #F8FAFC
Text Light:       #1E293B
```

### TIPOGRAFÍA

```
Font Family: 'Inter', 'SF Pro Display', sans-serif

H1: 32px bold
H2: 28px bold
H3: 24px semibold
H4: 20px semibold
Body: 16px regular
Small: 14px regular
Tiny:  12px regular
```

### ESPACIADO (8px base)

```
xs: 4px
sm: 8px
md: 16px
lg: 24px
xl: 32px
2xl: 48px
```

### BORDER RADIUS

```
none: 0
sm: 4px
base: 8px
lg: 12px
full: 9999px
```

---

## 📊 COMPONENTES DE DATOS

### Tablas

```
Requisitos:
- Sortable (click en header)
- Filterable (input search)
- Paginated (10, 25, 50, 100 rows)
- Selectable (checkboxes)
- Expandable (row detail)
- Exportable (CSV/PDF)
- Loading state
- Empty state
- Error state

Acciones en cada fila:
- Ver detalle (eye icon)
- Editar (pencil icon)
- Eliminar (trash icon)
```

### Gráficos

```
Tipos requeridos:
- Line Chart (series temporales: ingresos, sesiones)
- Bar Chart (categorías: servicios, proveedores)
- Pie Chart (distribución: tipos de servicio)
- Area Chart (progreso: stock over time)
- Gauge (KPIs: ocupación, satisfacción)
- Heatmap (actividad: calendario de citas)

Librería recomendada: Recharts o Chart.js
```

### Cards KPI

```
Componente KPICard:
┌─────────────────────┐
│ 📊 Título            │
│                      │
│   1,234              │
│   +12% desde ayer    │
│                      │
│ [Pequeño gráfico]    │
└─────────────────────┘

Campos:
- Icon/Color
- Title
- Value
- Change (% + direction)
- MinChart
- Clickable (ir a detalle)
```

---

## 🔄 FLUJOS DE USUARIO

### Flujo: Cliente Reservando Cita

```
1. Cliente login ✓
2. Dashboard → Ver próximas citas ✓
3. Clic en "Agendar nueva cita" ✓
4. Seleccionar servicio ✓
5. Seleccionar fisioterapeuta ✓
6. Ver disponibilidad (calendario) ✓
7. Seleccionar fecha/hora ✓
8. Confirmar reserva ✓
9. Recibir confirmación + email ✓
10. Ver cita en calendario ✓
```

### Flujo: Admin Facturando

```
1. Admin login ✓
2. Admin → Facturación ✓
3. Clic en "Emitir comprobante" ✓
4. Seleccionar cliente ✓
5. Agregar ítems (servicios) ✓
6. Sistema calcula: subtotal, IGV, total ✓
7. Revisar datos (cliente, dirección, RUC) ✓
8. Enviar a SUNAT ✓
9. Recibir respuesta (aceptado/rechazado) ✓
10. Descargar PDF ✓
11. Ver comprobante en listado ✓
```

### Flujo: Encargado Inventario Haciendo Compra

```
1. Admin login (rol encargado inventario) ✓
2. Admin → Compras ✓
3. Clic en "Nueva compra" ✓
4. Seleccionar proveedor ✓
5. Agregar ítems (con cantidades) ✓
6. Sistema muestra total ✓
7. Generar OC (orden de compra) ✓
8. Imprimir/enviar proveedor ✓
9. Recibir mercadería ✓
10. Registrar ingreso en kardex ✓
11. Stock se actualiza automáticamente ✓
```

---

## 🚀 PRIORIDADES DE IMPLEMENTACIÓN

### FASE 1: BASE (Semana 1-2)

```
1. AdminLayout con Sidebar dinámico (permiso-based)
2. Dashboard general con KPIs
3. Componentes comunes (Card, Button, Modal, Table)
4. Navegación responsive
5. Sistema de permisos en UI
```

### FASE 2: CRÍTICA (Semana 3-4)

```
1. Facturación (CRUD + SUNAT)
2. Inventario (CRUD + Kardex)
3. Compras (CRUD)
4. Tablas con paginación/filtros
```

### FASE 3: IMPORTANTE (Semana 5-7)

```
1. Contabilidad
2. Reportes con gráficos
3. Auditoría visual
4. Gestión de usuarios
```

### FASE 4: MEJORAS (Semana 8+)

```
1. Notificaciones
2. Perfil de usuario
3. Landing page
4. Mobile responsiveness optimization
5. Performance optimization
```

---

## 📝 GUÍA DE ESTILOS

### Botones

```
Primary: Azul, fondo completo, shadow
Secondary: Gris, outline, sin shadow
Success: Verde, fondo completo
Danger: Rojo, outline o fondo
Loading: Spinner, disabled
```

### Formularios

```
Inputs: Border 1px, focus azul, placeholder gris
Labels: Required con asterisco rojo
Validación: Mensaje debajo del input (rojo)
Success: Checkmark verde a la derecha
Helper text: Texto pequeño gris debajo
```

### Notificaciones

```
Success: Verde, checkmark, "Operación completada"
Error: Rojo, X, mensaje de error específico
Warning: Naranja, !, "Confirma acción"
Info: Azul, i, información
Auto-dismiss: 5 segundos
```

---

## ⚡ PERFORMANCE

```
- Lazy loading en listados grandes
- Virtualization para tablas (1000+ rows)
- Code splitting por módulo
- Images optimizadas (WebP)
- CSS-in-JS minificado
- Caching de queries
- Skeleton loading en data fetch
```

---

## ♿ ACCESIBILIDAD

```
- WCAG 2.1 AA compliance
- Keyboard navigation (Tab, Enter, Escape)
- ARIA labels en componentes
- Color contrast ratios
- Semantic HTML
- Focus indicators visibles
- Testing con screen readers
```

---

**Arquitectura UI/UX completada**  
**Próxima fase**: Implementar AdminLayout + Dashboard General  

---
