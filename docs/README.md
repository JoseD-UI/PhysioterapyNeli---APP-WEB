# 📚 Documentación - API Fisioterapia

Bienvenido a la documentación completa del sistema de gestión para centros de fisioterapia.

## 📂 Estructura de Documentación

### 🏗️ [Arquitectura](architecture/)

-   **[Visión General](architecture/overview.md)**: Arquitectura general del sistema
-   **[Patrones de Diseño](architecture/patterns.md)**: Repository, Service, Observer
-   **[Diagram de Base de Datos](architecture/database-diagram.md)**: ERD y relaciones
-   **[Seguridad](architecture/security.md)**: Autenticación y autorización

### 📦 [Módulos](modules/)

-   **[Autenticación](modules/authentication.md)**: Sanctum, login, registro
-   **[Principal](modules/principal.md)**: Personas, Usuarios, Roles, Permisos
-   **[Agenda](modules/agenda.md)**: Citas, horarios, validaciones
-   **[Clínico](modules/clinico.md)**: Historias clínicas, sesiones
-   **[Facturación](modules/facturacion.md)**: Comprobantes, pagos, SUNAT
-   **[Inventario](modules/inventario.md)**: Items, Kardex, activos
-   **[Compras](modules/compras.md)**: Compras y proveedores
-   **[Seguridad](modules/seguridad.md)**: Auditoría y logs

### 🔧 [Desarrollo](development/)

-   **[Configuración Inicial](development/setup.md)**: Instalación paso a paso
-   **[Contribución](development/contributing.md)**: Guía para contribuidores
-   **[Estándares de Código](development/coding-standards.md)**: Best practices
-   **[Testing](development/testing.md)**: Guía de tests

### 🚀 [Deployment](deployment/)

-   **[Checklist de Producción](deployment/production-checklist.md)**: Preparación
-   **[Evaluación de Calidad (Readiness)](deployment/production_readiness.md)**
-   **[MySQL Setup](deployment/mysql-setup.md)**: Configuración de BD
-   **[Requisitos del Servidor](deployment/server-requirements.md)**: Infraestructura
-   **[CI/CD](deployment/ci-cd.md)**: Integración continua

### 📡 [API Reference](api/)

-   **[Autenticación](api/authentication.md)**: Endpoints de auth
-   **[Principal](api/principal.md)**: API del módulo Principal
-   **[Agenda](api/agenda.md)**: API de citas y horarios
-   **[Clínico](api/clinico.md)**: API clínico
-   **[Facturación](api/facturacion.md)**: API de facturación
-   **[Inventario](api/inventario.md)**: API de inventario
-   **[Códigos de Error](api/error-codes.md)**: Referencia de errores

---

## 🚀 Inicio Rápido

1. **Instalación**: Ver [development/setup.md](development/setup.md)
2. **Autenticación**: Ver [api/authentication.md](api/authentication.md)
3. **Módulos**: Explorar [modules/](modules/)

## 🆘 Soporte

Para preguntas y soporte técnico:

-   Email: support@fisioterapia-api.com
-   Issues: GitHub Issues

---

**Última actualización**: Enero 2026
