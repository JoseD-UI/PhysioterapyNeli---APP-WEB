---
description: Guía paso a paso para obtener las credenciales de Google OAuth (Client ID y Secret) para tu aplicación.
---

# Configuración de Google OAuth 2.0

Sigue estos pasos para obtener tu `GOOGLE_CLIENT_ID` y `GOOGLE_CLIENT_SECRET`.

## 1. Crear un Proyecto en Google Cloud

1. Ve a [Google Cloud Console](https://console.cloud.google.com/).
2. Inicia sesión con tu cuenta de Google.
3. Arriba a la izquierda, haz clic en el selector de proyectos y luego en **"New Project"** (Nuevo Proyecto).
4. Nombre del proyecto: `PhysioApp` (o lo que prefieras).
5. Haz clic en **"Create"**.

## 2. Configurar la Pantalla de Consentimiento (OAuth Consent Screen)

1. En el menú lateral izquierdo, ve a **APIs & Services** > **OAuth consent screen**.
2. Selecciona **External** (Externo) y haz clic en **Create**.
3. **App Information**:
    - App name: `PhysioApp`
    - User support email: Tu correo.
4. **Developer contact information**: Tu correo.
5. Haz clic en **Save and Continue** en las siguientes pantallas (puedes dejar Scopes y Test Users vacíos por ahora si es para desarrollo personal).
    - _Nota: Si añades usuarios de prueba, solo esos correos podrán loguearse mientras la app esté en modo "Testing"._

## 3. Crear Credenciales (Client ID & Secret)

1. En el menú lateral, ve a **APIs & Services** > **Credentials**.
2. Haz clic en **+ CREATE CREDENTIALS** (arriba) y selecciona **OAuth client ID**.
3. **Application type**: Selecciona **Web application**.
4. **Name**: `PhysioApp Frontend`.
5. **Authorized JavaScript origins** (Importante para React):
    - Haz clic en **ADD URI**.
    - Escribe: `http://localhost:8000`
    - (Si usas otro puerto como 5173, añádelo también: `http://localhost:5173`).
6. **Authorized redirect URIs** (Importante para el flujo):
    - Haz clic en **ADD URI**.
    - Escribe: `http://localhost:8000`
    - Escribe: `http://localhost:8000/auth/callback`
7. Haz clic en **CREATE**.

## 4. Copiar y Configurar

Aparecerá una ventana con tus credenciales.

1. Copia "Your Client ID".
2. Copia "Your Client Secret".
3. Abre tu archivo `.env` local en VS Code.
4. Pega los valores así:

```env
GOOGLE_CLIENT_ID=TU_CLIENT_ID_COPIADO.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=TU_CLIENT_SECRET_COPIADO
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/callback

# Copia el MISMO Client ID aquí:
VITE_GOOGLE_CLIENT_ID=TU_CLIENT_ID_COPIADO.apps.googleusercontent.com
```

## 5. Reiniciar

Guarda el archivo `.env` y reinicia tu terminal de frontend:

```bash
# Detener el servidor (Ctrl + C)
npm run dev
```

¡Listo! Ahora el botón de Google debería funcionar.
