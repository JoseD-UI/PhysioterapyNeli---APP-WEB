# 📚 GUÍA DE USO - MÓDULOS AGENDA Y CLÍNICO

**Última Actualización**: 10 Enero 2026  
**Versión**: 1.0

---

## 📋 TABLA DE CONTENIDOS

1. [Instalación](#instalación)
2. [Módulo Agenda](#módulo-agenda)
3. [Módulo Clínico](#módulo-clínico)
4. [Testing](#testing)
5. [Troubleshooting](#troubleshooting)

---

## 🔧 INSTALACIÓN

### Paso 1: Instalar Dependencias

```bash
# Backend
composer install

# Frontend
npm install

# Vitest para tests
npm install --save-dev vitest @testing-library/react @testing-library/jest-dom happy-dom
```

### Paso 2: Configurar Variables de Entorno

```bash
# .env
VITE_GOOGLE_CLIENT_ID=your_client_id
VITE_API_BASE_URL=http://localhost:8000/api/v1
```

### Paso 3: Iniciar el Servidor

```bash
# Backend (Terminal 1)
php artisan serve

# Frontend (Terminal 2)
npm run dev

# Tests (Terminal 3 - opcional)
npm run test
```

---

## 📅 MÓDULO AGENDA

### ¿Qué es?

El módulo de Agenda permite gestionar citas de los pacientes con fisioterapeutas. Incluye:

- 📋 **Lista de Citas**: Visualización en lista con filtros
- 📆 **Calendario**: Vista interactiva con drag & drop
- ➕ **Crear/Editar**: Formulario completo con validaciones
- 🔄 **Cambiar Estado**: Pendiente → Confirmada → Completada

### Acceso

```
http://localhost:3000/admin/agenda
```

### Estructura de Carpetas

```
resources/js/features/Agenda/
├── index.jsx                 # Página principal con tabs
├── CitasListView.jsx         # Vista en lista
├── CalendarioView.jsx        # Vista de calendario
├── CitaFormModal.jsx         # Formulario modal
└── ...

resources/js/hooks/
├── useAgenda.js              # Hooks de React Query
└── usePrincipal.js           # Datos de pacientes, fisios, salas

resources/js/services/
├── agendaService.js          # Integración API
└── principalService.js
```

### Funcionalidades Principales

#### 1. **Vista Calendario**

```javascript
// Muestra todas las citas en un calendario interactivo
// - Click en fecha: Crear cita
// - Click en evento: Ver detalles
// - Colores según estado:
//   🟡 Pendiente (amarillo)
//   🟢 Confirmada (verde)
//   🔵 Completada (azul)
//   🔴 Cancelada (rojo)
```

**Ejemplo de Uso**:
1. Accede a `/admin/agenda`
2. Click en "Calendario"
3. Haz clic en cualquier fecha/hora
4. Se abre modal para crear cita
5. Llena los datos y guarda

#### 2. **Vista Lista**

```javascript
// Muestra todas las citas en una lista filtrable
// Filtros disponibles:
// - Estado (Todos, Pendiente, Confirmada, Completada, Cancelada)
// - Fecha desde
// - Fecha hasta
```

**Ejemplo de Uso**:
1. Click en pestaña "Lista de Citas"
2. Usa los filtros para buscar citas específicas
3. Click en "Completar" para marcar como realizada
4. Acciones: Confirmar, Completar, Cancelar

#### 3. **Crear Cita**

```javascript
// Formulario modal con campos:
{
    paciente_id: "uuid",           // Select de pacientes
    fisioterapeuta_id: "uuid",     // Select de fisioterapeutas
    fecha_hora: "2026-01-15 10:00",// DateTime picker
    sala_id: "uuid",               // Select de salas
    tipo_servicio_id: "uuid",      // Select (Masaje, Terapia, etc)
    notas: "string"                // Texto libre
}
```

**Validaciones**:
- Todos los campos requeridos
- Fecha en el futuro
- Paciente y fisioterapeuta distintos (si aplica)

### Permisos Requeridos

```php
'agenda.citas.ver'          // Visualizar citas
'agenda.citas.crear'        // Crear citas
'agenda.citas.editar'       // Editar citas
'agenda.citas.eliminar'     // Eliminar citas
```

### Integración con API Backend

```javascript
// En useAgenda.js se usan estos endpoints:
GET    /api/v1/agenda/citas              // Listar
POST   /api/v1/agenda/citas              // Crear
GET    /api/v1/agenda/citas/:id          // Detalle
PUT    /api/v1/agenda/citas/:id          // Editar
DELETE /api/v1/agenda/citas/:id          // Eliminar
PUT    /api/v1/agenda/citas/:id/estado   // Cambiar estado
```

---

## 🏥 MÓDULO CLÍNICO

### ¿Qué es?

El módulo Clínico gestiona información médica de pacientes:

- 📋 **Historias Clínicas**: Diagnósticos y antecedentes
- 🏃 **Sesiones**: Registro de sesiones de tratamiento

### Acceso

```
http://localhost:3000/admin/clinico
```

### Estructura de Carpetas

```
resources/js/features/Clinico/
├── index.jsx                    # Página principal
├── HistoriaClinicaView.jsx      # Gestión de historias
├── SesionesView.jsx             # Gestión de sesiones
└── ...

resources/js/hooks/
└── useClinico.js                # Todos los hooks de clínico
```

### Funcionalidades Principales

#### 1. **Historias Clínicas**

```javascript
// CRUD de historias clínicas por paciente
{
    paciente_id: "uuid",
    diagnostico: "Lumbalgia crónica",
    anamnesis: "Dolor en espalda baja...",
    observaciones: "Requiere seguimiento...",
    created_at: "2026-01-10",
}
```

**Operaciones**:
- ➕ Nueva historia
- ✏️ Editar existente
- 🗑️ Eliminar
- 👀 Ver detalles

**Ejemplo**:
1. Click en "Historias Clínicas"
2. Click en "Nueva Historia"
3. Completa el formulario
4. Guarda

#### 2. **Sesiones**

```javascript
// Registro de cada sesión de tratamiento
{
    cita_id: "uuid",
    observaciones: "Paciente en buenas condiciones",
    notas_clinicas: "Trabajamos espalda baja...",
    resultados: "Mejoría del 40%",
    proximas_recomendaciones: "Continuar con ejercicios...",
    estado: "completada",
}
```

**Estados de Sesión**:
- 🟡 **Pendiente**: Sesión no realizada aún
- 🟢 **Completada**: Sesión finalizada

**Ejemplo de Workflow**:
1. Paciente tiene una cita (desde módulo Agenda)
2. Cita se marca como "Confirmada"
3. En Clínico → Sesiones → "Nueva Sesión"
4. Selecciona la cita relacionada
5. Llena notas y resultados
6. Marca como "Completar"

### Permisos Requeridos

```php
'clinico.historias.ver'     // Ver historias
'clinico.historias.crear'   // Crear historias
'clinico.historias.editar'  // Editar historias
'clinico.historias.eliminar'// Eliminar historias
'clinico.sesiones.ver'      // Ver sesiones
'clinico.sesiones.crear'    // Crear sesiones
'clinico.sesiones.editar'   // Editar sesiones
'clinico.sesiones.eliminar' // Eliminar sesiones
```

### Integración con API

```javascript
// Endpoints utilizados:
GET    /api/v1/clinico/historias           // Listar
POST   /api/v1/clinico/historias           // Crear
PUT    /api/v1/clinico/historias/:id       // Editar
DELETE /api/v1/clinico/historias/:id       // Eliminar

GET    /api/v1/clinico/sesiones            // Listar
POST   /api/v1/clinico/sesiones            // Crear
PUT    /api/v1/clinico/sesiones/:id        // Editar
DELETE /api/v1/clinico/sesiones/:id        // Eliminar
PUT    /api/v1/clinico/sesiones/:id/completar // Marcar como completada
```

---

## 🧪 TESTING

### Ejecutar Tests

```bash
# Todos los tests
npm run test

# Con interfaz visual
npm run test:ui

# Con cobertura
npm run test:coverage
```

### Estructura de Tests

```
tests/
├── hooks/
│   ├── useAgenda.test.js
│   ├── useClinico.test.js
│   └── usePrincipal.test.js
├── components/
│   ├── CitasListView.test.jsx
│   ├── HistoriaClinicaView.test.jsx
│   └── SesionesView.test.jsx
└── setup.js                 # Configuración global
```

### Escribir un Test

```javascript
import { describe, it, expect, vi } from 'vitest';
import { renderHook } from '@testing-library/react';
import { useGetCitas } from '../hooks/useAgenda';

describe('useGetCitas', () => {
    it('should fetch citas successfully', async () => {
        const { result } = renderHook(() => useGetCitas());
        
        expect(result.current.isLoading).toBeDefined();
        expect(result.current.data).toBeDefined();
    });
});
```

### Cobertura Target: 80%

```
functions: 80%
lines: 80%
branches: 80%
statements: 80%
```

---

## 🌙 DARK MODE

Todos los componentes nuevos soportan Dark Mode automáticamente:

```javascript
// Usar useTheme() para verificar tema
const { theme } = useTheme();

// Aplicar clases condicionalmente
<div className={theme === 'dark' ? 'bg-gray-900 text-white' : 'bg-white text-black'}>
    Contenido
</div>

// O usar clase 'dark' para CSS
<div className={`component ${theme === 'dark' ? 'dark' : ''}`}>
    ...
</div>
```

**CSS para Dark Mode**:
```css
.dark .component {
    background-color: #111827;
    color: #f3f4f6;
}
```

---

## 🐛 TROUBLESHOOTING

### "Error: Cannot find module '@vitejs/plugin-react'"

```bash
# Reinstalar dependencias
npm install
npm install --save-dev @vitejs/plugin-react
```

### "React Query: queryKey must be an array"

```javascript
// ❌ MAL
useQuery('citas', () => agendaService.getCitas());

// ✅ BIEN
useQuery(['citas'], () => agendaService.getCitas());
```

### "TypeError: Cannot read property 'data' of undefined"

```javascript
// Siempre verificar data antes de usar
const { data } = useGetCitas();
const citas = data?.data || [];  // ← Protección
```

### "Modal no se cierra después de guardar"

```javascript
// Verificar que onSuccess dispara onClose()
const { mutate: createCita } = useCreateCita();

createCita(formData, {
    onSuccess: () => {
        onClose(); // ← Debe llamarse aquí
        queryClient.invalidateQueries(['citas']);
    }
});
```

### "Dark Mode no funciona en componente nuevo"

```javascript
// 1. Importar useTheme
import { useTheme } from '../../components/theme-provider';

// 2. Usar en componente
const { theme } = useTheme();

// 3. Aplicar clases
className={theme === 'dark' ? 'dark-classes' : 'light-classes'}
```

### "Tests fallan con 'Cannot find module'"

```bash
# Actualizar vitest.config.js con alias
resolve: {
    alias: {
        '@': path.resolve(__dirname, './resources/js'),
    }
}

# En tests, usar ruta absoluta
import { useGetCitas } from '../../resources/js/hooks/useAgenda';
```

---

## 📞 SOPORTE Y MANTENIMIENTO

- **Documentación API**: Ver `docs/api/`
- **Arquitectura**: Ver `docs/architecture/`
- **Errores**: Revisar `docs/error-codes.md`
- **Seguridad**: Revisar `docs/SECURITY_REVIEW_2026_01_10.md`

---

**¡Listo para usar! Happy coding! 🚀**
