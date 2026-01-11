# 🚀 GUÍA DE DESPLIEGUE - MÓDULO CLÍNICO 2026

**Versión**: 1.0  
**Fecha**: 10 Enero 2026  
**Estado**: ✅ LISTO PARA PRODUCCIÓN

---

## 📋 CHECKLIST PRE-DESPLIEGUE

### Verificaciones Locales

```
Antes de desplegar a producción, ejecuta:

□ npm install --legacy-peer-deps
□ npm run build
□ npm run dev
□ http://localhost:3000/admin/clinico-usuario ✓
□ http://localhost:3000/admin/clinico-admin ✓
□ Dark mode funciona ✓
□ Responsive en móvil ✓
□ No hay errores en consola ✓
□ Network tab muestra requests correctos ✓
```

### Verificaciones Backend

```
□ Las rutas de API existen
  GET    /api/v1/clinico/historias
  POST   /api/v1/clinico/historias
  PUT    /api/v1/clinico/historias/:id
  DELETE /api/v1/clinico/historias/:id
  
  GET    /api/v1/clinico/sesiones
  POST   /api/v1/clinico/sesiones
  PUT    /api/v1/clinico/sesiones/:id
  DELETE /api/v1/clinico/sesiones/:id

□ Los permisos están configurados
  clinico.historias.ver
  clinico.historias.crear
  clinico.historias.editar
  clinico.historias.eliminar
  clinico.sesiones.ver
  clinico.sesiones.crear
  clinico.sesiones.editar
  clinico.sesiones.eliminar

□ Base de datos migrada
  php artisan migrate

□ Seeders ejecutados (si aplica)
  php artisan db:seed
```

---

## 🔧 PASOS DE INSTALACIÓN

### 1. Clonar o Actualizar Repositorio

```bash
# Si es primera vez
git clone <repo-url>
cd fisioterapia_api

# Si ya existe
git pull origin main
```

### 2. Instalar Dependencias

```bash
# Backend
composer install

# Frontend
npm install --legacy-peer-deps
```

### 3. Configurar Variables de Entorno

```bash
# Copiar .env
cp .env.example .env

# Generar key
php artisan key:generate

# Configurar:
# - DB_CONNECTION
# - DB_HOST
# - DB_PORT
# - DB_DATABASE
# - DB_USERNAME
# - DB_PASSWORD
# - APP_URL
# - VITE_API_BASE_URL
```

### 4. Ejecutar Migraciones

```bash
# Migrar base de datos
php artisan migrate

# Si necesitas agregar permisos
php artisan db:seed --class=PermisosSeeder
```

### 5. Build Frontend

```bash
# Desarrollo
npm run dev

# Producción
npm run build
```

---

## 🌍 DESPLIEGUE EN PRODUCCIÓN

### Opción 1: Heroku

```bash
# 1. Crear aplicación
heroku create nombre-app

# 2. Configurar variables
heroku config:set APP_KEY=$(php artisan key:generate --show)
heroku config:set VITE_API_BASE_URL=https://nombre-app.herokuapp.com

# 3. Push código
git push heroku main

# 4. Ejecutar migraciones
heroku run php artisan migrate

# 5. Verificar
heroku open
```

### Opción 2: DigitalOcean App Platform

```bash
# 1. Crear app en DigitalOcean
#    - Conectar repo GitHub
#    - Seleccionar branch: main

# 2. Configurar:
#    - NODE_ENV=production
#    - APP_KEY=<tu-key>
#    - DB_HOST=<tu-host>
#    - etc.

# 3. Deploy automático
#    Cada push a main dispara deploy automático
```

### Opción 3: VPS (Ubuntu 22.04)

```bash
# 1. Conectarse al servidor
ssh user@your-ip

# 2. Instalar dependencias
sudo apt update
sudo apt install -y nodejs npm php composer mysql-server

# 3. Clonar repositorio
git clone <repo-url>
cd fisioterapia_api

# 4. Instalar dependencias
composer install
npm install --legacy-peer-deps

# 5. Configurar permisos
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# 6. Ejecutar migraciones
php artisan migrate

# 7. Build frontend
npm run build

# 8. Configurar Nginx/Apache
# (Ver configuración más abajo)

# 9. Iniciar con PM2
npm install -g pm2
pm2 start "npm run dev" --name "fisioterapia"
pm2 startup
pm2 save
```

