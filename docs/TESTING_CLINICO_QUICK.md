# 🧪 GUÍA RÁPIDA DE PRUEBAS - MÓDULO CLÍNICO

## ✅ Verificación Rápida

### 1. **Verifica que las rutas están en AppRoutes.jsx**

```bash
# Buscar en el archivo
grep -n "clinico-usuario\|clinico-admin" resources/js/routes/AppRoutes.jsx
```

**Resultado esperado:**
```
import UserClinicView from '../features/Clinico/UserClinicView';
import AdminClinicView from '../features/Clinico/AdminClinicView';
<Route path="clinico-usuario" element={<UserClinicView />} />
<Route path="clinico-admin" element={<AdminClinicView />} />
```

### 2. **Verifica que el navbar está actualizado**

```bash
grep -n "Mi Historia Clínica\|Gestión Clínica" resources/js/layouts/MainLayout.jsx
```

**Resultado esperado:**
```
<Link to="/admin/clinico-usuario">Mi Historia Clínica</Link>
<Link to="/admin/clinico-admin">Gestión Clínica</Link>
```

### 3. **Verifica que los componentes existen**

```bash
ls -la resources/js/features/Clinico/
```

**Resultado esperado:**
```
UserClinicView.jsx        (480 líneas)
AdminClinicView.jsx       (650 líneas)
HistoriaClinicaView.jsx   (277 líneas)
SesionesView.jsx          (363 líneas)
index.jsx
```

### 4. **Inicia el servidor y prueba en navegador**

```bash
npm run dev
```

**Acceso:**
- http://localhost:3000/admin/clinico-usuario
- http://localhost:3000/admin/clinico-admin

---

## 🎬 CASOS DE PRUEBA

### Caso 1: Usuario Ve Su Historia Clínica

1. Accede a http://localhost:3000/admin/clinico-usuario
2. Deberías ver:
   - ✅ Panel de 4 estadísticas arriba
   - ✅ Sección "Historia Clínica" con expandir/colapsar
   - ✅ Sección "Servicios Consumidos" a la derecha
   - ✅ Sección "Mis Sesiones" abajo
   - ✅ Todo en dark mode (si lo tienes activado)

### Caso 2: Admin Crea Nueva Historia

1. Accede a http://localhost:3000/admin/clinico-admin
2. Selecciona "Historias Clínicas" tab
3. Click en "➕ Nueva Historia"
4. Deberías ver:
   - ✅ Modal con formulario
   - ✅ Dropdown de pacientes
   - ✅ Campos: Motivo, Antecedentes, Alergias, Diagnóstico, Recomendaciones
   - ✅ Botones: Crear / Cancelar

### Caso 3: Admin Crea Nueva Sesión

1. En http://localhost:3000/admin/clinico-admin
2. Selecciona "Sesiones" tab
3. Click en "➕ Nueva Sesión"
4. Deberías ver:
   - ✅ Modal con formulario
   - ✅ Campos: Paciente, Fisioterapeuta, Duración, Fecha, Notas, Ejercicios, Materiales
   - ✅ Validaciones de tipos

### Caso 4: Filtrar por Paciente

1. En vista admin
2. Cambia el filtro "Filtrar por Paciente"
3. Deberías ver:
   - ✅ Dropdown se llena con pacientes
   - ✅ Listas se actualizan al cambiar

### Caso 5: Dark Mode

1. En cualquier vista (usuario o admin)
2. Click en icono de luna 🌙 en navbar
3. Deberías ver:
   - ✅ Todos los elementos cambian a dark mode
   - ✅ Texto es legible
   - ✅ Contrastes adecuados

---

## 🐛 Problemas Comunes y Soluciones

### Problema: "Componente no encontrado"

**Causa**: Las importaciones no están en las rutas

**Solución**:
```javascript
// En AppRoutes.jsx, verifica que tienes:
import UserClinicView from '../features/Clinico/UserClinicView';
import AdminClinicView from '../features/Clinico/AdminClinicView';
```

### Problema: "Navbar no muestra nuevos enlaces"

**Causa**: MainLayout.jsx no fue actualizado

**Solución**:
```javascript
// En MainLayout.jsx debe haber:
<Link to="/admin/clinico-usuario">Mi Historia Clínica</Link>
<Link to="/admin/clinico-admin">Gestión Clínica</Link>
```

### Problema: "Estilos no se aplican"

**Causa**: Tailwind no está compilando

**Solución**:
```bash
# Reinicia el servidor
npm run dev

# O limpia caché
rm -rf node_modules/.vite
npm run dev
```

### Problema: "Dark mode no funciona"

**Causa**: ThemeProvider no está disponible

**Solución**:
```javascript
// Verifica que useTheme está importado:
import { useTheme } from '../../components/theme-provider';

// Y úsalo en el componente:
const { theme } = useTheme();
```

---

## 📊 URLs de Prueba

```
# Vista Usuario
http://localhost:3000/admin/clinico-usuario

# Vista Admin
http://localhost:3000/admin/clinico-admin

# Navbar debe tener:
- 📅 Agenda / Citas
- 📋 Mi Historia Clínica
- 📋 Gestión Clínica
```

---

## 🔍 Verificar en DevTools

### Network Tab

Cuando hagas clic en "Crear Historia":
```
POST /api/v1/clinico/historias
Status: 201 Created (esperado)
Body: { historia_id, persona_id, ... }
```

Cuando hagas clic en "Crear Sesión":
```
POST /api/v1/clinico/sesiones
Status: 201 Created (esperado)
Body: { sesion_id, paciente_id, ... }
```

### Console Tab

No debe haber errores rojos sobre:
```
❌ "Cannot find module"
❌ "useTheme is not defined"
❌ "Cannot read property 'data'"
```

---

## ✅ Checklist de Verificación

- [ ] Las rutas existen en AppRoutes.jsx
- [ ] Los componentes existen en /features/Clinico/
- [ ] El navbar tiene los 3 enlaces nuevos
- [ ] http://localhost:3000/admin/clinico-usuario carga
- [ ] http://localhost:3000/admin/clinico-admin carga
- [ ] Dark mode funciona en ambas vistas
- [ ] Modales se abren al click
- [ ] Dropdown de pacientes se llena
- [ ] No hay errores en la consola
- [ ] Responsive en móvil

---

## 🎓 Comandos Útiles

```bash
# Ver estructura de archivos
tree resources/js/features/Clinico/ -L 1

# Buscar importaciones de los componentes
grep -r "UserClinicView\|AdminClinicView" resources/js/

# Verificar que los hooks existen
grep -n "useGetHistorias\|useGetSesiones" resources/js/hooks/useClinico.js

# Ver las rutas
grep -n "clinico" resources/js/routes/AppRoutes.jsx
```

---

## 📞 Si Algo Falla

1. **Limpia caché**:
   ```bash
   rm -rf node_modules/.vite
   npm run dev
   ```

2. **Verifica las importaciones**:
   ```bash
   grep "import.*Clinico" resources/js/routes/AppRoutes.jsx
   ```

3. **Comprueba el dark mode**:
   ```bash
   grep "useTheme" resources/js/features/Clinico/*.jsx
   ```

4. **Reinicia todo**:
   ```bash
   npm run dev
   # Ctrl+C
   # npm run dev
   ```

---

**Última actualización**: 10/01/2026
