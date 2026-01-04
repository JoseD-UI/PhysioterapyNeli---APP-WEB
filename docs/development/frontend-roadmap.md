# 📅 Plan de Trabajo Frontend (Roadmap) & Estrategia de Calidad

Este documento define la hoja de ruta para la construcción del frontend del ERP de Fisioterapia, enfocándose en la entrega de código listo para producción (Production-Ready) desde el día uno.

---

## 🛡️ Estrategia de Calidad y Depuración (Zero-Bug Policy)

Para asegurar robustez, aplicaremos el ciclo **"Code-Verify-Commit"** en cada tarea:

1.  **Análisis Estático (Pre-Commit)**
    -   **Linting**: Strict ESLint para prevenir errores de sintaxis y anti-patrones.
    -   **Limpieza**: No dejar `console.log` (excepto errores), código comentado o imports sin usar.
2.  **Verificación de Integración (Smoke Testing)**
    -   **Network Check**: Verificar en la pestaña `Network` que no hay peticiones fallidas (4xx/5xx).
    -   **React DevTools**: Verificar que no hay renderizados innecesarios (re-renders) en componentes pesados.
    -   **Console Cleanliness**: La consola del navegador debe estar **LISMPIA** de advertencias (warnings) de React (como `key` props faltantes).
3.  **Sincronización Backend**
    -   Validar que los payloads JSON enviados coinciden exactamente con lo esperado por los Form Requests de Laravel.
    -   Manejo de errores 422 (Validación) mostrando mensajes amigables al usuario.

---

## 🗺️ Roadmap de Implementación

### 📍 Fase 1: Cimientos y Configuración (Core)

**Objetivo**: Establecer la base técnica sólida y reutilizable.

-   [x] **1.1. Configuración de Librerías Core**
    -   Instalar `zustand` (Store), `@tanstack/react-query` (API State), `react-router-dom` (Rutas), `axios`.
    -   Configurar `axios` interceptors (Manejo automático de Token Auth y Errores 401/403).
    -   Configurar `QueryClient` con políticas de reintento y caché optimizadas.
-   [x] **1.2. Sistema de Diseño (UI Kit)**
    -   Implementar componentes base atómicos (Atomic Design):
        -   `ui/Button`, `ui/Input`, `ui/Card`, `ui/Modal`, `ui/Table`.
    -   Configurar alertas/toasts (Notifications) globales.
-   [x] **1.3. Arquitectura de Autenticación**
    -   Crear `useAuth` hook y `authStore`.
    -   Implementar `ProtectedRoute` que valide Permisos (`can('agenda.ver')`).
    -   **Verificación**: Login exitoso redirige, Token se guarda, Refresh de página mantiene sesión.

### 📍 Fase 2: Experiencia Pública (Landing)

-   [x] **2.1. Layout Público**
    -   Navbar Transparente/Glassmorphism.
    -   Footer corporativo completo.
    -   Estado de Auth en Navbar (Login / Avatar).
-   [x] **2.2. Landing Page "WOW"**
    -   Hero Section con imagen de alto impacto y CTA claro.
    -   Sección de "Servicios Rápidos" (Cards).
    -   Prueba Social (Testimonios/Estadísticas flotantes).
-   [ ] **2.3. Catálogo y Login** (Funcionalidad Clave)
    -   [x] Login Page (Email + Google Button).
    -   [x] Register Page (Datos completos).
    -   Listado de servicios/productos desde API.
    -   Carrito de compras (Persistente en LocalStorage).
    -   **Verificación**: Agregar ítems, recargar página y ver que siguen ahí.

### 📍 Fase 3: Portal del Cliente (Mi Perfil)

**Objetivo**: Autogestión del paciente.

-   [ ] **3.1. Dashboard Cliente**
    -   Resumen: Próxima cita, Saldo pendiente.
-   [ ] **3.2. Gestión de Citas**
    -   Agendar nueva cita (Wizard: Seleccionar Servicio -> Fisio -> Horario -> Confirmar).
    -   **Verificación**: La cita aparece en la base de datos con estado "Pendiente".

### 📍 Fase 4: ERP Administrativo (El Núcleo)

**Objetivo**: Gestión operativa del negocio.
_Se implementará módulo por módulo._

-   [ ] **4.1. Layout Admin**
    -   Sidebar colapsable dinámico (Muestra menús según permisos).
    -   TopHeader con perfil y notificaciones.
-   [ ] **4.2. Módulo de Agenda (Prioridad Alta)**
    -   Vista de Calendario (Semanal/Diario).
    -   Drag & Drop de citas (si es posible) o Modal de Edición.
    -   **Depuración**: Verificar performance al cargar 100+ citas en calendario.
-   [ ] **4.3. Módulo Clínico**
    -   Tablas de Pacientes con filtros server-side (Búsqueda por DNI).
    -   Ficha de Historia Clínica (Formularios complejos).
-   [ ] **4.4. Módulo de Inventario & Ventas (POS)**
    -   Punto de Venta rápido.
    -   Kardex visual.
    -   **Depuración**: Cada venta debe descontar stock en tiempo real (verificar tabla `inventario_items`).

### 📍 Fase 5: Pulido y Entrega

**Objetivo**: Preparar para producción real.

-   [ ] **5.1. Auditoría de Rendimiento**
    -   Revisar bundle size (`npm run build`).
    -   Implementar `Code Splitting` (Lazy loading de rutas Admin).
-   [ ] **5.2. Manejo de Errores Global**
    -   Página 404 personalizada.
    -   Error Boundary (Pantalla "Algo salió mal" en lugar de pantalla blanca).
-   [ ] **5.3. Walkthrough Final**
    -   Recorrido completo de usuario (Registro -> Cita -> Atención -> Pago).

---

## 🚦 Criterios de Aceptación (DoD - Definition of Done)

Para considerar una tarea terminada, debe cumplir:

1.  ✅ **Funcional**: Cumple el requerimiento de negocio.
2.  ✅ **Limpio**: Sin errores en consola ni logs de debug.
3.  ✅ **Responsivo**: Se ve bien en Móvil (375px) y Desktop (1366px+).
4.  ✅ **Integrado**: Conecta con la API real y maneja estados de Loading/Error.