---

## ⚙️ CONFIGURACIÓN NGINX

```nginx
server {
    listen 80;
    server_name tu-dominio.com;

    root /home/user/fisioterapia_api/public;
    index index.php index.html;

    # Frontend (React)
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # API Backend
    location /api {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Cache assets
    location ~* \.(js|css|png|jpg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # SSL (Let's Encrypt)
    listen 443 ssl;
    ssl_certificate /etc/letsencrypt/live/tu-dominio.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/tu-dominio.com/privkey.pem;
}

# Redirigir HTTP a HTTPS
server {
    listen 80;
    server_name tu-dominio.com;
    return 301 https://$server_name$request_uri;
}
```

---

## 📦 VARIABLES DE ENTORNO PRODUCCIÓN

```env
# .env.production

# APP
APP_NAME="Fisioterapia API"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxxxx
APP_URL=https://tu-dominio.com

# BASE DE DATOS
DB_CONNECTION=mysql
DB_HOST=tu-host
DB_PORT=3306
DB_DATABASE=fisioterapia_prod
DB_USERNAME=user_prod
DB_PASSWORD=password_seguro

# FRONTEND
VITE_API_BASE_URL=https://tu-dominio.com/api/v1
VITE_GOOGLE_CLIENT_ID=tu-client-id

# MAIL
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=tu-username
MAIL_PASSWORD=tu-password
MAIL_FROM_ADDRESS=noreply@tu-dominio.com

# REDIS (opcional)
REDIS_HOST=localhost
REDIS_PASSWORD=null
REDIS_PORT=6379

# SEGURIDAD
SESSION_DRIVER=cookie
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

---

## 🧪 VERIFICACIÓN POST-DESPLIEGUE

### Test 1: Accesibilidad

```bash
# Verificar que la app responde
curl https://tu-dominio.com

# Esperado: HTML de la aplicación
```

### Test 2: API

```bash
# Listar historias
curl -H "Authorization: Bearer tu-token" \
  https://tu-dominio.com/api/v1/clinico/historias

# Esperado: JSON con historias
```

### Test 3: Dark Mode

```
1. Abre https://tu-dominio.com/admin/clinico-usuario
2. Click en toggle de tema 🌙
3. Deberías ver dark mode activarse
```

### Test 4: Responsive

```
1. Abre DevTools (F12)
2. Toggle device toolbar (Ctrl+Shift+M)
3. Prueba en iPhone, iPad, Pixel
4. Todo debe verse correctamente
```

---

## 🔒 MEDIDAS DE SEGURIDAD

### Before Deploying

```
✅ Cambiar APP_KEY
✅ Set APP_DEBUG=false
✅ Usar HTTPS con certificado válido
✅ Configurar CORS correctamente
✅ Implementar rate limiting
✅ Configurar headers de seguridad
✅ Usar variables de entorno para secrets
✅ Backups automáticos de BD
✅ Logs centralizados
✅ Monitoreo de errores
```

### Configuración CORS (api.php)

```php
// config/cors.php
'allowed_origins' => [
    'https://tu-dominio.com',
    'https://www.tu-dominio.com'
],

'allowed_methods' => ['*'],

'allowed_headers' => ['*'],

'exposed_headers' => [],

'max_age' => 0,

'supports_credentials' => true,
```

### Headers de Seguridad (Middleware)

```php
// app/Http/Middleware/SetSecurityHeaders.php
$response->header('X-Frame-Options', 'DENY');
$response->header('X-Content-Type-Options', 'nosniff');
$response->header('X-XSS-Protection', '1; mode=block');
$response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
$response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
```

---

## 📊 MONITOREO Y LOGS

### Configurar Logs

```env
# .env
LOG_CHANNEL=daily
LOG_LEVEL=warning
LOG_MAX_FILES=14
```

### Monitoring Tools

```
Recomendados:
✅ New Relic (Performance)
✅ Sentry (Error tracking)
✅ Datadog (Infrastructure)
✅ UptimeRobot (Availability)
```

### Verificar Logs

```bash
# Logs en tiempo real
tail -f storage/logs/laravel.log

