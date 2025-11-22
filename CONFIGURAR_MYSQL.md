# Configuración de MySQL para DigitalXpress

## Pasos para configurar MySQL

### 1. Crear la base de datos en MySQL

Abre tu cliente de MySQL (phpMyAdmin, MySQL Workbench, DBeaver, o línea de comandos) y ejecuta:

```sql
CREATE DATABASE digitalXpress_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

O desde la línea de comandos:
```bash
mysql -u root -p
CREATE DATABASE digitalXpress_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 2. Configurar el archivo .env

Edita el archivo `.env` en la raíz del proyecto y actualiza las siguientes líneas:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=digitalXpress_db
DB_USERNAME=tu_usuario_mysql
DB_PASSWORD=tu_contraseña_mysql
```

**Ejemplo común:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=digitalXpress_db
DB_USERNAME=root
DB_PASSWORD=
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

### Error: "SQLSTATE[HY000] [2002] No connection could be made"
- Verifica que MySQL esté corriendo
- Verifica que el puerto 3306 esté abierto
- Verifica las credenciales en el `.env`

### Error: "SQLSTATE[HY000] [1049] Unknown database"
- Asegúrate de haber creado la base de datos `digitalXpress_db`
- Verifica el nombre en el `.env` (case-sensitive en algunos sistemas)

### Error: "SQLSTATE[HY000] [1045] Access denied"
- Verifica el usuario y contraseña en el `.env`
- Asegúrate de que el usuario tenga permisos para crear tablas

### Error: "Access denied for user"
- Verifica que el usuario tenga permisos:
  ```sql
  GRANT ALL PRIVILEGES ON digitalXpress_db.* TO 'tu_usuario'@'localhost';
  FLUSH PRIVILEGES;
  ```

## Migración desde SQLite a MySQL

Si ya tienes datos en SQLite y quieres migrarlos a MySQL:

1. Exporta los datos de SQLite
2. Configura MySQL como se indica arriba
3. Ejecuta las migraciones
4. Importa los datos manualmente o usa un script de migración

