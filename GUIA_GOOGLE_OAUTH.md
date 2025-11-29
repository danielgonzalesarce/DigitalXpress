# 🔐 GUÍA COMPLETA: Configuración de Autenticación con Google OAuth

Esta guía te llevará paso a paso para configurar el inicio de sesión y registro con Google en tu aplicación DigitalXpress.

---

## 📋 ÍNDICE

1. [Paso 1: Crear un Proyecto en Google Cloud Console](#paso-1-crear-un-proyecto-en-google-cloud-console)
2. [Paso 2: Habilitar la API de Google+](#paso-2-habilitar-la-api-de-google)
3. [Paso 3: Configurar la Pantalla de Consentimiento OAuth](#paso-3-configurar-la-pantalla-de-consentimiento-oauth)
4. [Paso 4: Crear las Credenciales OAuth 2.0](#paso-4-crear-las-credenciales-oauth-20)
5. [Paso 5: Configurar las URLs de Redirección](#paso-5-configurar-las-urls-de-redirección)
6. [Paso 6: Obtener las Credenciales](#paso-6-obtener-las-credenciales)
7. [Paso 7: Configurar el Archivo .env](#paso-7-configurar-el-archivo-env)
8. [Paso 8: Probar la Autenticación](#paso-8-probar-la-autenticación)
9. [Solución de Problemas](#solución-de-problemas)

---

## 🚀 PASO 1: Crear un Proyecto en Google Cloud Console

### 1.1. Acceder a Google Cloud Console

1. Ve a: **https://console.cloud.google.com/**
2. Inicia sesión con tu cuenta de Google (preferiblemente la cuenta que usarás para el proyecto)

### 1.2. Crear un Nuevo Proyecto

1. En la parte superior de la página, haz clic en el **selector de proyectos** (junto al logo de Google Cloud)
2. Haz clic en **"NUEVO PROYECTO"** o **"New Project"**
3. Completa el formulario:
   - **Nombre del proyecto**: `DigitalXpress` (o el nombre que prefieras)
   - **Organización**: Déjalo como está (si no tienes organización)
   - **Ubicación**: Selecciona la que prefieras
4. Haz clic en **"CREAR"** o **"Create"**
5. Espera unos segundos mientras se crea el proyecto

### 1.3. Seleccionar el Proyecto

1. Una vez creado, selecciona el proyecto desde el selector de proyectos en la parte superior

---

## 🔧 PASO 2: Habilitar la API de Google

### 2.1. Acceder a la Biblioteca de APIs

1. En el menú lateral izquierdo, busca y haz clic en **"APIs y servicios"** → **"Biblioteca"**
   - O ve directamente a: **https://console.cloud.google.com/apis/library**

### 2.2. Habilitar Google+ API

1. En el buscador de la biblioteca, escribe: **"Google+ API"**
2. Haz clic en **"Google+ API"** en los resultados
3. Haz clic en el botón **"HABILITAR"** o **"ENABLE"**
4. Espera unos segundos mientras se habilita

**Nota**: Aunque Google+ está deprecado, esta API es necesaria para obtener información del perfil del usuario.

### 2.3. Habilitar Google Identity API (Opcional pero recomendado)

1. En la biblioteca de APIs, busca: **"Google Identity"**
2. Haz clic en **"Google Identity"**
3. Haz clic en **"HABILITAR"**

---

## ⚙️ PASO 3: Configurar la Pantalla de Consentimiento OAuth

### 3.1. Acceder a la Configuración OAuth

1. En el menú lateral izquierdo de Google Cloud Console, busca **"Google Auth Platform"**
2. Si es la primera vez, haz clic en **"Comenzar"** (Get Started)
3. Selecciona el tipo de usuario:
   - **"Externo"** (External) - Si quieres que cualquier usuario de Google pueda iniciar sesión ✅ **RECOMENDADO**
   - **"Interno"** (Internal) - Solo si quieres usuarios de tu organización
4. Haz clic en **"CREAR"** o **"Create"**

### 3.2. Configurar la Información de la Marca

Ahora ve a **"Información de la marca"** (Brand Information) en el menú lateral izquierdo.

#### 3.2.1. Información de la Aplicación

En la sección **"Información de la aplicación"**, completa:

1. **Nombre de la aplicación** (Application name) *:
   - Ingresa: `DigitalXpress`
   - Este es el nombre que verán los usuarios en la pantalla de consentimiento

2. **Correo electrónico de asistencia del usuario** (User support email) *:
   - Ingresa tu correo electrónico (ej: `tu@email.com`)
   - Este correo aparecerá para que los usuarios puedan contactarte si tienen preguntas

#### 3.2.2. Logotipo de la App (Opcional)

En la sección **"Logotipo de la app"**:

- **Puedes saltar este paso** si estás en desarrollo
- Si quieres agregar un logo:
  - Haz clic en **"Explorar"** (Browse)
  - Selecciona una imagen cuadrada (recomendado: 120x120 píxeles)
  - Formatos permitidos: JPG, PNG, BMP
  - Tamaño máximo: 1 MB
  - **Nota**: Si subes un logo, Google puede requerir verificación de la app (excepto si está en modo "Prueba")

#### 3.2.3. Dominio de la App

En la sección **"Dominio de la app"**, completa los siguientes campos:

**Para desarrollo local, puedes dejar estos campos vacíos o usar URLs temporales:**

1. **Página principal de la aplicación** (Application homepage):
   - Desarrollo: Puedes dejar vacío o usar `http://127.0.0.1:8081`
   - Producción: `https://tudominio.com`

2. **Vínculo a la Política de Privacidad** (Link to Privacy Policy):
   - Desarrollo: Puedes dejar vacío o usar `http://127.0.0.1:8081/privacy`
   - Producción: `https://tudominio.com/privacy`
   - **Nota**: En producción, este campo es obligatorio

3. **Vínculo a las Condiciones del Servicio** (Link to Terms of Service):
   - Desarrollo: Puedes dejar vacío o usar `http://127.0.0.1:8081/terms`
   - Producción: `https://tudominio.com/terms`
   - **Nota**: En producción, este campo es obligatorio

4. **Dominios autorizados** (Authorized domains):
   - Desarrollo local: Puedes dejarlo vacío
   - Producción: Agrega tu dominio (ej: `digitalxpress.com`)
   - **Cómo agregar**: Haz clic en **"+ AGREGAR DOMINIO"** y escribe tu dominio sin `http://` ni `www`

#### 3.2.4. Guardar los Cambios

1. Desplázate hacia abajo en la página
2. Los cambios se guardan automáticamente, pero puedes hacer clic en **"Actualizar"** (Update) en la parte superior derecha si quieres asegurarte

### 3.3. Configurar los Ámbitos (Scopes) - IMPORTANTE

Los ámbitos definen qué información puede solicitar tu aplicación a Google.

1. En el menú lateral izquierdo, haz clic en **"Acceso a los datos"** (Data Access)
   - O ve directamente a: **https://console.cloud.google.com/apis/credentials/consent?project=tu-proyecto**

2. En la sección **"Ámbitos"** (Scopes), haz clic en **"AGREGAR O QUITAR ÁMBITOS"** o **"ADD OR REMOVE SCOPES"**

3. En la ventana que aparece, busca y selecciona los siguientes ámbitos:
   - ✅ `.../auth/userinfo.email` - Para obtener el email del usuario
   - ✅ `.../auth/userinfo.profile` - Para obtener nombre y foto del perfil
   - ✅ `openid` - Para autenticación OpenID Connect

4. Haz clic en **"ACTUALIZAR"** o **"UPDATE"**

5. Haz clic en **"GUARDAR Y CONTINUAR"** o **"SAVE AND CONTINUE"**

### 3.4. Configurar el Público (Audience)

1. En el menú lateral, haz clic en **"Público"** (Audience)

2. Verifica que esté configurado como:
   - **Tipo de usuario**: "Usuarios externos" (External users)
   - **Estado de publicación**: Puede estar en "En producción" o "Prueba"
     - **Para desarrollo**: Si está en "En producción", puedes cambiarlo a "Prueba" haciendo clic en **"Volver al modo de prueba"**

3. **Límite de usuarios de OAuth**:
   - En modo "Prueba": Puedes agregar hasta 100 usuarios de prueba
   - En modo "Producción": No hay límite, pero Google puede requerir verificación

### 3.5. Agregar Usuarios de Prueba (Solo si estás en modo "Prueba")

Si tu aplicación está en modo "Prueba" y seleccionaste "Externo":

1. En la página **"Público"** (Audience), busca la sección **"Usuarios de prueba"** o ve a **"Acceso a los datos"** → **"Usuarios de prueba"**

2. Haz clic en **"AGREGAR USUARIOS"** o **"ADD USERS"**

3. Agrega los correos electrónicos de Google que quieras usar para pruebas:
   - Tu correo personal
   - Correos de otros usuarios de prueba
   - **Importante**: Solo estos usuarios podrán iniciar sesión mientras esté en modo "Prueba"

4. Haz clic en **"AGREGAR"** o **"ADD"**

### 3.6. Verificar la Configuración

Antes de continuar, verifica que todo esté correcto:

1. **Información de la marca**: Nombre, email, dominio (si aplica)
2. **Ámbitos**: `userinfo.email`, `userinfo.profile`, `openid`
3. **Público**: Configurado como "Externo"
4. **Usuarios de prueba**: Agregados (si estás en modo "Prueba")

Si todo está bien, continúa con el **Paso 4** para crear las credenciales OAuth 2.0.

---

## 🔑 PASO 4: Crear las Credenciales OAuth 2.0

### 4.1. Acceder a la Sección de Clientes

1. En el menú lateral izquierdo de Google Cloud Console, dentro de **"Google Auth Platform"**, haz clic en **"Clientes"** (Clients)
   - O ve directamente a: **https://console.cloud.google.com/apis/credentials**

2. También puedes acceder desde:
   - **"Descripción general"** (Overview) → Botón **"Crear cliente de OAuth"** (Create OAuth client)
   - O desde el menú: **"APIs y servicios"** → **"Credenciales"** → **"+ CREAR CREDENCIALES"** → **"ID de cliente de OAuth 2.0"**

### 4.2. Crear el Cliente OAuth 2.0

1. Haz clic en el botón **"Crear cliente de OAuth"** (Create OAuth client) o **"+ CREAR CREDENCIALES"** → **"ID de cliente de OAuth 2.0"**

2. Si es la primera vez, Google puede pedirte confirmar la configuración de la pantalla de consentimiento (ya la configuraste en el Paso 3)

### 4.3. Configurar el Tipo de Aplicación

En el formulario que aparece:

1. **Tipo de aplicación** (Application type):
   - Selecciona: **"Aplicación web"** (Web application)
   - **NO selecciones**: "Aplicación de escritorio", "Dispositivos móviles", etc.

2. **Nombre** (Name):
   - Ingresa: `DigitalXpress Web Client`
   - Este nombre es solo para identificarlo en la consola, no afecta a los usuarios

---

## 🌐 PASO 5: Configurar las URLs de Redirección

### 5.1. Agregar URLs de Redirección en el Formulario

En el mismo formulario donde configuraste el tipo de aplicación, busca la sección:

**"URI de redirección autorizados"** (Authorized redirect URIs)

### 5.2. URLs para Desarrollo Local

Agrega las siguientes URLs **UNA POR UNA**:

1. Haz clic en **"+ AGREGAR URI"** o **"+ ADD URI"**
2. Pega la primera URL:
   ```
   http://127.0.0.1:8081/auth/google/callback
   ```
3. Haz clic en **"+ AGREGAR URI"** nuevamente
4. Pega la segunda URL:
   ```
   http://localhost:8081/auth/google/callback
   ```

**⚠️ IMPORTANTE**: 
- Las URLs deben coincidir **EXACTAMENTE** con las que uses en tu aplicación
- No agregues espacios antes o después
- Usa `http://` para desarrollo local (no `https://`)
- El puerto `8081` debe coincidir con el que uses en Laravel

### 5.3. URLs para Producción (Opcional - Cuando despliegues)

Si ya tienes tu dominio en producción, también agrega:

```
https://tudominio.com/auth/google/callback
https://www.tudominio.com/auth/google/callback
```

**Nota**: 
- En producción **SIEMPRE** usa `https://` (no `http://`)
- Agrega tanto la versión con `www` como sin `www` si tu dominio soporta ambas

### 5.4. Crear el Cliente OAuth

1. Una vez agregadas todas las URLs de redirección
2. Revisa que el tipo de aplicación sea **"Aplicación web"**
3. Revisa que el nombre sea `DigitalXpress Web Client`
4. Haz clic en **"CREAR"** o **"CREATE"**

---

## 📝 PASO 6: Obtener las Credenciales

### 6.1. Ver las Credenciales Después de Crear

Después de hacer clic en **"CREAR"**, aparecerá un modal o una página con tus credenciales:

**En el modal/página verás:**

1. **ID de cliente** (Client ID):
   - Formato: `123456789-abcdefghijklmnop.apps.googleusercontent.com`
   - Este es un identificador único de tu aplicación
   - **Puedes verlo después** si lo necesitas (está en la lista de clientes)

2. **Secreto de cliente** (Client Secret):
   - Formato: `GOCSPX-abcdefghijklmnopqrstuvwxyz`
   - **⚠️ MUY IMPORTANTE**: Este secreto **SOLO SE MUESTRA UNA VEZ**
   - **CÓPIALO AHORA** antes de cerrar el modal

### 6.2. Cómo Copiar las Credenciales

1. **Client ID**: 
   - Haz clic en el campo o selecciona todo el texto
   - Copia (Ctrl+C o Clic derecho → Copiar)

2. **Client Secret**:
   - Haz clic en el ícono de "ojo" 👁️ si está oculto para verlo
   - Selecciona todo el texto
   - Copia (Ctrl+C o Clic derecho → Copiar)
   - **Guárdalo en un lugar seguro** (bloc de notas, documento, etc.)

### 6.3. Si Perdiste el Client Secret

Si cerraste el modal sin copiar el Client Secret:

1. Ve a **"Clientes"** (Clients) en el menú lateral
2. Haz clic en el nombre de tu cliente OAuth (`DigitalXpress Web Client`)
3. Verás el **Client ID**, pero el **Client Secret** estará oculto
4. Haz clic en **"Restablecer secreto"** o **"Reset secret"** para generar uno nuevo
5. **Copia el nuevo secreto inmediatamente**

### 6.4. Guardar las Credenciales de Forma Segura

Guarda estas credenciales en un lugar temporal seguro (bloc de notas, documento de texto, etc.) porque las necesitarás en el siguiente paso para agregarlas al archivo `.env`.

**Ejemplo de cómo guardarlas:**

```
Client ID: 123456789-abcdefghijklmnop.apps.googleusercontent.com
Client Secret: GOCSPX-abcdefghijklmnopqrstuvwxyz
```

---

## ⚙️ PASO 7: Configurar el Archivo .env

### 7.1. Abrir el Archivo .env

1. Abre el archivo `.env` en la raíz de tu proyecto DigitalXpress
2. Si no existe, copia `.env.example` y renómbralo a `.env`

### 7.2. Agregar las Credenciales de Google

Busca o agrega las siguientes líneas al final del archivo `.env`:

```env
# Google OAuth Credentials
GOOGLE_CLIENT_ID=tu_client_id_aqui
GOOGLE_CLIENT_SECRET=tu_client_secret_aqui
GOOGLE_REDIRECT_URI=http://127.0.0.1:8081/auth/google/callback
```

### 7.3. Reemplazar los Valores

Reemplaza:
- `tu_client_id_aqui` con el **ID de cliente** que copiaste en el Paso 6.1
- `tu_client_secret_aqui` con el **Secreto de cliente** que copiaste en el Paso 6.1

**Ejemplo:**

```env
# Google OAuth Credentials
GOOGLE_CLIENT_ID=123456789-abcdefghijklmnop.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abcdefghijklmnopqrstuvwxyz
GOOGLE_REDIRECT_URI=http://127.0.0.1:8081/auth/google/callback
```

### 7.4. Guardar el Archivo

Guarda el archivo `.env` después de hacer los cambios.

---

## ✅ PASO 8: Probar la Autenticación

### 8.1. Limpiar la Caché de Configuración

Abre tu terminal en la carpeta del proyecto y ejecuta:

```bash
php artisan config:clear
php artisan cache:clear
```

### 8.2. Iniciar el Servidor

Si no está corriendo, inicia el servidor:

```bash
php artisan serve --port=8081
```

### 8.3. Probar el Login con Google

1. Ve a tu aplicación: **http://127.0.0.1:8081**
2. Haz clic en el botón de **"Iniciar Sesión"** o **"Registrarse"**
3. Haz clic en el botón **"Continuar con Google"**
4. Deberías ser redirigido a la página de Google para autorizar
5. Selecciona tu cuenta de Google
6. Autoriza el acceso
7. Deberías ser redirigido de vuelta a tu aplicación y estar autenticado

---

## 🔧 SOLUCIÓN DE PROBLEMAS

### ❌ Error: "redirect_uri_mismatch"

**Problema**: La URL de redirección no coincide con las configuradas en Google Cloud Console.

**Solución**:
1. Ve a Google Cloud Console → Credenciales
2. Haz clic en tu ID de cliente OAuth 2.0
3. Verifica que la URL en `.env` (`GOOGLE_REDIRECT_URI`) esté exactamente igual en "URI de redirección autorizados"
4. Asegúrate de que no haya espacios extra o diferencias (http vs https, localhost vs 127.0.0.1)

### ❌ Error: "invalid_client"

**Problema**: Las credenciales (Client ID o Client Secret) son incorrectas.

**Solución**:
1. Verifica que copiaste correctamente el Client ID y Client Secret en el archivo `.env`
2. Asegúrate de que no haya espacios antes o después de los valores
3. Ejecuta `php artisan config:clear` después de modificar `.env`

### ❌ Error: "access_denied"

**Problema**: El usuario canceló la autorización o la aplicación está en modo de prueba.

**Solución**:
1. Si tu aplicación está en modo "Externo" y en "Prueba", asegúrate de agregar tu correo como usuario de prueba
2. O publica la aplicación (pero esto requiere verificación de Google para producción)

### ❌ Error: "La autenticación con Google no está configurada"

**Problema**: Las variables de entorno no están configuradas correctamente.

**Solución**:
1. Verifica que el archivo `.env` tenga las tres variables:
   - `GOOGLE_CLIENT_ID`
   - `GOOGLE_CLIENT_SECRET`
   - `GOOGLE_REDIRECT_URI`
2. Ejecuta `php artisan config:clear`
3. Reinicia el servidor

### ❌ El botón de Google no funciona

**Problema**: La ruta no está configurada o hay un error en el código.

**Solución**:
1. Verifica que las rutas estén en `routes/auth.php`:
   ```php
   Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('google.auth');
   Route::get('auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
   ```
2. Verifica que el paquete `laravel/socialite` esté instalado: `composer require laravel/socialite`
3. Verifica que el archivo `config/services.php` tenga la configuración de Google

---

## 📚 ENLACES ÚTILES

- **Google Cloud Console**: https://console.cloud.google.com/
- **Biblioteca de APIs**: https://console.cloud.google.com/apis/library
- **Credenciales OAuth**: https://console.cloud.google.com/apis/credentials
- **Pantalla de Consentimiento**: https://console.cloud.google.com/apis/credentials/consent
- **Documentación de Laravel Socialite**: https://laravel.com/docs/socialite

---

## 🎉 ¡LISTO!

Si seguiste todos los pasos correctamente, ahora deberías poder:
- ✅ Iniciar sesión con Google
- ✅ Registrarte con Google
- ✅ Los usuarios se crearán automáticamente en tu base de datos
- ✅ Los usuarios con @gmail.com podrán registrarse
- ✅ Los usuarios con @digitalxpress.com serán redirigidos al panel de administración

---

## 📝 NOTAS IMPORTANTES

1. **Seguridad**: Nunca subas tu archivo `.env` a Git. Está en `.gitignore` por defecto.
2. **Producción**: Cuando despliegues a producción, asegúrate de:
   - Cambiar `GOOGLE_REDIRECT_URI` a tu dominio de producción
   - Agregar las URLs de producción en Google Cloud Console
   - Usar `https://` en producción
3. **Límites**: Google tiene límites en el número de usuarios de prueba para aplicaciones en modo "Externo" y "Prueba". Para producción, necesitarás verificar tu aplicación con Google.

---

**¿Necesitas ayuda?** Revisa la sección de "Solución de Problemas" o verifica los logs de Laravel con `php artisan pail` o revisando `storage/logs/laravel.log`.

