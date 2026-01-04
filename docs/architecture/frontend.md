# 🏗️ Arquitectura Frontend - Fisioterapia ERP

## 🎯 Visión General

El sistema es una Single Page Application (SPA) construida con **Laravel (Backend)** y **React (Frontend)**. La arquitectura sigue un enfoque **"Roles-First"**, adaptando dinámicamente la interfaz según el tipo de usuario (Público, Cliente, Administrativo).

## 🛠️ Stack Tecnológico

| Capa              | Tecnología             | Justificación                                             |
| ----------------- | ---------------------- | --------------------------------------------------------- |
| **Core**          | React 19 + Vite        | Estándar moderno, rápido y ya configurado.                |
| **Estilos**       | Tailwind CSS v4        | Diseño rápido, flexible y sistema de diseño unificado.    |
| **Routing**       | React Router v7        | Manejo robusto de rutas anidadas y protecciones.          |
| **Estado**        | **Zustand**            | Gestión de estado global ligera (Usuario, Carrito, UI).   |
| **Data Fetching** | **TanStack Query**     | Manejo de caché, loading states y sincronización con API. |
| **Formularios**   | React Hook Form + Zod  | Validación de esquemas robusta y performante.             |
| **UI Components** | Radix UI / Headless UI | Primitivas accesibles para modales, popovers, etc.        |
| **Http Client**   | Axios                  | Configurado con interceptores para Tokens y CSRF.         |

## 👥 Experiencia de Usuario por Roles

### 1. 🌐 Zona Pública (Landing Page)

**Usuarios:** Visitantes anónimos y Clientes potenciales.

-   **Header:** Navegación, Login, Carrito.
-   **Hero Section:** "WOW Factor" con animaciones y propuesta de valor.
-   **Servicios:** Catálogo de terapias y masajes.
-   **E-commerce:** Compra de productos o paquetes de sesiones.
-   **Booking (Light):** Formulario rápido para solicitar cita (Lead).

### 2. 👤 Zona Privada Cliente (Patient Portal)

**Usuarios:** Pacientes registrados.

-   **Dashboard:** Resumen de próximas citas y saldo pendiente.
-   **Mis Citas:** Calendario personal, reprogramación.
-   **Historial:** Ver resultados de sesiones pasadas.
-   **Pagos:** Ver historial de compras y facturas.

### 3. 🏢 Zona Administrativa (ERP)

**Usuarios:** Admin, Recepción, Fisioterapeutas, Contadores.
_El acceso a los menús se controla vía Permisos (`scope`)._

| Módulo          | Funcionalidad Principal                       | Permiso Requerido                 |
| --------------- | --------------------------------------------- | --------------------------------- |
| **Dashboard**   | KPIs, Gráficos de ventas, citas del día.      | `dashboard.ver`                   |
| **Agenda**      | Calendario drag-and-drop, gestión de estados. | `agenda.citas.ver`                |
| **Pacientes**   | CRM, Historias Clínicas, Ficha técnica.       | `principal.personas.ver`          |
| **Inventario**  | Kardex, Stock, Activos Fijos.                 | `inventario.items.ver`            |
| **Facturación** | Punto de Venta (POS), Caja, SUNAT.            | `facturacion.comprobantes.emitir` |
| **Reportes**    | Exportación Excel/PDF, Contabilidad.          | `reportes.ver`                    |
| **Config**      | Usuarios, Roles, Permisos del sistema.        | `seguridad.permisos.ver`          |

## 📂 Estructura de Carpetas (Feature-First)

```bash
resources/js/
├── assets/                 # Imágenes, fuentes, iconos globales
├── components/             # UI Kit Global (Atomos/Moleculas)
│   ├── ui/                 # Button, Input, Card, Modal (Genéricos)
│   └── layout/             # Navbar, Sidebar, Footer
├── features/               # Módulos de negocio (Organismos)
│   ├── public/             # Landing Page & Store
│   │   ├── components/
│   │   └── pages/          # Home, Services, Contact
│   ├── auth/               # Login, Register, ForgotPassword
│   ├── admin/              # Panel ERP
│   │   ├── agenda/         # Componentes de Calendario
│   │   ├── pacientes/      # Tablas y formularios de pacientes
│   │   ├── inventario/
│   │   └── dashboard/
│   └── client/             # Portal del Paciente
│       ├── appointments/
│       └── profile/
├── hooks/                  # useAuth, usePermissions, useCart
├── lib/                    # Configuraciones (axios.js, utils.js)
├── stores/                 # Stores de Zustand (authStore.js, uiStore.js)
├── routes/                 # Definiciones de rutas (AppRoutes.jsx)
└── services/               # Llamadas a API (agendaService.js, authService.js)
```

## 🔐 Seguridad y Autenticación

1.  **JWT / Sanctum:** El token se almacena en `localStorage` o Cookies seguras.
2.  **Interceptores Axios:**
    -   **Request:** Inyecta automáticamente `Authorization: Bearer <token>`.
    -   **Response:** Detecta `401 Unauthorized` y redirige a login automáticamente.
3.  **Role Based Access Control (RBAC):**
    -   Componente `<ProtectedRoute />` verifica si el usuario tiene el permiso necesario antes de renderizar la ruta.

## 📅 Plan de Implementación

1.  **Configuración Base:** Instalar librerías (Zustand, React Query, Router).
2.  **Layouts:** Crear `PublicLayout`, `ClientLayout` y `AdminLayout`.
3.  **Auth System:** Implementar Login y recuperación de sesión (`/me`).
4.  **Módulos:** Implementación incremental por feature.
