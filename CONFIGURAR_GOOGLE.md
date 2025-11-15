# ⚙️ Configuración Rápida de Google OAuth

## 🔴 Problema Actual
El error "Missing required parameter: client_id" indica que las credenciales de Google no están configuradas.

## ✅ Solución: 3 Pasos Simples

### Paso 1: Obtener Credenciales de Google

1. **Ve a Google Cloud Console**: https://console.cloud.google.com/
2. **Crea o selecciona un proyecto**
3. **Habilita Google+ API**:
   - Ve a "APIs & Services" > "Library"
   - Busca "Google+ API" o "Google Identity"
   - Haz clic en "Enable"
4. **Crea credenciales OAuth 2.0**:
   - Ve a "APIs & Services" > "Credentials"
   - Haz clic en "+ CREATE CREDENTIALS" > "OAuth client ID"
   - Tipo: **Web application**
   - **Authorized JavaScript origins**:
     ```
     http://127.0.0.1:8081
     http://localhost:8081
     ```
   - **Authorized redirect URIs**:
     ```
     http://127.0.0.1:8081/auth/google/callback
     http://localhost:8081/auth/google/callback
     ```
   - Haz clic en "Create"
   - **Copia el Client ID y Client Secret**

### Paso 2: Configurar el archivo .env

Abre el archivo `.env` en la raíz del proyecto y agrega estas líneas:

```env
GOOGLE_CLIENT_ID=tu_client_id_aqui.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-tu_client_secret_aqui
GOOGLE_REDIRECT_URI=http://127.0.0.1:8081/auth/google/callback
```

**Ejemplo real:**
```env
GOOGLE_CLIENT_ID=123456789-abcdefghijklmnop.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abcdefghijklmnopqrstuvwxyz123456
GOOGLE_REDIRECT_URI=http://127.0.0.1:8081/auth/google/callback
```

⚠️ **IMPORTANTE**: 
- Reemplaza `tu_client_id_aqui` con tu Client ID real
- Reemplaza `tu_client_secret_aqui` con tu Client Secret real
- No dejes espacios antes o después del `=`
- No uses comillas

### Paso 3: Limpiar Caché

Ejecuta estos comandos en la terminal:

```bash
php artisan config:clear
php artisan cache:clear
```

### Paso 4: Verificar Configuración

Ejecuta este comando para verificar que las credenciales se cargaron correctamente:

```bash
php artisan tinker
```

Luego ejecuta:
```php
config('services.google.client_id')
config('services.google.client_secret')
config('services.google.redirect')
```

Si ves tus credenciales, ¡está todo configurado! Si ves `null`, verifica el archivo `.env`.

## 🧪 Probar

1. Inicia el servidor:
   ```bash
   php artisan serve --port=8081
   ```

2. Ve a: http://127.0.0.1:8081/login

3. Haz clic en "Continuar con Google"

4. Deberías ser redirigido a Google para autenticarte

## ❌ Errores Comunes

### Error: "redirect_uri_mismatch"
- **Solución**: Verifica que la URL en `GOOGLE_REDIRECT_URI` coincida EXACTAMENTE con la configurada en Google Cloud Console (incluyendo http/https y el puerto)

### Error: "invalid_client"
- **Solución**: Verifica que el `GOOGLE_CLIENT_ID` y `GOOGLE_CLIENT_SECRET` sean correctos y no tengan espacios

### Error: "Access blocked"
- **Solución**: Si estás en modo "Testing" en Google Cloud Console, agrega tu email como usuario de prueba en "OAuth consent screen" > "Test users"

## 📚 Más Información

Para una guía más detallada, consulta: [GOOGLE_OAUTH_SETUP.md](GOOGLE_OAUTH_SETUP.md)

