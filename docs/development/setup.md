# Configuración Inicial - Setup

## Requisitos del Sistema

### Desarrollo

-   **PHP**: >= 8.2
-   **Composer**: >= 2.5
-   **Node.js**: >= 18 (opcional, para assets)
-   **Base de Datos**: SQLite (incluida)

### Producción

-   **PHP**: >= 8.2 con extensiones: pdo, mbstring, xml, bcmath, json
-   **Composer**: >= 2.5
-   **Base de Datos**: MySQL >= 8.0 o PostgreSQL >= 13
-   **Servidor Web**: Nginx o Apache con mod_rewrite
-   **SSL**: Certificado válido (obligatorio para Sanctum)

## Instalación Paso a Paso

### 1. Clonar Repositorio

```bash
git clone https://github.com/tu-org/fisioterapia_api.git
cd fisioterapia_api
```

### 2. Instalar Dependencias

```bash
composer install
```

### 3. Configurar Entorno

```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar application key
php artisan key:generate
```

### 4. Configurar Base de Datos

#### Desarrollo (SQLite - por defecto)

```env
DB_CONNECTION=sqlite
```

El archivo `database/database.sqlite` se crea automáticamente.

#### Producción (MySQL)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fisioterapia_db
DB_USERNAME=usuario
DB_PASSWORD=contraseña_segura
```

### 5. Ejecutar Migraciones y Seeders

```bash
# Migrar y sembrar datos iniciales
php artisan migrate:fresh --seed
```

Esto creará:

-   ✅ Estructura de base de datos completa
-   ✅ Roles: ADMINISTRADOR, PACIENTE, CONTADOR, RECEPCIONISTA, FISIOTERAPEUTA, ALMACENERO
-   ✅ Permisos completos
-   ✅ Estados de citas
-   ✅ Tipos de servicio
-   ✅ Series de facturación

### 6. Iniciar Servidor de Desarrollo

```bash
php artisan serve
```

La API estará disponible en `http://localhost:8000`

## Verificación de Instalación

### 1. Health Check

```bash
curl http://localhost:8000/up
```

### 2. Listar Rutas

```bash
php artisan route:list
```

### 3. Verificar Base de Datos

```bash
php artisan tinker
```

```php
>>> User::count()  // Debe haber usuarios sembrados
>>> \App\Models\Principal\Rol::all()  // Ver roles
```

## Datos de Prueba (Seeders)

Después de `migrate:fresh --seed`:

### Usuario ADMINISTRADOR

```
Email: admin@fisioterapia.local
Password: password
```

### Roles Disponibles

-   ADMINISTRADOR (todos los permisos)
-   RECEPCIONISTA
-   FISIOTERAPEUTA
-   CONTADOR
-   PACIENTE
-   ALMACENERO

## Configuración Adicional

### Configurar CORS (Opcional)

Si necesitas acceso desde frontend en otro dominio:

```bash
php artisan config:publish cors
```

Editar `config/cors.php`:

```php
'paths' => ['api/*'],
'allowed_origins' => ['http://localhost:3000'],  // Tu frontend
```

### Configurar Cache (Opcional)

```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### Configurar Queues (Opcional)

Para tareas pesadas (ej: envío SUNAT):

```env
QUEUE_CONNECTION=database  # o redis
```

```bash
php artisan queue:work
```

## Comandos Útiles

```bash
# Limpiar caché
php artisan optimize:clear

# Ver rutas de auth
php artisan route:list --path=api/v1/auth

# Rollback migraciones
php artisan migrate:rollback

# Refrescar base de datos
php artisan migrate:fresh --seed

# Generar claves de aplicación
php artisan key:generate
```

## Troubleshooting

### Error: "Class 'PDO' not found"

```bash
# Ubuntu/Debian
sudo apt-get install php8.2-pdo php8.2-mysql

# Windows (descomentar en php.ini)
extension=pdo_mysql
```

###Error: "Permission denied" en storage

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Error Sanctum

Verificar `.env`:

```env
SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DRIVER=cookie
```

## Próximos Pasos

1. ✅ Instalación completada
2. 📖 Leer [documentación de módulos](../modules/)
3. 🔐 Probar [autenticación](../api/authentication.md)
4. 📝 Ver [estándares de código](coding-standards.md)
5. 🚀 Consultar [deployment](../deployment/production-checklist.md)

---

**Ver también**:

-   [Deployment en Producción](../deployment/production-checklist.md)
-   [MySQL Setup](../deployment/mysql-setup.md)
