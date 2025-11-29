# 🚀 GUÍA RÁPIDA: Configurar Google OAuth con tu Configuración Actual

Esta guía está diseñada específicamente para tu configuración actual:
- ✅ **Estado**: En producción
- ✅ **Tipo de usuario**: Usuarios externos
- ✅ **Límite**: 100 usuarios máximo

---

## 📋 PASOS A SEGUIR

### ✅ PASO 1: Verificar Configuración Actual (YA LO TIENES)

Tu configuración actual está correcta:
- **Estado de publicación**: "En producción" ✅
- **Tipo de usuario**: "Usuarios externos" ✅
- **Límite**: 0/100 usuarios ✅

**No necesitas cambiar nada aquí.** Puedes continuar.

---

### ✅ PASO 2: Configurar los Ámbitos (Scopes) - IMPORTANTE

Los ámbitos son los permisos que tu aplicación solicitará a Google.

1. **En el menú lateral izquierdo**, haz clic en **"Acceso a los datos"** (Data Access)
   - O ve directamente a: `https://console.cloud.google.com/apis/credentials/consent?project=digitalxpress-479713`

2. Busca la sección **"Ámbitos"** (Scopes)

3. Haz clic en **"AGREGAR O QUITAR ÁMBITOS"** o **"ADD OR REMOVE SCOPES"**

4. En la ventana que aparece, busca y selecciona estos 3 ámbitos:
   - ✅ `.../auth/userinfo.email` - Para obtener el email del usuario
   - ✅ `.../auth/userinfo.profile` - Para obtener nombre y foto del perfil
   - ✅ `openid` - Para autenticación OpenID Connect

5. Haz clic en **"ACTUALIZAR"** o **"UPDATE"**

6. Haz clic en **"GUARDAR"** o **"SAVE"** si aparece

**⚠️ IMPORTANTE**: Sin estos ámbitos, tu aplicación no podrá obtener la información del usuario.

---

### ✅ PASO 3: Crear el Cliente OAuth 2.0

1. **En el menú lateral izquierdo**, haz clic en **"Clientes"** (Clients)
   - O ve a: `https://console.cloud.google.com/apis/credentials`

2. Haz clic en el botón **"Crear cliente de OAuth"** (Create OAuth client)
   - O si ves **"+ CREAR CREDENCIALES"**, haz clic ahí y selecciona **"ID de cliente de OAuth 2.0"**

3. **Configura el cliente**:
   - **Tipo de aplicación**: Selecciona **"Aplicación web"** (Web application)
   - **Nombre**: Escribe `DigitalXpress Web Client`

4. **Agregar URLs de redirección**:
   - En **"URI de redirección autorizados"**, haz clic en **"+ AGREGAR URI"**
   - Agrega esta URL (una por una):
     ```
     http://127.0.0.1:8081/auth/google/callback
     ```
   - Haz clic en **"+ AGREGAR URI"** nuevamente
   - Agrega esta segunda URL:
     ```
     http://localhost:8081/auth/google/callback
     ```

5. **Crear el cliente**:
   - Haz clic en **"CREAR"** o **"CREATE"**

---

### ✅ PASO 4: Copiar las Credenciales

Después de crear el cliente, aparecerá un modal con tus credenciales:

1. **Client ID** (ID de cliente):
   - Formato: `123456789-abcdefghijklmnop.apps.googleusercontent.com`
   - **Copia este valor**

2. **Client Secret** (Secreto de cliente):
   - Formato: `GOCSPX-abcdefghijklmnopqrstuvwxyz`
   - **⚠️ MUY IMPORTANTE**: Este secreto **SOLO SE MUESTRA UNA VEZ**
   - **CÓPIALO AHORA** antes de cerrar el modal
   - Si está oculto, haz clic en el ícono de "ojo" 👁️ para verlo

3. **Guarda estas credenciales** en un bloc de notas temporal:
   ```
   Client ID: [pega aquí el Client ID]
   Client Secret: [pega aquí el Client Secret]
   ```

---

### ✅ PASO 5: Configurar el Archivo .env

1. Abre el archivo `.env` en la raíz de tu proyecto DigitalXpress

2. Agrega estas líneas al final del archivo (o busca si ya existen y actualízalas):

```env
# Google OAuth Credentials
GOOGLE_CLIENT_ID=tu_client_id_aqui
GOOGLE_CLIENT_SECRET=tu_client_secret_aqui
GOOGLE_REDIRECT_URI=http://127.0.0.1:8081/auth/google/callback
```

