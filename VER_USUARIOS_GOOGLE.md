# 👥 Cómo Ver Usuarios Registrados con Google

Esta guía te explica cómo ver los usuarios que se están registrando con Google en tu aplicación DigitalXpress.

---

## 🚀 MÉTODO 1: Panel de Administración (Recomendado)

### Paso 1: Acceder al Panel de Administración

1. Inicia sesión con una cuenta de administrador (email que termine en `@digitalxpress.com`)
2. Ve a: `http://127.0.0.1:8081/admin/dashboard`
3. En el menú lateral, haz clic en **"Usuarios"** o ve directamente a: `http://127.0.0.1:8081/admin/users`

### Paso 2: Ver Estadísticas

En la parte superior verás **4 tarjetas de estadísticas**:
- 📊 **Total Usuarios**: Todos los usuarios registrados
- 🛡️ **Administradores**: Usuarios con rol de administrador
- 👤 **Clientes**: Usuarios normales
- 🔴 **Con Google**: Usuarios registrados con Google OAuth

### Paso 3: Filtrar Usuarios de Google

1. En la sección de **Filtros**, busca el campo **"Tipo"**
2. Selecciona **"Solo con Google"** del menú desplegable
3. Haz clic en **"Filtrar"**
4. Verás solo los usuarios que se registraron con Google

### Paso 4: Información Visible

En la tabla de usuarios verás:

- **Usuario**: Nombre y avatar (si tiene foto de Google, se mostrará)
- **Email**: Correo electrónico del usuario
- **Tipo**: Rol del usuario (Administrador/Cliente)
- **Método de Registro**: 
  - 🔴 **Google** - Si se registró con Google
  - 📧 **Email** - Si se registró con email/contraseña tradicional
- **Fecha de Registro**: Cuándo se registró
- **Acciones**: Editar o eliminar usuario

---

## 🔍 MÉTODO 2: Base de Datos Directamente

Si prefieres ver los datos directamente en la base de datos:

### Usando PostgreSQL (psql)

```bash
# Conectarte a PostgreSQL
psql -U postgres -d digitalxpress

# Ver todos los usuarios registrados con Google
SELECT id, name, email, google_id, avatar, created_at 
FROM users 
WHERE google_id IS NOT NULL 
ORDER BY created_at DESC;

# Ver solo el conteo
SELECT COUNT(*) as usuarios_google FROM users WHERE google_id IS NOT NULL;
```

### Usando Laravel Tinker

```bash
# Abrir Tinker
php artisan tinker

# Ver usuarios de Google
User::whereNotNull('google_id')->get();

# Contar usuarios de Google
User::whereNotNull('google_id')->count();

# Ver información específica
User::whereNotNull('google_id')->select('name', 'email', 'google_id', 'avatar', 'created_at')->get();
```

---

## 📊 MÉTODO 3: Ver en Tiempo Real (Logs)

Cuando un usuario se registra con Google, puedes verlo en los logs:

```bash
# Ver logs en tiempo real
php artisan pail

# O ver el archivo de logs
tail -f storage/logs/laravel.log
```

---

## 🎯 Características del Panel Mejorado

### ✅ Lo que ahora puedes ver:

1. **Tarjeta de Estadísticas**: 
   - Muestra cuántos usuarios se registraron con Google

2. **Columna "Método de Registro"**:
   - Badge rojo con ícono de Google para usuarios de Google
   - Badge gris con ícono de email para usuarios tradicionales

3. **Avatar de Google**:
   - Si el usuario tiene foto de perfil de Google, se muestra automáticamente
   - Si no tiene foto, se muestra una inicial con fondo verde

4. **Filtro "Solo con Google"**:
   - Permite filtrar y ver solo usuarios registrados con Google

---

## 📝 Ejemplo de Uso

### Ver todos los usuarios de Google:

1. Ve a: `http://127.0.0.1:8081/admin/users`
2. En el filtro "Tipo", selecciona **"Solo con Google"**
3. Haz clic en **"Filtrar"**
4. Verás una lista de todos los usuarios que se registraron con Google

### Ver detalles de un usuario específico:

1. En la lista de usuarios, haz clic en **"Editar"** junto al usuario
2. Verás toda la información del usuario, incluyendo:
   - `google_id`: ID único de Google del usuario
   - `avatar`: URL de la foto de perfil de Google
   - `email`: Email del usuario (verificado por Google)
   - `name`: Nombre completo del usuario

---

## 🔔 Notas Importantes

- **Usuarios de Google**: Tienen un `google_id` en la base de datos
- **Usuarios tradicionales**: No tienen `google_id` (es NULL)
- **Avatar**: Solo los usuarios de Google pueden tener avatar (foto de perfil de Google)
- **Email verificado**: Los usuarios de Google tienen su email verificado automáticamente

---

## 🎉 ¡Listo!

Ahora puedes ver fácilmente todos los usuarios que se registran con Google desde el panel de administración.

**Ruta directa**: `http://127.0.0.1:8081/admin/users`

**Filtro rápido**: Selecciona "Solo con Google" en el filtro de tipo.

