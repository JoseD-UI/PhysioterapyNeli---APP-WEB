## 🎉 BACKEND LISTO PARA PRODUCCIÓN

Este backend está completamente preparado para despliegue en producción con:

-   ✅ Laravel Sanctum para autenticación
-   ✅ Servicios refactorizados por responsabilidad
-   ✅ Manejo centralizado de errores
-   ✅ Sistema de permisos granular
-   ✅ Documentación modularizada completa
-   ✅ Optimización para MySQL/PostgreSQL
-   ✅ Índices de base de datos
-   ✅ Auditoría automática

---

## 📚 Documentación Completa

### 🚀 **Ver [Documentación Modularizada](/docs/README.md)**

La documentación incluye:

-   📖 **[Arquitectura](docs/architecture/overview.md)**: Patrones, capas, diseño
-   📦 **[Módulos](docs/modules/)**: Guías detalladas por módulo
-   🔧 **[Desarrollo](docs/development/setup.md)**: Setup, testing, estándares
-   🚀 **[Deployment](docs/deployment/production-checklist.md)**: Producción checklist
-   📡 **[API Reference](docs/api/)**: Endpoints documentados

---

## 🔑 Autenticación

-   Autenticación API con Laravel Sanctum (tokens)
-   Sistema de permisos granular basado en roles
-   Middleware de autorización por endpoints
-   Auditoría automática de operaciones

### 📦 Módulos del Sistema

#### 👥 Principal

-   Gestión de personas (pacientes, fisioterapeutas, administrativos)
-   Usuarios y autenticación
-   Roles y permisos
-   Salas de atención

#### 🏥 Clínico

-   Tipos de servicios (masajes, terapias, etc.)
-   Historias clínicas
-   Registro de sesiones
-   Vinculación paciente-fisioterapeuta

#### 📅 Agenda

-   Citas con estados (pendiente, confirmada, cancelada, completada)
-   Horarios de fisioterapeutas
-   Días no laborables
-   Control de disponibilidad

#### 💰 Facturación

-   Emisión de comprobantes (Facturas 01, Boletas 03, Tickets 12)
-   Notas de crédito (07)
-   Registro de pagos múltiples métodos
-   Integración con SUNAT (XML, CDR)
-   Series de comprobantes

#### 📦 Inventario

-   Gestión de items (productos, insumos)
-   Sistema Kardex con valorización PROMEDIO
-   Categorías y unidades de medida
-   Activos fijos con control de estado
-   Vista resumen de kardex

#### 🛒 Compras

-   Registro de compras a proveedores
-   Gestión de proveedores
-   Detalles de compra con costos
-   Integración con inventario

#### 📊 Contabilidad

-   Libros resumen
-   Reportes de ventas mensuales
-   Reporte de sesiones
-   Integración con facturación

#### 🔒 Seguridad

-   Logs de auditoría automáticos
-   Exportación de auditoría a CSV
-   Trazabilidad completa de operaciones

---

## 🚀 Instalación

### Requisitos Previos

-   PHP >= 8.2
-   Composer
-   SQLite (desarrollo) / PostgreSQL o MySQL (producción)

### Pasos de Instalación

1. **Clonar el repositorio**

```bash
git clone <url-repositorio>
cd fisioterapia_api
```

2. **Instalar dependencias**

```bash
composer install
```

3. **Configurar variables de entorno**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Editar `.env`** según tus necesidades:

```env
APP_NAME="Fisioterapia API"
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# O para PostgreSQL/MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=fisioterapia
# DB_USERNAME=root
# DB_PASSWORD=
```

5. **Ejecutar migraciones y seeders**

```bash
php artisan migrate:fresh --seed
```

6. **Iniciar servidor de desarrollo**

```bash
php artisan serve
```

La API estará disponible en `http://localhost:8000`

---

## 🔑 Autenticación

Esta API utiliza **Laravel Sanctum** para autenticación basada en tokens.

### Registro de Usuario

```http
POST /api/v1/auth/register
Content-Type: application/json

{
  "name": "Juan Pérez",
  "email": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "nombres": "Juan",
  "apellidos": "Pérez",
  "dni": "12345678",
  "telefono": "999888777"
}
```

**Respuesta:**

```json
{
    "message": "Usuario registrado exitosamente",
    "user": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@example.com"
    },
    "access_token": "1|abc123...",
    "token_type": "Bearer"
}
```

### Login

```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "juan@example.com",
  "password": "password123"
}
```

**Respuesta:**

```json
{
    "message": "Login exitoso",
    "access_token": "2|xyz789...",
    "token_type": "Bearer"
}
```

### Uso del Token

Para acceder a endpoints protegidos, incluye el token en el header:

```http
GET /api/v1/principal/personas
Authorization: Bearer 2|xyz789...
```

### Cerrar Sesión

```http
POST /api/v1/auth/logout
Authorization: Bearer 2|xyz789...
```

---

##📡 Endpoints Principales

### Autenticación