3. **Reemplaza los valores**:
   - `tu_client_id_aqui` → Pega el **Client ID** que copiaste
   - `tu_client_secret_aqui` → Pega el **Client Secret** que copiaste

**Ejemplo real** (con valores de ejemplo):
```env
# Google OAuth Credentials
GOOGLE_CLIENT_ID=123456789-abcdefghijklmnop.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abcdefghijklmnopqrstuvwxyz
GOOGLE_REDIRECT_URI=http://127.0.0.1:8081/auth/google/callback
```

4. **Guarda el archivo** `.env`

---

### ✅ PASO 6: Limpiar Caché y Probar

1. Abre tu terminal en la carpeta del proyecto

2. Ejecuta estos comandos para limpiar la caché:

```bash
php artisan config:clear
php artisan cache:clear
```

3. Si tu servidor no está corriendo, inícialo:

```bash
php artisan serve --port=8081
```

4. **Probar el login con Google**:
   - Ve a: `http://127.0.0.1:8081`
   - Haz clic en **"Iniciar Sesión"** o **"Registrarse"**
   - Haz clic en el botón **"Continuar con Google"**
   - Deberías ser redirigido a Google para autorizar
   - Selecciona tu cuenta de Google
   - Autoriza el acceso
   - Deberías ser redirigido de vuelta a tu aplicación y estar autenticado

---

## ⚠️ IMPORTANTE: Sobre el Límite de 100 Usuarios

Tu aplicación está configurada con un límite de **100 usuarios**. Esto significa:

- ✅ Los primeros 100 usuarios pueden registrarse sin problemas
- ⚠️ Después del usuario 100, Google puede mostrar una advertencia de "app no verificada"
- 📝 Para aumentar el límite o eliminarlo, necesitarás verificar tu aplicación con Google (proceso más complejo)

**Para desarrollo y pruebas**: 100 usuarios es más que suficiente.

---

## 🔧 SOLUCIÓN DE PROBLEMAS RÁPIDOS

### ❌ Error: "redirect_uri_mismatch"

**Solución**:
1. Ve a Google Cloud Console → Clientes
2. Haz clic en tu cliente OAuth
3. Verifica que la URL en `.env` (`GOOGLE_REDIRECT_URI`) esté **EXACTAMENTE** igual en "URI de redirección autorizados"
4. Asegúrate de que no haya espacios extra

### ❌ Error: "invalid_client"

**Solución**:
1. Verifica que copiaste correctamente el Client ID y Client Secret en `.env`
2. Asegúrate de que no haya espacios antes o después de los valores
3. Ejecuta: `php artisan config:clear`

### ❌ Error: "La autenticación con Google no está configurada"

**Solución**:
1. Verifica que el archivo `.env` tenga las tres variables:
   - `GOOGLE_CLIENT_ID`
   - `GOOGLE_CLIENT_SECRET`
   - `GOOGLE_REDIRECT_URI`
2. Ejecuta: `php artisan config:clear`
3. Reinicia el servidor

### ❌ El botón de Google no hace nada

**Solución**:
1. Verifica que las rutas estén en `routes/auth.php`
2. Verifica que el servidor esté corriendo en el puerto 8081
3. Abre la consola del navegador (F12) para ver si hay errores

---

## ✅ CHECKLIST FINAL

Antes de probar, verifica que tengas:

- [ ] Configuración de "Público" correcta (En producción, Usuarios externos)
- [ ] Ámbitos configurados (`userinfo.email`, `userinfo.profile`, `openid`)
- [ ] Cliente OAuth creado
- [ ] URLs de redirección agregadas (`http://127.0.0.1:8081/auth/google/callback`)
- [ ] Credenciales copiadas (Client ID y Client Secret)
- [ ] Archivo `.env` configurado con las credenciales
- [ ] Caché limpiada (`php artisan config:clear`)
- [ ] Servidor corriendo en puerto 8081

---

## 🎉 ¡LISTO!

Si seguiste todos los pasos, ahora deberías poder:
- ✅ Iniciar sesión con Google
- ✅ Registrarse con Google
- ✅ Los usuarios se crearán automáticamente en tu base de datos
- ✅ Los usuarios con @gmail.com podrán registrarse
- ✅ Los usuarios con @digitalxpress.com serán redirigidos al panel de administración

---

**¿Necesitas ayuda?** Revisa la sección de "Solución de Problemas" o verifica los logs de Laravel con `php artisan pail`.

