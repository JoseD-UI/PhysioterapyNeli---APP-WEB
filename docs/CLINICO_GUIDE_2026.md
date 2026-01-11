# 🏥 MÓDULO CLÍNICO - GUÍA COMPLETA

**Última Actualización**: 10 Enero 2026  
**Versión**: 2.0 - Dual View (Usuario + Admin)

---

## 📋 TABLA DE CONTENIDOS

1. [Descripción General](#descripción-general)
2. [Estructura de Datos](#estructura-de-datos)
3. [Vista de Usuario](#vista-de-usuario)
4. [Vista de Administrador](#vista-de-administrador)
5. [Integración con Backend](#integración-con-backend)
6. [Flujos de Uso](#flujos-de-uso)
7. [Dark Mode](#dark-mode)

---

## 🎯 DESCRIPCIÓN GENERAL

El módulo clínico está diseñado para gestionar la información médica de los pacientes en dos niveles:

### **Vista de Usuario** 🧑‍💼
Permite que los pacientes vean:
- Su historia clínica personal
- Todas sus sesiones completadas
- Servicios consumidos
- Progreso del tratamiento
- Notas y recomendaciones del terapeuta

### **Vista de Administrador** 👨‍⚕️
Permite que los profesionales de salud:
- Crear y editar historias clínicas
- Registrar sesiones de tratamiento
- Gestionar ejercicios y materiales utilizados
- Filtrar y buscar pacientes
- Actualizar diagnósticos y recomendaciones

---

## 📊 ESTRUCTURA DE DATOS

### Tabla: `clinico_historias_clinicas`

```php
[
    'historia_id' => UUID,              // PK
    'persona_id' => UUID,               // FK -> principal_personas
    'motivo_consulta' => text,          // Razón de la consulta
    'antecedentes' => text,             // Antecedentes médicos
    'alergias' => text,                 // ⚠️ CRÍTICO: Alergias conocidas
    'diagnostico_inicial' => text,      // Diagnóstico
    'recomendaciones' => text,          // Plan de tratamiento
    'creado_en' => timestamp,
    'actualizado_en' => timestamp
]
```

### Tabla: `clinico_sesiones`

```php
[
    'sesion_id' => UUID,                // PK
    'cita_id' => UUID,                  // FK -> agenda_citas (nullable)
    'paciente_id' => UUID,              // FK -> principal_personas
    'fisioterapeuta_id' => UUID,        // FK -> principal_personas (nullable)
    'servicio_id' => UUID,              // FK -> clinico_servicios (nullable)
    'fecha_atencion' => timestamp,      // Cuándo fue la sesión
    'duracion_minutos' => smallint,     // Cuánto duró
    'notas' => text,                    // Observaciones
    'ejercicios_realizados' => text,    // Qué se hizo
    'materiales_usados' => json,        // Equipamiento utilizado
    'creado_en' => timestamp
]
```

### Tabla: `clinico_servicios`

```php
[
    'servicio_id' => UUID,              // PK
    'nombre' => string,                 // Ej: "Masaje Terapéutico"
    'descripcion' => text,              // Descripción del servicio
    'duracion_minutos' => smallint,     // Duración estándar
    'precio' => decimal,                // Costo del servicio
    'activo' => boolean,                // ¿Disponible?
    'creado_en' => timestamp
]
```

---

## 👥 VISTA DE USUARIO

### Acceso
```
http://localhost:3000/admin/clinico-usuario
```

### Características

#### 1. **Panel de Estadísticas** (Top)
```
📊 Sesiones Completadas: 3
⏱️ Minutos de Terapia: 195
📅 Última Sesión: 08/01
📈 Progreso: 65%
```

#### 2. **Historia Clínica Expandible**
```
┌─────────────────────────────────────┐
│ Lumbalgia crónica                   │
│ Actualizado: 10/01/2026             │
│                                     │
│ [Click para expandir]               │
│                                     │
│ Motivo de Consulta:                 │
│ Dolor persistente en espalda baja   │
│                                     │
│ Antecedentes:                       │
│ Sin cirugías previas                │
│                                     │
│ ⚠️ Alergias:                         │
│ Alergia a penicilina                │
│                                     │
│ Recomendaciones:                    │
│ Continuar con ejercicios...         │
└─────────────────────────────────────┘
```

#### 3. **Servicios Consumidos**
```
Masaje Terapéutico
├─ Sesiones: 2
└─ Duración: 120 min

Terapia Física
├─ Sesiones: 1
└─ Duración: 45 min
```

#### 4. **Historial de Sesiones**
```
┌─────────────────────────────────────┐
│ Masaje Terapéutico                  │
│ Viernes 08 Enero 2026 - 10:00       │
│                                     │
│ ✓ Completada                        │
│                                     │
│ Duración: 60 min                    │
│ Terapeuta: Dr. Carlos Mendez        │
│                                     │
│ Ejercicios Realizados:              │
│ Estiramientos dorsales, movilidad   │
│                                     │
│ 📊 Resultado: Mejoría del 40%       │
└─────────────────────────────────────┘
```

### Código de Uso

```javascript
import UserClinicView from '../../features/Clinico/UserClinicView';

// En las rutas
<Route path="clinico-usuario" element={<UserClinicView />} />
```

---

## ⚙️ VISTA DE ADMINISTRADOR

### Acceso
```
http://localhost:3000/admin/clinico-admin
```

### Características

#### 1. **Filtro por Paciente**
```
┌─────────────────────────┐
│ Filtrar por Paciente:   │
│ ┌─────────────────────┐ │
│ │ [Todos los pacientes]│ │
│ │ Juan Pérez          │ │
│ │ María García        │ │
│ │ Carlos López        │ │
│ └─────────────────────┘ │
└─────────────────────────┘
```

#### 2. **Tabs: Historias / Sesiones**

##### Historias Clínicas
```
┌─────────────────────────────────────┐
│ ➕ Nueva Historia                    │
├─────────────────────────────────────┤
│ Lumbalgia crónica                   │
│ Actualizado: 10/01/2026             │
│                                     │
│ [✏️ Editar] [🗑️ Eliminar]           │
│                                     │
│ Motivo Consulta: Dolor espalda...   │
│ ⚠️ Alergias: Penicilina             │
└─────────────────────────────────────┘
```

##### Sesiones
```
┌─────────────────────────────────────┐
│ ➕ Nueva Sesión                      │
├─────────────────────────────────────┤
│ Sesión - 08/01/2026                 │
│ Duración: 60 minutos                │
│                                     │
│ [✏️ Editar] [🗑️ Eliminar]           │
│                                     │
│ Notas: Sesión de relajación...      │
│ Ejercicios: Estiramientos...        │
│ Materiales: Balón...                │
└─────────────────────────────────────┘
```

#### 3. **Modal: Nueva Historia Clínica**

```
Crear Historia Clínica

Paciente *
[Seleccionar paciente ▼]

Motivo de Consulta
[Textarea: Describe el motivo...]

Antecedentes
[Textarea: Antecedentes médicos...]

⚠️ Alergias
[Input: Alergias conocidas...]

Diagnóstico Inicial *
[Textarea: Diagnóstico...]

Recomendaciones
[Textarea: Plan de tratamiento...]

[Crear Historia] [Cancelar]
```

#### 4. **Modal: Nueva Sesión**

```
Nueva Sesión

Paciente *
[Seleccionar paciente ▼]

Fisioterapeuta          Duración (minutos) *
[Seleccionar ▼]         [Input: 60]

Fecha y Hora *
[DateTime: 2026-01-10 10:00]

Notas
[Textarea: Observaciones...]

Ejercicios Realizados
[Textarea: Ejercicios...]

Materiales Usados
[Input: Balón, bandas elásticas...]

[Crear Sesión] [Cancelar]
```

### Código de Uso

```javascript
import AdminClinicView from '../../features/Clinico/AdminClinicView';

// En las rutas
<Route path="clinico-admin" element={<AdminClinicView />} />
```

---

## 🔗 INTEGRACIÓN CON BACKEND

### Endpoints Utilizados

#### Historias Clínicas
```
GET    /api/v1/clinico/historias          Listar todas
POST   /api/v1/clinico/historias          Crear nueva
GET    /api/v1/clinico/historias/:id      Ver detalle
PUT    /api/v1/clinico/historias/:id      Actualizar
DELETE /api/v1/clinico/historias/:id      Eliminar
```

#### Sesiones
```
GET    /api/v1/clinico/sesiones           Listar todas
POST   /api/v1/clinico/sesiones           Crear nueva
GET    /api/v1/clinico/sesiones/:id       Ver detalle
PUT    /api/v1/clinico/sesiones/:id       Actualizar
DELETE /api/v1/clinico/sesiones/:id       Eliminar
```

#### Servicios
```
GET    /api/v1/clinico/tipos-servicio     Listar tipos
POST   /api/v1/clinico/tipos-servicio     Crear tipo
PUT    /api/v1/clinico/tipos-servicio/:id Actualizar
DELETE /api/v1/clinico/tipos-servicio/:id Eliminar
```

### Permisos Requeridos

```php
// Para ver historias
'clinico.historias.ver'

// Para crear/editar/eliminar
'clinico.historias.crear'
'clinico.historias.editar'
'clinico.historias.eliminar'

// Para sesiones
'clinico.sesiones.ver'
'clinico.sesiones.crear'
'clinico.sesiones.editar'
'clinico.sesiones.eliminar'
```

### Ejemplo de Request

#### Crear Historia Clínica
```javascript
POST /api/v1/clinico/historias
Content-Type: application/json

{
    "persona_id": "550e8400-e29b-41d4-a716-446655440000",
    "motivo_consulta": "Dolor persistente en espalda baja",
    "antecedentes": "Sin cirugías previas, sedentario",
    "alergias": "Penicilina",
    "diagnostico_inicial": "Lumbalgia crónica inespecífica",
    "recomendaciones": "Fisioterapia 2x por semana, ejercicios en casa diarios"
}
```

#### Crear Sesión
```javascript
POST /api/v1/clinico/sesiones
Content-Type: application/json

{
    "paciente_id": "550e8400-e29b-41d4-a716-446655440000",
    "fisioterapeuta_id": "660e8400-e29b-41d4-a716-446655440111",
    "servicio_id": "770e8400-e29b-41d4-a716-446655440222",
    "fecha_atencion": "2026-01-10T10:00:00",
    "duracion_minutos": 60,
    "notas": "Sesión de relajación muscular enfocada en espalda",
    "ejercicios_realizados": "Estiramientos dorsales, movilidad articular",
    "materiales_usados": ["Balón", "Bandas elásticas"]
}
```

---

## 🔄 FLUJOS DE USO

### Flujo 1: Usuario Ve Su Historia

```
1. Usuario accede a /admin/clinico-usuario
2. El sistema carga su historia clínica desde el backend
3. Se muestran estadísticas (sesiones, minutos, progreso)
4. Usuario puede expandir secciones para ver detalles
5. Ve todas sus sesiones en orden cronológico
6. Visualiza servicios consumidos y resultados
```

**Datos Utilizados**: `persona_id` del usuario logueado

### Flujo 2: Admin Crea Nueva Historia

```
1. Admin accede a /admin/clinico-admin
2. Selecciona "Historias Clínicas" tab
3. Click en "Nueva Historia"
4. Modal se abre con formulario
5. Selecciona paciente del dropdown
6. Completa:
   - Motivo de consulta
   - Antecedentes
   - Alergias ⚠️
   - Diagnóstico inicial
   - Recomendaciones
7. Click "Crear Historia"
8. Sistema envía POST a /api/v1/clinico/historias
9. Se invalida caché de React Query
10. Lista se actualiza automáticamente
11. Modal se cierra
```

**Validaciones**:
- Paciente requerido
- Diagnóstico requerido
- Todos los campos de texto

### Flujo 3: Admin Registra Sesión

```
1. Admin en /admin/clinico-admin
2. Selecciona "Sesiones" tab
3. Click en "Nueva Sesión"
4. Modal se abre
5. Completa:
   - Paciente *
   - Fisioterapeuta
   - Duración (minutos) *
   - Fecha y hora *
   - Notas
   - Ejercicios realizados
   - Materiales usados
6. Click "Crear Sesión"
7. Sistema envía POST a /api/v1/clinico/sesiones
8. Se actualiza la lista
9. Usuario verá la nueva sesión en su vista
```

**Relaciones**:
- Una sesión puede vincularse a una cita (agenda_citas)
- Una sesión usa un servicio (clinico_servicios)
- Una sesión es atendida por un fisioterapeuta

### Flujo 4: Admin Edita Historia

```
1. Admin ve lista de historias
2. Click en "Editar" en una historia
3. Modal se abre con datos precargados
4. Modifica campos necesarios
5. Click "Actualizar Historia"
6. Sistema envía PUT a /api/v1/clinico/historias/:id
7. Lista se actualiza
8. Usuario ve cambios en su vista
```

---

## 🌙 DARK MODE

### Aplicado a Todos los Componentes

#### UserClinicView
- ✅ Stats cards con colores adaptados
- ✅ Historias clínicas con hover effects
- ✅ Cards de sesiones con tema oscuro
- ✅ Colores de badges (estado, categorías)

#### AdminClinicView
- ✅ Filtro de pacientes
- ✅ Tabs navigation
- ✅ Modales con dark mode
- ✅ Inputs y textareas adaptados
- ✅ Botones con variantes

### Variables de Color

```javascript
// Light Mode
bg-white, bg-gray-50
text-gray-900, text-gray-600
border-gray-200

// Dark Mode  
bg-gray-800, bg-gray-900
text-white, text-gray-300
border-gray-700

// Con theme provider
const { theme } = useTheme();
className={theme === 'dark' ? 'dark-classes' : 'light-classes'}
```

### Ejemplo

```jsx
<Card className={`
    ${theme === 'dark' 
        ? 'bg-gray-800 border-gray-700' 
        : 'bg-white border-gray-200'
    } 
    p-6
`}>
    <p className={theme === 'dark' ? 'text-white' : 'text-gray-900'}>
        Contenido
    </p>
</Card>
```

---

## 📱 RESPONSIVE DESIGN

### Desktop (>1024px)
- Vista grid 3 columnas en UserClinicView
- Historias + Servicios + Sesiones lado a lado
- Modales ajustados al ancho

### Tablet (768px - 1024px)
- Vista grid 2 columnas
- Stack vertical de componentes

### Mobile (<768px)
- Vista stack vertical
- Full width
- Tabs en horizontal scroll
- Botones full width en modales

---

## ✅ CHECKLIST DE IMPLEMENTACIÓN

- [x] Vista de Usuario (UserClinicView.jsx)
- [x] Vista de Administrador (AdminClinicView.jsx)
- [x] Hooks de React Query (useClinico.js)
- [x] Servicios API (clinicoService.js)
- [x] Rutas agregadas (AppRoutes.jsx)
- [x] Navbar actualizado (MainLayout.jsx)
- [x] Dark mode en todo
- [x] Modales con formularios
- [x] Filtros por paciente
- [x] Integración con backend
- [x] Permisos y autenticación

---

## 🚀 PRÓXIMOS PASOS

1. **Testing**: Crear tests para ambas vistas (40% coverage actual)
2. **Validaciones Avanzadas**: 
   - Validar alergias críticas
   - Verificar disponibilidad de terapeuta
3. **Reportes**: 
   - PDF de historia clínica
   - Historial de sesiones
4. **Notificaciones**: 
   - Email de nueva sesión
   - Recordatorios de citas
5. **Integración Facturación**:
   - Vincular sesiones a pagos
   - Mostrar monto en servicios

---

**Última revisión**: 10/01/2026  
**Estado**: ✅ COMPLETADO
