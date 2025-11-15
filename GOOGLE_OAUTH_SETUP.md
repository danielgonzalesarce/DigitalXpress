# 🔐 Configuración de Autenticación con Google OAuth

Esta guía te ayudará a configurar la autenticación con Google en DigitalXpress.

## 📋 Requisitos Previos

1. Una cuenta de Google
2. Acceso a [Google Cloud Console](https://console.cloud.google.com/)

## 🚀 Pasos para Configurar Google OAuth

### 1. Crear un Proyecto en Google Cloud Console

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Haz clic en el selector de proyectos en la parte superior
3. Haz clic en "Nuevo Proyecto"
4. Ingresa un nombre para tu proyecto (ej: "DigitalXpress")
5. Haz clic en "Crear"

### 2. Habilitar Google+ API

1. En el menú lateral, ve a **APIs & Services** > **Library**
2. Busca "Google+ API" o "Google Identity"
3. Haz clic en "Enable" (Habilitar)

### 3. Configurar Pantalla de Consentimiento OAuth

1. Ve a **APIs & Services** > **OAuth consent screen**
2. Selecciona **External** (para desarrollo) o **Internal** (solo para tu organización)
3. Completa la información requerida:
   - **App name**: DigitalXpress
   - **User support email**: Tu email
   - **Developer contact information**: Tu email
4. Haz clic en "Save and Continue"
5. En **Scopes**, haz clic en "Save and Continue" (puedes agregar scopes después)
6. En **Test users**, agrega los emails de prueba si es necesario
7. Haz clic en "Save and Continue"

### 4. Crear Credenciales OAuth 2.0

1. Ve a **APIs & Services** > **Credentials**
2. Haz clic en **+ CREATE CREDENTIALS** > **OAuth client ID**
3. Selecciona **Web application** como tipo de aplicación
4. Configura:
   - **Name**: DigitalXpress Web Client
   - **Authorized JavaScript origins**: 
     - `http://127.0.0.1:8081` (para desarrollo local)
     - `http://localhost:8081` (alternativa)
   - **Authorized redirect URIs**:
     - `http://127.0.0.1:8081/auth/google/callback`
     - `http://localhost:8081/auth/google/callback`
5. Haz clic en "Create"
6. **IMPORTANTE**: Copia el **Client ID** y **Client Secret** que se muestran

### 5. Configurar Variables de Entorno

Edita tu archivo `.env` y agrega las siguientes líneas:

```env
GOOGLE_CLIENT_ID=tu_client_id_aqui
GOOGLE_CLIENT_SECRET=tu_client_secret_aqui
GOOGLE_REDIRECT_URI=http://127.0.0.1:8081/auth/google/callback
```

**Ejemplo:**
```env
GOOGLE_CLIENT_ID=123456789-abcdefghijklmnop.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abcdefghijklmnopqrstuvwxyz
GOOGLE_REDIRECT_URI=http://127.0.0.1:8081/auth/google/callback
```

### 6. Ejecutar la Migración

Ejecuta la migración para agregar los campos necesarios a la tabla `users`:

```bash
php artisan migrate
```

### 7. Limpiar Caché

```bash
php artisan config:clear
php artisan cache:clear
```

## ✅ Verificación

1. Inicia el servidor:
   ```bash
   php artisan serve --port=8081
   ```

2. Ve a `http://127.0.0.1:8081/login`

3. Haz clic en "Continuar con Google"

4. Deberías ser redirigido a Google para autenticarte

5. Después de autenticarte, serás redirigido de vuelta a la aplicación

## 🔧 Solución de Problemas

### Error: "redirect_uri_mismatch"
- Verifica que la URL en `GOOGLE_REDIRECT_URI` coincida exactamente con la configurada en Google Cloud Console
- Asegúrate de que no haya espacios o caracteres extra

### Error: "invalid_client"
- Verifica que el `GOOGLE_CLIENT_ID` y `GOOGLE_CLIENT_SECRET` sean correctos
- Asegúrate de haber copiado las credenciales completas

### Error: "access_denied"
- Verifica que la pantalla de consentimiento OAuth esté configurada correctamente
- Si estás en modo "Testing", asegúrate de agregar tu email como usuario de prueba

### El botón de Google no aparece
- Verifica que las rutas estén correctamente configuradas
- Limpia el caché: `php artisan config:clear`

## 📝 Notas Importantes

- **Desarrollo Local**: Usa `http://127.0.0.1:8081` en las URLs autorizadas
- **Producción**: Cambia las URLs a tu dominio de producción
- **Seguridad**: Nunca compartas tu `GOOGLE_CLIENT_SECRET` públicamente
- **Testing**: Durante el desarrollo, puedes usar el modo "Testing" en OAuth consent screen

## 🎉 ¡Listo!

Una vez configurado, los usuarios podrán:
- ✅ Iniciar sesión con Google
- ✅ Crear cuenta con Google
- ✅ Vincular su cuenta existente con Google
- ✅ Usar su avatar de Google en el perfil

---

**¿Necesitas ayuda?** Revisa la [documentación oficial de Google OAuth](https://developers.google.com/identity/protocols/oauth2)

