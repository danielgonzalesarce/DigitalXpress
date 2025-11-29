# 🚀 Instalación Automática - DigitalXpress

<div align="center">

**Scripts de instalación automática que configuran todo el proyecto en un solo comando**

[Volver al README](README.md) • [Soporte](#-soporte)

</div>

---

## 📋 Tabla de Contenidos

- [Requisitos Previos](#-requisitos-previos)
- [Scripts Disponibles](#-scripts-disponibles)
- [Instalación Paso a Paso](#-instalación-paso-a-paso)
- [¿Qué hace el script?](#-qué-hace-el-script)
- [Iniciar el Proyecto](#-iniciar-el-proyecto)
- [Usuarios de Prueba](#-usuarios-de-prueba)
- [Solución de Problemas](#-solución-de-problemas)
- [Reinstalar](#-reinstalar)
- [Soporte](#-soporte)

---

## 📋 Requisitos Previos

Antes de ejecutar el script de instalación, asegúrate de tener instalado:

| Requisito | Versión Mínima | Descripción |
|-----------|----------------|-------------|
| **PHP** | 8.1+ | Lenguaje de programación |
| **Composer** | Última | Gestor de dependencias PHP |
| **PostgreSQL** | 17/18 | Base de datos |
| **Node.js** | 18+ | Opcional, para compilar assets |
| **NPM** | Última | Opcional, gestor de paquetes Node |

### Verificar Instalación

**Windows (PowerShell):**
```powershell
php --version
composer --version
psql --version
```

**Linux/Mac:**
```bash
php --version
composer --version
psql --version
```

---

## 🎯 Scripts Disponibles

### 🪟 Windows

#### Opción 1: PowerShell (Recomendado)

```powershell
git clone https://github.com/danielgonzalesarce/DigitalXpress.git
cd DigitalXpress
.\install.ps1
```

**Ventajas:**
- ✅ Mejor manejo de errores
- ✅ Colores y formato mejorado
- ✅ Más interactivo

#### Opción 2: CMD (Batch)

```cmd
git clone https://github.com/danielgonzalesarce/DigitalXpress.git
cd DigitalXpress
install.bat
```

### 🐧 Linux / 🍎 macOS

```bash
git clone https://github.com/danielgonzalesarce/DigitalXpress.git
cd DigitalXpress
chmod +x install.sh
./install.sh
```

---

## 🔧 ¿Qué hace el script?

El script de instalación ejecuta automáticamente los siguientes pasos:

### Paso 1: Verificación de Dependencias
- ✅ Verifica que PHP esté instalado y en el PATH
- ✅ Verifica que Composer esté instalado y en el PATH
- ✅ Muestra las versiones instaladas

### Paso 2: Instalación de Dependencias
- ✅ Ejecuta `composer install` para instalar todas las dependencias PHP
- ✅ Descarga e instala paquetes de Laravel y dependencias

### Paso 3: Configuración del Entorno
- ✅ Crea el archivo `.env` desde `.env.example` si no existe
- ✅ Solicita credenciales de PostgreSQL de forma interactiva:
  - Host (default: `localhost`)
  - Puerto (default: `5432`)
  - Nombre de base de datos (default: `digitalxpress`)
  - Usuario (default: `postgres`)
  - Contraseña (requerida)

### Paso 4: Generación de Clave
- ✅ Genera la clave de aplicación Laravel con `php artisan key:generate`

### Paso 5: Creación de Base de Datos
- ✅ Intenta crear la base de datos automáticamente usando `psql`
- ✅ Si `psql` no está disponible, muestra instrucciones para crearla manualmente

### Paso 6: Ejecución de Migraciones
- ✅ Ejecuta `php artisan migrate --force`
- ✅ Crea todas las tablas necesarias en la base de datos

### Paso 7: Limpieza de Caché
- ✅ Limpia el caché de configuración
- ✅ Limpia el caché de aplicación
- ✅ Limpia el caché de vistas
- ✅ Limpia el caché de rutas

### Paso 8: Enlaces Simbólicos
- ✅ Crea el enlace simbólico para storage con `php artisan storage:link`

---

## 📝 Instalación Paso a Paso

### Paso 1: Clonar el Repositorio

```bash
git clone https://github.com/danielgonzalesarce/DigitalXpress.git
cd DigitalXpress
```

### Paso 2: Ejecutar el Script

**Windows PowerShell:**
```powershell
.\install.ps1
```

**Windows CMD:**
```cmd
install.bat
```

**Linux/Mac:**
```bash
chmod +x install.sh
./install.sh
```

### Paso 3: Configurar Base de Datos

El script te solicitará la siguiente información:

```
Host de PostgreSQL (default: localhost): [Enter para usar default]
Puerto de PostgreSQL (default: 5432): [Enter para usar default]
Nombre de la base de datos (default: digitalxpress): [Enter para usar default]
Usuario de PostgreSQL (default: postgres): [Enter para usar default]
Contraseña de PostgreSQL: [Ingresa tu contraseña]
```

### Paso 4: Esperar a que Complete

El script ejecutará todos los pasos automáticamente. Verás mensajes de progreso como:

```
[1/8] Verificando dependencias...
[2/8] Instalando dependencias de PHP...
[3/8] Verificando archivo .env...
...
[8/8] Creando enlaces simbólicos...
```

### Paso 5: Confirmación

Al finalizar, verás:

```
========================================
  INSTALACION COMPLETADA EXITOSAMENTE!
========================================

El proyecto está listo para usar.
```

---

## 🚀 Iniciar el Proyecto

Después de la instalación exitosa:

### Opción 1: Usando Artisan

```bash
php artisan serve --port=8081
```

### Opción 2: Usando Scripts Incluidos

**Windows:**
```cmd
serve.bat
```

**Linux/Mac:**
```bash
./serve.sh
```

### Acceder a la Aplicación

Abre tu navegador en: **http://127.0.0.1:8081**

---

## 👤 Usuarios de Prueba

El proyecto incluye usuarios de prueba predefinidos:

| Usuario | Email | Contraseña | Rol |
|---------|-------|------------|-----|
| **Daniel Admin** | admin@digitalxpress.com | password | Administrador |
| **María García** | cliente@digitalxpress.com | password | Cliente |

> ⚠️ **Importante**: Estos usuarios son solo para desarrollo. Cambia las contraseñas en producción.

### Acceder al Panel de Administración

1. Inicia sesión con: `admin@digitalxpress.com` / `password`
2. Navega a: `http://127.0.0.1:8081/admin/dashboard`

---

## ⚠️ Solución de Problemas

### Error: "PHP no está instalado"

**Solución:**
1. Descarga PHP 8.1+ desde [php.net](https://www.php.net/downloads)
2. Instala PHP y agrégalo al PATH del sistema
3. Reinicia la terminal/consola
4. Verifica con: `php --version`

**Windows:**
- Agrega la ruta de PHP a las variables de entorno del sistema
- Ejemplo: `C:\php` → Variables de entorno → PATH

**Linux:**
```bash
sudo apt-get install php8.1-cli php8.1-common php8.1-mbstring
```

**macOS:**
```bash
brew install php@8.1
```

### Error: "Composer no está instalado"

**Solución:**
1. Descarga Composer desde [getcomposer.org](https://getcomposer.org/download)
2. Sigue las instrucciones de instalación para tu sistema operativo
3. Verifica con: `composer --version`

**Windows:**
- Descarga `Composer-Setup.exe` y ejecútalo
- Sigue el asistente de instalación

**Linux/Mac:**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Error: "No se pudo crear la base de datos"

**Solución:**

1. **Verifica que PostgreSQL esté ejecutándose:**
   ```bash
   # Windows (Services)
   services.msc → Buscar "PostgreSQL"

   # Linux
   sudo systemctl status postgresql

   # macOS
   brew services list | grep postgresql
   ```

2. **Crea la base de datos manualmente:**
   ```sql
   -- Conectar a PostgreSQL
   psql -U postgres

   -- Crear la base de datos
   CREATE DATABASE digitalxpress;

   -- Verificar
   \l
   ```

3. **Verifica las credenciales en `.env`:**
   ```env
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=digitalxpress
   DB_USERNAME=postgres
   DB_PASSWORD=tu_contraseña
   ```

### Error: "Error al ejecutar migraciones"

**Solución:**

1. **Verifica la conexión a la base de datos:**
   ```bash
   php artisan migrate:status
   ```

2. **Verifica las credenciales en `.env`**

3. **Verifica que la base de datos exista:**
   ```sql
   psql -U postgres -l
   ```

4. **Intenta ejecutar las migraciones manualmente:**
   ```bash
   php artisan migrate --force
   ```

5. **Si hay errores, revisa los logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

### Error: "Permission denied" (Linux/Mac)

**Solución:**

```bash
# Dar permisos de ejecución al script
chmod +x install.sh

# Dar permisos a storage y cache
chmod -R 775 storage bootstrap/cache
```

### Error: "Class not found" o errores de autoload

**Solución:**

```bash
# Regenerar autoload de Composer
composer dump-autoload

# Limpiar caché
php artisan optimize:clear
```

---

## 🔄 Reinstalar

Si necesitas reinstalar el proyecto desde cero:

### Paso 1: Eliminar Base de Datos

```sql
-- Conectar a PostgreSQL
psql -U postgres

-- Eliminar base de datos
DROP DATABASE digitalxpress;

-- Crear nueva base de datos
CREATE DATABASE digitalxpress;
```

### Paso 2: Eliminar Archivo .env

**Windows:**
```cmd
del .env
```

**Linux/Mac:**
```bash
rm .env
```

### Paso 3: Ejecutar Script de Instalación

Ejecuta nuevamente el script de instalación correspondiente a tu sistema operativo.

---

## 📞 Soporte

Si tienes problemas con la instalación:

### 1. Revisa los Logs

```bash
# Logs de Laravel
tail -f storage/logs/laravel.log

# Logs de Composer
composer install -vvv
```

### 2. Verifica la Configuración

Revisa el archivo `.env` y asegúrate de que todas las credenciales sean correctas.

### 3. Consulta la Documentación

- [README.md](README.md) - Documentación principal
- [Laravel Docs](https://laravel.com/docs) - Documentación oficial de Laravel
- [PostgreSQL Docs](https://www.postgresql.org/docs/) - Documentación de PostgreSQL

### 4. Abre un Issue

Si el problema persiste, abre un [Issue en GitHub](https://github.com/danielgonzalesarce/DigitalXpress/issues) con:
- Descripción detallada del problema
- Mensajes de error completos
- Pasos para reproducir
- Información del sistema (OS, PHP version, etc.)

---

## ✅ Checklist de Instalación

Usa este checklist para verificar que todo esté correcto:

- [ ] PHP 8.1+ instalado y funcionando
- [ ] Composer instalado y funcionando
- [ ] PostgreSQL instalado y ejecutándose
- [ ] Repositorio clonado
- [ ] Script de instalación ejecutado
- [ ] Base de datos creada
- [ ] Migraciones ejecutadas sin errores
- [ ] Archivo `.env` configurado correctamente
- [ ] Servidor iniciado sin errores
- [ ] Aplicación accesible en el navegador
- [ ] Puedes iniciar sesión con usuarios de prueba

---

<div align="center">

**¡Listo! Tu proyecto DigitalXpress está configurado y funcionando al 100%** 🎉

[Volver al README](README.md)

</div>
