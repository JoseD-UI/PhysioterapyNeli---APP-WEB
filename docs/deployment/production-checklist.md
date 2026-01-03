# Checklist de Producción

## ✅ Pre-Deployment

### Base de Datos

-   [ ] Configurar MySQL/PostgreSQL en servidor
-   [ ] Actualizar `.env` con credenciales de producción
-   [ ]Ejecutar migraciones: `php artisan migrate --force`
-   [ ] Ejecutar seed solo de datos esenciales (roles, permisos)
-   [ ] Verificar conexión: `php artisan tinker` → `DB::connection()->getPdo()`
-   [ ] Backup automático configurado

### Seguridad

-   [ ] `APP_DEBUG=false` en `.env`
-   [ ] `APP_ENV=production`
-   [ ] Generar nueva `APP_KEY`
-   [ ] Configurar HTTPS (certificado SSL válido)
-   [ ] CORS configurado correctamente
-   [ ] Rate limiting activo
-   [ ] Firewall configurado (solo puertos 80, 443, 22)
-   [ ] Validar permisos de archivos (775 storage, 755 demás)

### Configuración

-   [ ] Cache configurado (Redis recomendado)
-   [ ] Queue configurado para tareas pesadas
-   [ ] Logs centralizados
-   [ ] Monitoring (Sentry, New Relic, etc.)
-   [ ] Backup de `.env` en lugar seguro

### Optimización

-   [ ] Ejecutar: `composer install --optimize-autoloader --no-dev`
-   [ ] Cache de configuración: `php artisan config:cache`
-   [ ] Cache de rutas: `php artisan route:cache`
-   [ ] Cache de vistas: `php artisan view:cache`
-   [ ] Optimizar autoload: `composer dump-autoload -o`

### Tests

-   [ ] Tests de autenticación pasando
-   [ ] Tests de permisos pasando
-   [ ] Tests de módulos críticos pasando
-   [ ] Test de integración con base de datos real
-   [ ] Verificar que no hay TODOs críticos en código

## 🚀 Deployment

### Servidor Web

-   [ ] Nginx/Apache configurado
-   [ ] Document root apuntando a `/public`
-   [ ] PHP-FPM configurado
-   [ ] Límites de memoria PHP: `memory_limit=512M`
-   [ ] Max execution time: `max_execution_time=300`

### Servicios

-   [ ] Queue worker activo: `php artisan queue:work --daemon`
-   [ ] Cron job para scheduler: `* * * * * cd /path && php artisan schedule:run`
-   [ ] Supervisor configurado para workers

### Verificación Post-Deploy

-   [ ] `curl https://tu-dominio.com/up` retorna 200
-   [ ] Login funciona correctamente
-   [ ] Emitir comprobante de prueba
-   [ ] Registrar pago de prueba
-   [ ] Crear cita de prueba
-   [ ] Verificar auditoría funcionando
-   [ ] Revisar logs de errores

## 📊 Monitoring

### Métricas Clave

-   [ ] Response time < 200ms promedio
-   [ ] Error rate < 1%
-   [ ] Database connections activas < 80% del límite
-   [ ] CPU usage < 70% promedio
-   [ ] Memory usage < 80%

### Alerts

-   [ ] Alerta si error 500 > Threshold
-   [ ] Alerta si base de datos no responde
-   [ ] Alerta si disco > 85% usado
-   [ ] Alerta si certificado SSL por vencer

## 🔄 Post-Production

### Backups

-   [ ] Backup diario de base de datos
-   [ ] Backup semanal de código
-   [ ] Backup de archivos subidos (storage)
-   [ ] Plan de recuperación ante desastres (DRP)

### Mantenimiento

-   [ ] Actualizar dependencias mensualmente
-   [ ] Revisar logs semanalmente
-   [ ] Optimizar queries lentos
-   [ ] Revisar y limpiar audit logs antiguos

---

## Comandos de Producción

### Deployment

```bash
# Pull latest code
git pull origin main

# Install dependencies
composer install --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Clear and cache
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl restart php8.2-fpm
sudo supervisorctl restart all
```

### Rollback

```bash
# Revert code
git checkout previous-tag

# Rollback migration
php artisan migrate:rollback --step=1

# Clear cache
php artisan optimize:clear
```

---

**Ver también**:

-   [MySQL Setup](mysql-setup.md)
-   [Server Requirements](server-requirements.md)
