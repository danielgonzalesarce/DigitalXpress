# 🔄 Guía de Reorganización de Base de Datos

Esta guía te ayudará a reorganizar las migraciones de tu base de datos para que sean más limpias y consolidadas.

---

## 📋 ANTES DE EMPEZAR

### ⚠️ IMPORTANTE: Hacer Backup

**ANTES de hacer cualquier cambio, asegúrate de hacer un backup completo de tu base de datos:**

```bash
# Para PostgreSQL
pg_dump -U postgres digitalxpress_db > backup_antes_reorganizacion.sql

# O desde phpMyAdmin, exporta todas las tablas
```

---

## 🎯 OBJETIVO

Reorganizar las migraciones para que:
- ✅ Solo se vean las tablas necesarias
- ✅ Las migraciones estén consolidadas (sin múltiples migraciones para la misma tabla)
- ✅ Los datos existentes se preserven
- ✅ El orden de las migraciones sea lógico

---

## 📦 MIGRACIONES CONSOLIDADAS CREADAS

Se han creado las siguientes migraciones consolidadas:

1. ✅ `2025_01_01_000000_create_users_table_consolidated.php` - Users con role, google_id, avatar
2. ✅ `2025_01_01_000001_create_cache_table.php` - Cache (Laravel)
3. ✅ `2025_01_01_000002_create_jobs_table.php` - Jobs (Laravel)
4. ✅ `2025_01_01_000003_create_categories_table_consolidated.php` - Categories
5. ✅ `2025_01_01_000004_create_products_table_consolidated.php` - Products
6. ✅ `2025_01_01_000005_create_cart_items_table_consolidated.php` - Cart Items (user_id nullable)
7. ✅ `2025_01_01_000006_create_orders_table_consolidated.php` - Orders (con todos los campos de checkout)
8. ✅ `2025_01_01_000007_create_order_items_table_consolidated.php` - Order Items
9. ✅ `2025_01_01_000008_create_repairs_table_consolidated.php` - Repairs
10. ✅ `2025_01_01_000009_create_favorites_table_consolidated.php` - Favorites
11. ✅ `2025_01_01_000010_create_settings_table_consolidated.php` - Settings (con datos iniciales)
12. ✅ `2025_01_01_000011_create_conversations_table_consolidated.php` - Conversations
13. ✅ `2025_01_01_000012_create_messages_table_consolidated.php` - Messages

---

## 🚀 PASOS PARA REORGANIZAR

### Paso 1: Hacer Backup de las Migraciones Actuales

```bash
# Mover migraciones antiguas a carpeta backup
cd database/migrations
move *.php backup/
```

O manualmente:
1. Ve a `database/migrations/`
2. Mueve TODAS las migraciones antiguas a `database/migrations/backup/`
3. **EXCEPTO** las nuevas migraciones consolidadas que empiezan con `2025_01_01_`

### Paso 2: Renombrar las Migraciones Consolidadas

Las migraciones consolidadas tienen el sufijo `_consolidated`. Necesitas renombrarlas para que Laravel las reconozca:

```bash
# Renombrar migraciones consolidadas (quitar _consolidated)
cd database/migrations
ren 2025_01_01_000000_create_users_table_consolidated.php 2025_01_01_000000_create_users_table.php
ren 2025_01_01_000003_create_categories_table_consolidated.php 2025_01_01_000003_create_categories_table.php
ren 2025_01_01_000004_create_products_table_consolidated.php 2025_01_01_000004_create_products_table.php
ren 2025_01_01_000005_create_cart_items_table_consolidated.php 2025_01_01_000005_create_cart_items_table.php
ren 2025_01_01_000006_create_orders_table_consolidated.php 2025_01_01_000006_create_orders_table.php
ren 2025_01_01_000007_create_order_items_table_consolidated.php 2025_01_01_000007_create_order_items_table.php
ren 2025_01_01_000008_create_repairs_table_consolidated.php 2025_01_01_000008_create_repairs_table.php
ren 2025_01_01_000009_create_favorites_table_consolidated.php 2025_01_01_000009_create_favorites_table.php
ren 2025_01_01_000010_create_settings_table_consolidated.php 2025_01_01_000010_create_settings_table.php
ren 2025_01_01_000011_create_conversations_table_consolidated.php 2025_01_01_000011_create_conversations_table.php
ren 2025_01_01_000012_create_messages_table_consolidated.php 2025_01_01_000012_create_messages_table.php
```