# Últimas 100 líneas
tail -100 storage/logs/laravel.log

# Buscar errores
grep "ERROR" storage/logs/laravel.log
```

---

## 🔄 CI/CD PIPELINE

### GitHub Actions

```yaml
# .github/workflows/deploy.yml
name: Deploy

on:
  push:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: actions/setup-node@v3
      - run: npm install --legacy-peer-deps
      - run: npm run build
  
  deploy:
    needs: test
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - run: |
          ssh user@server << 'EOF'
          cd /var/www/fisioterapia_api
          git pull origin main
          npm install --legacy-peer-deps
          npm run build
          php artisan migrate
          EOF
```

---

## 🆘 TROUBLESHOOTING PRODUCCIÓN

### Problema: API no responde

```
1. Verificar logs: tail -f storage/logs/laravel.log
2. Verificar PHP: php -v
3. Verificar conexión BD: php artisan tinker
4. Verificar permisos: ls -la storage/
```

### Problema: Frontend no carga

```
1. Verificar build: npm run build
2. Verificar assets: ls -la public/
3. Verificar permisos: chmod -R 755 public/
4. Limpiar cache: rm -rf node_modules/.vite
```

### Problema: Dark mode no funciona

```
1. Verificar tema en localStorage
2. Verificar CSS se cargó: Inspector → Styles
3. Verificar variables CSS
4. Limpiar caché del navegador (Ctrl+Shift+Delete)
```

### Problema: Lentitud

```
1. Verificar React Query cache
2. Optimizar queries BD
3. Usar CDN para assets
4. Comprimir imágenes
5. Configurar gzip en nginx
```

---

## 📈 ESCALABILIDAD

### Cuando crece el tráfico:

```
1. Implementar caché (Redis)
2. Configurar queue (Laravel Horizon)
3. Usar CDN (Cloudflare)
4. Balancer de carga
5. Read replicas de BD
6. Separar frontend de backend
```

---

## 🎯 TESTING EN PRODUCCIÓN

### Smoke Tests

```bash
# 1. ¿Responde la aplicación?
curl -I https://tu-dominio.com

# 2. ¿Login funciona?
curl -X POST https://tu-dominio.com/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"password"}'

# 3. ¿API responde?
curl https://tu-dominio.com/api/v1/clinico/historias \
  -H "Authorization: Bearer TOKEN"

# 4. ¿Vistas cargan?
curl https://tu-dominio.com/admin/clinico-usuario
```

---

## 📞 SOPORTE POST-DEPLOY

```
Si algo falla después del deploy:

1. Revisar logs: storage/logs/laravel.log
2. Verificar migraciones: php artisan migrate:status
3. Reimigrar si es necesario: php artisan migrate:refresh
4. Limpiar caché: php artisan cache:clear
5. Limpiar views: php artisan view:clear
6. Regenrar keys: php artisan key:generate
```

---

## ✅ CHECKLIST FINAL

```
ANTES DE PUBLICAR:
□ Tests en local pasando
□ Build sin errores
□ API funcionando
□ BD migrada
□ Permisos configurados
□ HTTPS activo
□ Variables de env correctas
□ Dark mode funciona
□ Responsive verificado

DESPUÉS DE PUBLICAR:
□ Verificar logs
□ Probar vistas principales
□ Probar API endpoints
□ Probar crear/editar/eliminar
□ Probar dark mode
□ Probar en móvil
□ Revisar performance
□ Configurar backups
□ Configurar monitoreo
```

---

**Estado**: ✅ LISTO PARA DESPLEGAR  
**Versión**: 2.0  
**Fecha**: 10 Enero 2026

```
╔════════════════════════════════════════╗
║  🚀 LISTO PARA PRODUCCIÓN 🚀          ║
║                                        ║
║  Sigue esta guía para desplegar       ║
║  correctamente en producción          ║
║                                        ║
║  Cualquier duda: revisar logs         ║
╚════════════════════════════════════════╝
```