-   `POST /api/v1/auth/register` - Registro
-   `POST /api/v1/auth/login` - Login
-   `POST /api/v1/auth/logout` - Logout _(requiere auth)_
-   `GET /api/v1/auth/me` - Usuario actual _(requiere auth)_

### Principal _(requiere auth + permisos)_

-   `GET /api/v1/principal/personas` - Listar personas
-   `POST /api/v1/principal/personas` - Crear persona
-   `GET /api/v1/principal/personas/{id}` - Ver persona
-   `PUT /api/v1/principal/personas/{id}` - Actualizar persona
-   `DELETE /api/v1/principal/personas/{id}` - Eliminar persona

_Similar estructura para: usuarios, roles, salas, permisos_

### Agenda _(requiere auth + permisos)_

-   `GET /api/v1/agenda/citas` - Listar citas
-   `POST /api/v1/agenda/citas` - Crear cita
-   `GET /api/v1/agenda/estados` - Estados de citas
-   `GET /api/v1/agenda/horarios` - Horarios de fisioterapeutas

### Facturación _(requiere auth + permisos)_

-   `POST /api/v1/facturacion/comprobantes` - Emitir comprobante
-   `POST /api/v1/facturacion/comprobantes/{id}/anular` - Anular
-   `POST /api/v1/facturacion/pagos` - Registrar pago

### Inventario _(requiere auth + permisos)_

-   `GET /api/v1/inventario/items` - Listar items
-   `POST /api/v1/inventario/items` - Crear item
-   `GET /api/v1/inventario/kardex/{itemId}` - Kardex de item
-   `GET /api/v1/inventario/kardex-resumen` - Resumen general

### SUNAT _(requiere auth + permisos)_

-   `POST /api/v1/sunat/comprobantes/{id}/enviar` - Enviar a SUNAT
-   `GET /api/v1/sunat/comprobantes/{id}/estado` - Consultar estado

Ver [routes/api.php](file:///c:/Users/LENOVO/Desktop/fisioterapia_api/routes/api.php) para la lista completa.

---

## 🔐 Sistema de Permisos

Los permisos siguen la estructura: `modulo.recurso.accion`

Ejemplos:

-   `principal.personas.ver` - Ver personas
-   `agenda.citas.crear` - Crear citas
-   `facturacion.comprobantes.emitir` - Emitir comprobantes
-   `inventario.items.editar` - Editar items

### Roles Predefinidos

| Rol           | ID  | Permisos                         |
| ------------- | --- | -------------------------------- |
| ADMINISTRADOR | 1   | Todos los permisos               |
| PACIENTE      | 2   | Permisos básicos                 |
| CONTADOR      | 3   | Facturación, contabilidad, SUNAT |
| RECEPCIONISTA | 4   | Agenda, emisión de comprobantes  |
| ALMACENERO    | 6   | Inventario, compras              |

---

## 🏗️ Arquitectura

```
app/
├── Http/
│   ├── Controllers/Api/     # Controladores por módulo
│   ├── Middleware/          # CheckPermiso, AuditAction
│   ├── Requests/            # Form Requests (validación)
│   └── Resources/           # API Resources (transformación)
├── Models/                  # Modelos Eloquent por módulo
├── Services/                # Lógica de negocio
├── Repositories/            # Abstracción de acceso a datos
└── Observers/               # AuditObserver (auditoría automática)

database/
├── migrations/             # 53 migraciones + Sanctum
└── seeders/                # Datos iniciales (roles, permisos, etc.)

routes/
└── api.php                 # Definición de rutas API
```

### Patrón de Diseño

-   **Controllers**: Reciben requests, validan, delegan a Services
-   **Services**: Lógica de negocio, coordinan Repositories
-   **Repositories**: Acceso a datos (queries optimizadas)
-   **Models**: Eloquent, relaciones entre entidades
-   **Observers**: Auditoría automática de cambios

---

## 🔍 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests específicos
php artisan test --filter=AuthTest
```

_(Por implementar - actualmente sin tests)_

---

## 📊 Base de Datos

### SQLite (Desarrollo)

Por defecto usa SQLite en `database/database.sqlite`

### PostgreSQL/MySQL (Producción)

Actualiza `.env`:

```env
D B_CONNECTION=pgsql  # o mysql
DB_HOST=127.0.0.1
DB_PORT=5432          # 3306 para MySQL
DB_DATABASE=fisiote rapia_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

Luego ejecuta:

```bash
php artisan migrate:fresh --seed
```

---

## 🛠️ Comandos Útiles

```bash
# Limpiar caché
php artisan optimize:clear

# Ver rutas
php artisan route:list

# Crear modelo con migración, factory, seeder
php artisan make:model NombreModelo -mfs

# Rollback de última migración
php artisan migrate:rollback

# Refrescar base de datos
php artisan migrate:fresh --seed
```

---

## 📝 Licencia

Este proyecto es de código cerrado. Todos los derechos reservados.

---

## 👥 Equipo

Desarrollado para [Nombre del Centro de Fisioterapia]

---

## 📞 Soporte

Para soporte técnico, contactar a: [tu-email@example.com]
