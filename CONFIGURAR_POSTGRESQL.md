# Configuración de PostgreSQL 17 para DigitalXpress

## Pasos para configurar PostgreSQL

### 1. Crear la base de datos en PostgreSQL

Abre tu cliente de PostgreSQL (pgAdmin, DBeaver, o línea de comandos) y ejecuta:

```sql
CREATE DATABASE digitalxpress;
```

O desde la línea de comandos:
```bash
psql -U postgres
CREATE DATABASE digitalxpress;
\q
```

### 2. Configurar el archivo .env

Edita el archivo `.env` en la raíz del proyecto y actualiza las siguientes líneas:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=digitalxpress
DB_USERNAME=tu_usuario_postgres
DB_PASSWORD=tu_contraseña_postgres
```

**Ejemplo:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=digitalxpress
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

### 3. Limpiar caché y ejecutar migraciones

Después de configurar el `.env`, ejecuta:

```bash
php artisan config:clear
php artisan cache:clear
php artisan migrate:fresh --seed
```

**Nota:** `migrate:fresh` eliminará todas las tablas y las recreará. Si ya tienes datos importantes, usa `php artisan migrate` en su lugar.

### 4. Verificar la conexión

Puedes verificar que la conexión funciona con:

```bash
php artisan db:show
```

## Solución de problemas

### Error: "could not connect to server"
- Verifica que PostgreSQL esté corriendo
- Verifica que el puerto 5432 esté abierto
- Verifica las credenciales en el `.env`

### Error: "database does not exist"
- Asegúrate de haber creado la base de datos `digitalxpress`
- Verifica el nombre en el `.env`

### Error: "password authentication failed"
- Verifica el usuario y contraseña en el `.env`
- Asegúrate de que el usuario tenga permisos para crear tablas

## Migración desde SQLite a PostgreSQL

Si ya tienes datos en SQLite y quieres migrarlos a PostgreSQL:

1. Exporta los datos de SQLite
2. Configura PostgreSQL como se indica arriba
3. Ejecuta las migraciones
4. Importa los datos manualmente o usa un script de migración

