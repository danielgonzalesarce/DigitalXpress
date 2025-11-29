# 🚀 Instalación Automática - DigitalXpress

Scripts de instalación automática que configuran todo el proyecto en un solo comando.

## 📋 Requisitos Previos

- **PHP 8.1 o superior**
- **Composer** (gestor de dependencias de PHP)
- **PostgreSQL 17/18** instalado y ejecutándose
- **Node.js y NPM** (opcional, para compilar assets)

## 🎯 Scripts Disponibles

### Windows

#### Opción 1: Script Batch (CMD)
```bash
install.bat
```

#### Opción 2: Script PowerShell (Recomendado)
```powershell
.\install.ps1
```

### Linux / Mac

```bash
chmod +x install.sh
./install.sh
```

## 🔧 ¿Qué hace el script?

El script automáticamente:

1. ✅ **Verifica dependencias** (PHP, Composer)
2. ✅ **Instala dependencias** de PHP (Composer)
3. ✅ **Crea archivo .env** desde .env.example
4. ✅ **Configura base de datos** (solicita credenciales)
5. ✅ **Genera clave de aplicación** Laravel
6. ✅ **Crea la base de datos** PostgreSQL (si psql está disponible)
7. ✅ **Ejecuta migraciones** (crea todas las tablas)
8. ✅ **Limpia caché** de Laravel
9. ✅ **Crea enlaces simbólicos** para storage

## 📝 Proceso de Instalación

### Paso 1: Ejecutar el script

**Windows:**
```cmd
install.bat
```

**Linux/Mac:**
```bash
chmod +x install.sh
./install.sh
```

### Paso 2: Configurar Base de Datos

El script te pedirá:
- **Host de PostgreSQL** (default: `localhost`)
- **Puerto** (default: `5432`)
- **Nombre de la base de datos** (default: `digitalxpress`)
- **Usuario** (default: `postgres`)
- **Contraseña** (requerida)

### Paso 3: Esperar a que termine

El script ejecutará todos los pasos automáticamente. Al finalizar, verás:

```
========================================
  INSTALACION COMPLETADA EXITOSAMENTE!
========================================
```

## 🚀 Iniciar el Proyecto

Después de la instalación:

```bash
php artisan serve --port=8081
```

Abre tu navegador en: **http://127.0.0.1:8081**

## 👤 Usuarios de Prueba

| Usuario | Email | Contraseña | Rol |
|---------|-------|------------|-----|
| Daniel Admin | admin@digitalxpress.com | password | Administrador |
| María García | cliente@digitalxpress.com | password | Cliente |

## ⚠️ Solución de Problemas

### Error: "PHP no está instalado"
- Instala PHP 8.1+ desde [php.net](https://www.php.net/downloads)
- Asegúrate de agregar PHP al PATH

### Error: "Composer no está instalado"
- Instala Composer desde [getcomposer.org](https://getcomposer.org)
- Verifica que esté en el PATH

### Error: "No se pudo crear la base de datos"
- Asegúrate de que PostgreSQL esté ejecutándose
- Crea la base de datos manualmente:
  ```sql
  CREATE DATABASE digitalxpress;
  ```

### Error: "Error al ejecutar migraciones"
- Verifica las credenciales en `.env`
- Asegúrate de que la base de datos exista
- Verifica que PostgreSQL esté ejecutándose

## 📚 Instalación Manual

Si prefieres instalar manualmente, consulta el [README.md](README.md) principal.

## 🔄 Reinstalar

Si necesitas reinstalar:

1. Elimina la base de datos:
   ```sql
   DROP DATABASE digitalxpress;
   ```

2. Elimina el archivo `.env`:
   ```bash
   rm .env  # Linux/Mac
   del .env # Windows
   ```

3. Ejecuta el script de instalación nuevamente

## 📞 Soporte

Si tienes problemas con la instalación, revisa:
- Los logs de Laravel: `storage/logs/laravel.log`
- La configuración en `.env`
- Los requisitos del sistema

---

**¡Listo! Tu proyecto DigitalXpress está configurado y funcionando al 100%** 🎉