### Paso 3: Limpiar la Tabla de Migraciones

```bash
# Limpiar la tabla migrations (esto NO elimina tus datos, solo el registro de migraciones)
php artisan migrate:reset
```

**⚠️ CUIDADO**: `migrate:reset` eliminará todas las tablas. Si quieres preservar datos, usa:

```bash
# Opción alternativa: Solo limpiar el registro de migraciones ejecutadas
php artisan tinker
>>> DB::table('migrations')->truncate();
>>> exit
```

### Paso 4: Ejecutar las Nuevas Migraciones

```bash
# Ejecutar todas las migraciones consolidadas
php artisan migrate
```

### Paso 5: Verificar que Todo Funcione

```bash
# Ver las tablas creadas
php artisan db:show

# O verificar en phpMyAdmin/PostgreSQL
```

---

## 🔄 ALTERNATIVA: Preservar Datos Existentes

Si quieres preservar los datos existentes sin eliminar las tablas:

### Opción A: Usar `migrate:fresh` (Elimina y recrea)

```bash
# ⚠️ ESTO ELIMINARÁ TODOS LOS DATOS
php artisan migrate:fresh
```

### Opción B: Migración Manual (Preserva Datos)

1. **NO ejecutes** `migrate:reset` o `migrate:fresh`
2. Las migraciones consolidadas tienen verificaciones `if (!Schema::hasColumn())` para evitar errores
3. Ejecuta solo las migraciones que faltan:

```bash
php artisan migrate
```

Laravel solo ejecutará las migraciones que no se hayan ejecutado antes.

---

## 📊 TABLAS QUE SE CREARÁN

Después de reorganizar, tendrás estas tablas:

### Tablas del Sistema (Laravel)
- `users` - Usuarios con role, google_id, avatar
- `password_reset_tokens` - Tokens de recuperación
- `sessions` - Sesiones de usuarios
- `cache` - Caché del sistema
- `cache_locks` - Locks de caché
- `jobs` - Cola de trabajos
- `job_batches` - Lotes de trabajos
- `failed_jobs` - Trabajos fallidos
- `migrations` - Registro de migraciones ejecutadas

### Tablas de la Aplicación
- `categories` - Categorías de productos
- `products` - Productos
- `cart_items` - Items del carrito
- `orders` - Pedidos
- `order_items` - Items de pedidos
- `repairs` - Solicitudes de reparación
- `favorites` - Productos favoritos
- `settings` - Configuraciones del sistema
- `conversations` - Conversaciones de mensajería
- `messages` - Mensajes

---

## ✅ VERIFICACIÓN FINAL

Después de reorganizar, verifica:

1. ✅ Todas las tablas se crearon correctamente
2. ✅ Los datos se preservaron (si usaste la opción B)
3. ✅ Las relaciones entre tablas funcionan
4. ✅ La aplicación funciona correctamente

---

## 🆘 SOLUCIÓN DE PROBLEMAS

### Error: "Table already exists"

Si ves este error, significa que la tabla ya existe. Las migraciones consolidadas tienen verificaciones, pero si persiste:

```bash
# Ver qué migraciones se han ejecutado
php artisan migrate:status

# Si una migración consolidada ya se ejecutó, puedes eliminarla de la tabla migrations
php artisan tinker
>>> DB::table('migrations')->where('migration', 'like', '%_consolidated%')->delete();
>>> exit
```

### Error: "Foreign key constraint fails"

Si hay problemas con claves foráneas:

```bash
# Deshabilitar verificaciones de claves foráneas temporalmente
php artisan migrate --force
```

### Perdí mis datos

Si perdiste datos y tienes backup:

```bash
# Restaurar desde backup
psql -U postgres digitalxpress_db < backup_antes_reorganizacion.sql
```

---

## 📝 NOTAS IMPORTANTES

1. **Backup primero**: Siempre haz backup antes de reorganizar
2. **Orden de migraciones**: Las migraciones consolidadas están numeradas en orden lógico
3. **Dependencias**: Las migraciones respetan las dependencias (users antes de orders, etc.)
4. **Datos iniciales**: La migración de `settings` incluye datos iniciales

---

## 🎉 ¡LISTO!

Después de seguir estos pasos, tendrás una base de datos limpia y organizada con migraciones consolidadas.

**¿Necesitas ayuda?** Revisa los logs de Laravel con `php artisan pail` o `storage/logs/laravel.log`.

