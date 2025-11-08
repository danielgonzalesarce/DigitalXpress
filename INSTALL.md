# 🚀 Guía de Instalación - DigitalXpress

Esta guía te ayudará a instalar y configurar DigitalXpress en tu máquina local.

## 📋 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- **PHP 8.1 o superior** - [Descargar PHP](https://www.php.net/downloads.php)
- **Composer** - [Descargar Composer](https://getcomposer.org/download/)
- **PostgreSQL 17 o 18** - [Descargar PostgreSQL](https://www.postgresql.org/download/)
- **Node.js y NPM** (opcional, para compilar assets) - [Descargar Node.js](https://nodejs.org/)

## 🎯 Instalación Automática

### Para Linux/Mac:

```bash
# Dar permisos de ejecución al script
chmod +x install.sh

# Ejecutar el script de instalación
./install.sh
```

### Para Windows:

```batch
# Ejecutar el script de instalación
install.bat
```

El script automáticamente:
- ✅ Verificará que tengas todos los requisitos instalados
- ✅ Instalará las dependencias de PHP (Composer)
- ✅ Instalará las dependencias de Node.js (NPM)
- ✅ Creará el archivo `.env` desde `.env.example`
- ✅ Generará la clave de aplicación
- ✅ Te preguntará si deseas ejecutar las migraciones
- ✅ Compilará los assets
- ✅ Limpiará el caché

## 📝 Instalación Manual

Si prefieres instalar manualmente, sigue estos pasos:

### 1. Clonar el Repositorio

```bash
git clone https://github.com/danielgonzalesarce/DigitalXpress.git
cd DigitalXpress
```

### 2. Instalar Dependencias de PHP

```bash
composer install
```

### 3. Instalar Dependencias de Node.js (Opcional)

```bash
npm install
```

### 4. Configurar el Entorno

```bash
# Copiar archivo de configuración
copy .env.example .env  # Windows
# o
cp .env.example .env    # Linux/Mac

# Generar clave de aplicación
php artisan key:generate
```

### 5. Configurar la Base de Datos

Edita el archivo `.env` y configura tu base de datos PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=digitalxpress
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 6. Crear la Base de Datos

```sql
-- Conéctate a PostgreSQL y ejecuta:
CREATE DATABASE digitalxpress;
```

### 7. Ejecutar Migraciones y Seeders

```bash
php artisan migrate:fresh --seed
```

### 8. Compilar Assets (Opcional)

```bash
npm run build
```

### 9. Limpiar Caché

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 10. Iniciar el Servidor

```bash
php artisan serve
```

El proyecto estará disponible en: `http://127.0.0.1:8000`

## 👤 Usuarios de Prueba

Después de ejecutar los seeders, puedes usar estos usuarios:

| Usuario        | Email                                                         | Contraseña | Rol           |
| -------------- | ------------------------------------------------------------- | ---------- | ------------- |
| Daniel Admin   | [admin@digitalxpress.com](mailto:admin@digitalxpress.com)     | password   | Administrador |
| María García   | [cliente@digitalxpress.com](mailto:cliente@digitalxpress.com) | password   | Cliente       |
| Carlos Técnico | [tecnico@digitalxpress.com](mailto:tecnico@digitalxpress.com) | password   | Técnico       |
| Ana VIP        | [vip@digitalxpress.com](mailto:vip@digitalxpress.com)         | password   | VIP           |

## 🔧 Solución de Problemas

### Error: "Composer no encontrado"
- Asegúrate de tener Composer instalado y en tu PATH
- Visita: https://getcomposer.org/download/

### Error: "PHP no encontrado"
- Verifica que PHP esté instalado: `php -v`
- Asegúrate de que PHP esté en tu PATH

### Error de conexión a la base de datos
- Verifica que PostgreSQL esté corriendo
- Revisa las credenciales en el archivo `.env`
- Asegúrate de que la base de datos existe

### Error al compilar assets
- Verifica que Node.js y NPM estén instalados
- Ejecuta: `npm install` nuevamente
- Si el error persiste, puedes omitir este paso (los assets se compilarán automáticamente)

## 📚 Recursos Adicionales

- [Documentación de Laravel](https://laravel.com/docs)
- [Documentación de PostgreSQL](https://www.postgresql.org/docs/)
- [Documentación de Composer](https://getcomposer.org/doc/)

## 🆘 Soporte

Si encuentras algún problema durante la instalación, por favor:

1. Revisa los logs de error
2. Verifica que todos los requisitos estén instalados
3. Abre un issue en el repositorio de GitHub

---

¡Disfruta de DigitalXpress! 🚀

