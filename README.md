# 🛒 DigitalXpress - E-commerce Store with Repair Services

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.7.1-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-17/18-316192?style=for-the-badge&logo=postgresql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

**Una aplicación web completa desarrollada en Laravel que combina una tienda de e-commerce con servicios técnicos especializados.**

[Características](#-características-principales) • [Instalación](#-instalación-paso-a-paso) • [Configuración](#-configuración-detallada) • [Soporte](#-soporte-técnico)

</div>

---

## 📋 Tabla de Contenidos

- [Características Principales](#-características-principales)
- [Tecnologías Utilizadas](#-tecnologías-utilizadas)
- [Requisitos del Sistema](#-requisitos-del-sistema)
- [Instalación Paso a Paso](#-instalación-paso-a-paso)
  - [Verificación de Requisitos](#1-verificación-de-requisitos)
  - [Clonar el Repositorio](#2-clonar-el-repositorio)
  - [Instalar Dependencias](#3-instalar-dependencias)
  - [Configurar Entorno](#4-configurar-entorno)
  - [Configurar Base de Datos](#5-configurar-base-de-datos)
  - [Ejecutar Migraciones y Seeders](#6-ejecutar-migraciones-y-seeders)
  - [Configurar Storage](#7-configurar-storage)
  - [Compilar Assets](#8-compilar-assets-opcional)
  - [Iniciar el Servidor](#9-iniciar-el-servidor)
  - [Verificar Instalación](#10-verificar-instalación)
- [Configuración Detallada](#-configuración-detallada)
- [Usuarios de Prueba](#-usuarios-de-prueba)
- [Uso del Sistema](#-uso-del-sistema)
- [Solución de Problemas](#-solución-de-problemas-comunes)
- [Comandos Útiles](#-comandos-útiles)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Documentación Adicional](#-documentación-adicional)
- [Soporte Técnico](#-soporte-técnico)

---

## 🚀 Características Principales

### 🛍️ E-commerce Completo
- ✅ **Catálogo de productos** con 8 categorías específicas (Laptops, Relojes, Televisores, Mouses, Teclados, Audífonos, Celulares, Cámaras)
- ✅ **Carrito inteligente** que funciona para usuarios registrados e invitados
- ✅ **Sistema de categorías** organizado y filtrable
- ✅ **Gestión de stock** en tiempo real
- ✅ **Precios con descuentos** y ofertas especiales
- ✅ **Búsqueda avanzada** de productos con filtros automáticos
- ✅ **Sistema de favoritos** para usuarios registrados
- ✅ **Checkout completo** con múltiples métodos de pago

### 🔧 Servicio Técnico
- ✅ **Dashboard de reparaciones** completo
- ✅ **Formulario de solicitud** con subida de imágenes
- ✅ **Seguimiento de estado** en tiempo real
- ✅ **Generación de reportes PDF** automáticos
- ✅ **Sistema de citas** y contacto directo

### 🔐 Autenticación y Seguridad
- ✅ **Autenticación avanzada** con modal personalizado
- ✅ **Login con Google OAuth** (crear cuenta e iniciar sesión)
- ✅ **Validaciones robustas** en formularios
- ✅ **Sistema de roles** (Admin, Cliente, Técnico, VIP)
- ✅ **Gestión de sesiones** segura
- ✅ **Sistema de auditoría** completo

### 👨‍💼 Panel de Administración
- ✅ **Dashboard completo** con estadísticas en tiempo real
- ✅ **Gestión de productos** (CRUD completo) con filtros automáticos
- ✅ **Gestión de inventario** y stock
- ✅ **Gestión de pedidos** y órdenes
- ✅ **Sistema de auditoría** para rastrear actividades
- ✅ **Gestión de usuarios** y permisos
- ✅ **Sistema de mensajería** entre usuarios y administradores

---

## 🛠️ Tecnologías Utilizadas

| Categoría | Tecnología | Versión |
|-----------|-----------|---------|
| **Backend** | Laravel | 12.7.1 |
| **Lenguaje** | PHP | 8.1+ |
| **Base de Datos** | PostgreSQL | 17/18 |
| **Frontend** | Bootstrap | 5.x |
| **Iconos** | Font Awesome | 6.0 |
| **Autenticación** | Laravel Breeze, Laravel Socialite | - |
| **PDF** | DomPDF | - |
| **Gestión de Paquetes** | Composer, NPM | - |

---

## 📋 Requisitos del Sistema

### Requisitos Mínimos Obligatorios

#### Software Base
- **PHP**: 8.1 o superior (recomendado 8.3+)
- **Composer**: Última versión estable
- **PostgreSQL**: Versión 17 o 18
- **Git**: Para clonar el repositorio

#### Extensiones PHP Requeridas
Asegúrate de tener habilitadas las siguientes extensiones en tu `php.ini`:

```ini
extension=bcmath
extension=ctype
extension=fileinfo
extension=json
extension=mbstring
extension=openssl
extension=pdo
extension=pdo_pgsql
extension=tokenizer
extension=xml
extension=gd
extension=zip
```

#### Verificar Extensiones PHP
```bash
php -m | grep -E "bcmath|ctype|fileinfo|json|mbstring|openssl|pdo|pdo_pgsql|tokenizer|xml|gd|zip"
```

### Requisitos Opcionales (Recomendados)

- **Node.js**: 18+ (para compilar assets CSS/JS)
- **NPM**: Última versión estable
- **PostgreSQL Client Tools**: Para gestión de base de datos

---

## 📝 Instalación Paso a Paso

> ⚠️ **IMPORTANTE**: Sigue estos pasos en orden. No omitas ningún paso.

### 1. Verificación de Requisitos

Antes de comenzar, verifica que tienes todo instalado:

#### Verificar PHP
```bash
php -v
# Debe mostrar PHP 8.1 o superior
```

#### Verificar Composer
```bash
composer --version
# Debe mostrar la versión de Composer instalada
```

#### Verificar PostgreSQL
```bash
psql --version
# Debe mostrar PostgreSQL 17 o 18
```

#### Verificar Node.js (Opcional)
```bash
node -v
npm -v
# Debe mostrar Node.js 18+ y NPM
```

### 2. Clonar el Repositorio

```bash
# Clonar el repositorio
git clone https://github.com/danielgonzalesarce/DigitalXpress.git

# Navegar al directorio del proyecto
cd DigitalXpress
```

### 3. Instalar Dependencias

#### Instalar Dependencias de PHP (Composer)
```bash
composer install
```

> ⏱️ **Tiempo estimado**: 2-5 minutos dependiendo de tu conexión a internet.

#### Instalar Dependencias de Node.js (Opcional)
```bash
npm install
```

> ⏱️ **Tiempo estimado**: 1-3 minutos.

### 4. Configurar Entorno

#### Crear archivo `.env`
```bash
# Windows (CMD)
copy .env.example .env

# Windows (PowerShell)
Copy-Item .env.example .env

# Linux/Mac
cp .env.example .env
```

#### Generar Clave de Aplicación
```bash
php artisan key:generate
```

> ✅ **Verificación**: Deberías ver el mensaje "Application key set successfully."

### 5. Configurar Base de Datos

#### Paso 5.1: Crear Base de Datos en PostgreSQL

**Opción A: Usando psql (Línea de comandos)**
```bash
# Conectar a PostgreSQL
psql -U postgres

# Crear la base de datos
CREATE DATABASE digitalxpress;

# Verificar que se creó
\l

# Salir de psql
\q
```

**Opción B: Usando pgAdmin (Interfaz gráfica)**
1. Abre pgAdmin
2. Conecta a tu servidor PostgreSQL
3. Click derecho en "Databases" → "Create" → "Database"
4. Nombre: `digitalxpress`
5. Click en "Save"

#### Paso 5.2: Configurar Credenciales en `.env`

Abre el archivo `.env` y actualiza las siguientes líneas con tus credenciales de PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=digitalxpress
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña_aqui
```

> ⚠️ **IMPORTANTE**: Reemplaza `tu_contraseña_aqui` con tu contraseña real de PostgreSQL.

#### Paso 5.3: Probar Conexión a Base de Datos

```bash
php artisan migrate:status
```

> ✅ **Verificación**: Si la conexión es exitosa, verás una tabla con el estado de las migraciones. Si hay error, revisa tus credenciales en `.env`.

### 6. Ejecutar Migraciones y Seeders

#### Ejecutar Migraciones
```bash
php artisan migrate --force
```

> ⏱️ **Tiempo estimado**: 10-30 segundos.

> ✅ **Verificación**: Deberías ver mensajes como "Migrating: 2025_01_01_000001_create_users_table" y al final "Migration completed successfully."

#### Ejecutar Seeders (Datos de Prueba)
```bash
php artisan db:seed --force
```

> ⏱️ **Tiempo estimado**: 5-10 segundos.

> ✅ **Verificación**: Esto creará:
> - 4 usuarios de prueba (Admin, Cliente, Técnico, VIP)
> - 8 categorías de productos
> - Productos de ejemplo

### 7. Configurar Storage

#### Crear Enlace Simbólico para Storage
```bash
php artisan storage:link
```

> ✅ **Verificación**: Deberías ver "The [public/storage] link has been connected to [storage/app/public]."

### 8. Compilar Assets (Opcional)

Si instalaste Node.js, puedes compilar los assets:

```bash
npm run build
```

> ⏱️ **Tiempo estimado**: 30-60 segundos.

> ℹ️ **Nota**: Si no tienes Node.js instalado, puedes omitir este paso. Los assets ya están compilados en el repositorio.

### 9. Iniciar el Servidor

#### Opción A: Usando Artisan (Recomendado)
```bash
php artisan serve --port=8081
```

#### Opción B: Usando Scripts Incluidos
```bash
# Windows
.\serve.bat

# Linux/Mac
./serve.sh
```

> ✅ **Verificación**: Deberías ver un mensaje como:
> ```
> INFO  Server running on [http://127.0.0.1:8081]
> ```

### 10. Verificar Instalación

#### Paso 10.1: Abrir en el Navegador

Abre tu navegador y visita:
```
http://127.0.0.1:8081
```

> ✅ **Verificación**: Deberías ver la página principal de DigitalXpress.

#### Paso 10.2: Probar Login de Administrador

1. Haz clic en "Iniciar Sesión"
2. Usa las credenciales:
   - **Email**: `admin@digitalxpress.com`
   - **Contraseña**: `password`
3. Deberías ser redirigido al dashboard de administración

#### Paso 10.3: Verificar Panel de Administración

Visita:
```
http://127.0.0.1:8081/admin/dashboard
```

> ✅ **Verificación**: Deberías ver el dashboard con estadísticas y gráficos.

---

## ⚙️ Configuración Detallada

### Configurar Autenticación con Google OAuth (Opcional)

Para habilitar el login con Google:

#### Paso 1: Crear Proyecto en Google Cloud Console

1. Ve a [Google Cloud Console](https://console.cloud.google.com/)
2. Crea un nuevo proyecto o selecciona uno existente
3. Habilita la **API de Google+**

#### Paso 2: Crear Credenciales OAuth 2.0

1. Ve a **"APIs & Services"** → **"Credentials"**
2. Click en **"Create Credentials"** → **"OAuth client ID"**
3. Si es la primera vez, configura la **OAuth consent screen**:
   - Tipo: External
   - Nombre de la app: DigitalXpress
   - Email de soporte: tu email
   - Click en "Save and Continue"
4. En **"Scopes"**, agrega:
   - `userinfo.email`
   - `userinfo.profile`
5. En **"OAuth Client ID"**:
   - Tipo: Web application
   - Nombre: DigitalXpress Web Client
   - **Authorized redirect URIs**: `http://127.0.0.1:8081/auth/google/callback`
   - Click en "Create"

#### Paso 3: Configurar en `.env`

Agrega las siguientes líneas a tu archivo `.env`:

```env
GOOGLE_CLIENT_ID=tu_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=tu_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8081/auth/google/callback
```

> ⚠️ **IMPORTANTE**: Reemplaza `tu_client_id` y `tu_client_secret` con los valores reales de Google Cloud Console.

#### Paso 4: Verificar Funcionamiento

1. Visita `http://127.0.0.1:8081`
2. Haz clic en "Iniciar Sesión"
3. Deberías ver el botón **"Continuar con Google"**
4. Al hacer clic, deberías ser redirigido a Google para autenticarte

### Configurar Correo Electrónico (Opcional)

Para habilitar el envío de correos electrónicos:

#### Configuración SMTP (Gmail)

Edita el archivo `.env` con tus credenciales SMTP:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_contraseña_de_aplicacion
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

> ⚠️ **IMPORTANTE**: Para Gmail, necesitas usar una **Contraseña de aplicación**, no tu contraseña normal. Genera una en: [Google Account Security](https://myaccount.google.com/apppasswords)

#### Probar Envío de Correo

```bash
php artisan tinker
```

Luego en la consola:
```php
Mail::raw('Test email', function($message) {
    $message->to('tu_email@ejemplo.com')
            ->subject('Test Email');
});
```

---

## 👤 Usuarios de Prueba

El sistema incluye los siguientes usuarios predefinidos (creados por el seeder):

| Usuario | Email | Contraseña | Rol | Descripción |
|---------|-------|------------|-----|-------------|
| **Daniel Admin** | admin@digitalxpress.com | password | Administrador | Acceso completo al sistema |
| **María García** | cliente@digitalxpress.com | password | Cliente | Usuario estándar de la tienda |
| **Carlos Técnico** | tecnico@digitalxpress.com | password | Técnico | Acceso al módulo de reparaciones |
| **Ana VIP** | vip@digitalxpress.com | password | VIP | Usuario con beneficios especiales |

> ⚠️ **IMPORTANTE**: 
> - **Cambia estas contraseñas en producción**
> - Todos los usuarios tienen el email verificado automáticamente
> - Puedes iniciar sesión con cualquiera de estos usuarios para probar diferentes funcionalidades

### Acceder al Panel de Administración

1. Inicia sesión con: `admin@digitalxpress.com` / `password`
2. Navega a: `http://127.0.0.1:8081/admin/dashboard`
3. O haz clic en el menú de usuario → "Panel de Administración"

---

## 🎮 Uso del Sistema

### Iniciar el Servidor de Desarrollo

```bash
# Usando Artisan
php artisan serve --port=8081

# O usando los scripts incluidos
.\serve.bat      # Windows
./serve.sh       # Linux/Mac
```

### URLs Principales

| Ruta | Descripción | Acceso |
|------|-------------|--------|
| `http://127.0.0.1:8081` | Página principal | Público |
| `http://127.0.0.1:8081/productos` | Catálogo de productos | Público |
| `http://127.0.0.1:8081/reparaciones` | Servicio de reparaciones | Público |
| `http://127.0.0.1:8081/carrito` | Carrito de compras | Usuarios |
| `http://127.0.0.1:8081/favoritos` | Lista de favoritos | Usuarios autenticados |
| `http://127.0.0.1:8081/admin/dashboard` | Panel de administración | Solo Admin |
| `http://127.0.0.1:8081/admin/products` | Gestión de productos | Solo Admin |
| `http://127.0.0.1:8081/admin/activity-logs` | Sistema de auditoría | Solo Admin |

### Funcionalidades Principales

#### Para Usuarios
- ✅ Navegar catálogo de productos
- ✅ Buscar y filtrar productos
- ✅ Agregar productos al carrito
- ✅ Agregar productos a favoritos
- ✅ Realizar compras
- ✅ Solicitar servicios de reparación
- ✅ Ver historial de pedidos

#### Para Administradores
- ✅ Ver dashboard con estadísticas
- ✅ Gestionar productos (crear, editar, eliminar)
- ✅ Gestionar categorías
- ✅ Gestionar pedidos
- ✅ Gestionar usuarios
- ✅ Ver sistema de auditoría
- ✅ Ver reportes y estadísticas

---

## 🔧 Solución de Problemas Comunes

### Error: "Class 'PDO' not found"

**Causa**: Extensión PDO de PHP no está habilitada.

**Solución**:
1. Abre tu archivo `php.ini`
2. Busca y descomenta: `extension=pdo`
3. Reinicia tu servidor web

### Error: "SQLSTATE[08006] [7] could not connect to server"

**Causa**: PostgreSQL no está corriendo o las credenciales son incorrectas.

**Solución**:
1. Verifica que PostgreSQL esté corriendo:
   ```bash
   # Windows
   services.msc  # Busca "postgresql" en servicios
   
   # Linux
   sudo systemctl status postgresql
   ```
2. Verifica las credenciales en `.env`
3. Prueba la conexión:
   ```bash
   psql -U postgres -h 127.0.0.1 -d digitalxpress
   ```

### Error: "The stream or file could not be opened"

**Causa**: Permisos incorrectos en la carpeta `storage`.

**Solución**:
```bash
# Linux/Mac
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Windows (si usas IIS)
# Asegúrate de que IIS_IUSRS tenga permisos de escritura en storage/
```

### Error: "No application encryption key has been specified"

**Causa**: No se generó la clave de aplicación.

**Solución**:
```bash
php artisan key:generate
```

### Error: "Route [admin.dashboard] not defined"

**Causa**: Caché de rutas desactualizado.

**Solución**:
```bash
php artisan route:clear
php artisan cache:clear
php artisan config:clear
```

### Error: "419 Page Expired" al enviar formularios

**Causa**: Token CSRF expirado o sesión inválida.

**Solución**:
1. Limpia el caché del navegador
2. Limpia el caché de Laravel:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```
3. Verifica que `APP_KEY` esté configurado en `.env`

### Los productos no se muestran

**Causa**: No se ejecutaron los seeders o hay un problema con la base de datos.

**Solución**:
```bash
# Verificar que existan productos
php artisan tinker
>>> App\Models\Product::count()

# Si es 0, ejecutar seeders
php artisan db:seed --force
```

### Error al compilar assets con npm

**Causa**: Node.js no está instalado o versión incorrecta.

**Solución**:
1. Verifica la versión: `node -v` (debe ser 18+)
2. Si no está instalado, instálalo desde [nodejs.org](https://nodejs.org/)
3. Ejecuta: `npm install` y luego `npm run build`

---

## 🔧 Comandos Útiles

### Gestión de Caché

```bash
# Limpiar caché de aplicación
php artisan cache:clear

# Limpiar caché de configuración
php artisan config:clear

# Limpiar caché de vistas
php artisan view:clear

# Limpiar caché de rutas
php artisan route:clear

# Limpiar todo el caché de una vez
php artisan optimize:clear
```

### Base de Datos

```bash
# Ver estado de migraciones
php artisan migrate:status

# Ejecutar migraciones
php artisan migrate

# Ejecutar migraciones con seeders (CUIDADO: borra datos existentes)
php artisan migrate:fresh --seed

# Crear nueva migración
php artisan make:migration create_nombre_tabla_table

# Crear nuevo seeder
php artisan make:seeder NombreSeeder

# Ejecutar seeders específicos
php artisan db:seed --class=UserSeeder
```

### Servidor

```bash
# Iniciar servidor de desarrollo
php artisan serve --port=8081

# Iniciar servidor en otro puerto
php artisan serve --port=8000 --host=0.0.0.0

# Ver todas las rutas disponibles
php artisan route:list
```

### Desarrollo

```bash
# Crear nuevo controlador
php artisan make:controller NombreController

# Crear nuevo modelo
php artisan make:model NombreModel

# Crear modelo con migración y controlador
php artisan make:model NombreModel -mcr

# Abrir consola interactiva (Tinker)
php artisan tinker
```

### Storage

```bash
# Crear enlace simbólico para storage
php artisan storage:link

# Verificar permisos de storage
ls -la storage/
```

---

## 📁 Estructura del Proyecto

```
DigitalXpress/
├── app/
│   ├── Console/Commands/      # Comandos Artisan personalizados
│   ├── Http/
│   │   ├── Controllers/       # Controladores de la aplicación
│   │   │   ├── Admin/         # Controladores del panel admin
│   │   │   └── Auth/          # Controladores de autenticación
│   │   └── Middleware/        # Middleware personalizado
│   ├── Mail/                  # Clases Mailable
│   ├── Models/                # Modelos Eloquent
│   ├── Providers/             # Service Providers
│   ├── Services/              # Servicios de la aplicación
│   └── Traits/                # Traits reutilizables (LogsActivity)
├── bootstrap/                 # Archivos de arranque
├── config/                    # Archivos de configuración
├── database/
│   ├── migrations/            # Migraciones de base de datos
│   └── seeders/               # Seeders para datos de prueba
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       ├── CategorySeeder.php
│       └── ProductSeeder.php
├── public/                    # Archivos públicos (punto de entrada)
│   ├── index.php             # Punto de entrada principal
│   └── storage/              # Enlace simbólico a storage/app/public
├── resources/
│   ├── css/                  # Estilos CSS
│   ├── js/                   # JavaScript
│   └── views/                # Vistas Blade
│       ├── admin/            # Vistas del panel admin
│       ├── auth/             # Vistas de autenticación
│       ├── products/         # Vistas de productos
│       ├── cart/             # Vistas del carrito
│       ├── checkout/         # Vistas de checkout
│       └── favorites/        # Vistas de favoritos
├── routes/
│   ├── auth.php              # Rutas de autenticación
│   └── web.php               # Rutas web principales
├── storage/                  # Archivos de almacenamiento
│   ├── app/                  # Archivos de la aplicación
│   ├── framework/            # Archivos del framework
│   └── logs/                 # Logs de la aplicación
├── tests/                    # Pruebas automatizadas
├── vendor/                   # Dependencias de Composer
├── .env                      # Variables de entorno (NO subir a Git)
├── .env.example              # Ejemplo de variables de entorno
├── composer.json             # Dependencias de PHP
├── package.json              # Dependencias de Node.js
└── README.md                 # Este archivo
```

---

## 📚 Documentación Adicional

- **[INSTALACION_AUTOMATICA.md](INSTALACION_AUTOMATICA.md)** - Guía completa de instalación automática con scripts
- **[Laravel Documentation](https://laravel.com/docs)** - Documentación oficial de Laravel
- **[PostgreSQL Documentation](https://www.postgresql.org/docs/)** - Documentación oficial de PostgreSQL
- **[Bootstrap Documentation](https://getbootstrap.com/docs/5.0/getting-started/introduction/)** - Documentación de Bootstrap 5

---

## 📞 Soporte Técnico

### Antes de Contactar Soporte

1. ✅ Verifica que seguiste todos los pasos de instalación
2. ✅ Revisa la sección [Solución de Problemas](#-solución-de-problemas-comunes)
3. ✅ Revisa los logs en `storage/logs/laravel.log`
4. ✅ Verifica que todos los requisitos estén cumplidos

### Obtener Ayuda

- 📧 **Abre un Issue** en [GitHub Issues](https://github.com/danielgonzalesarce/DigitalXpress/issues)
- 🔍 **Revisa los logs**: `storage/logs/laravel.log`
- 📖 **Consulta la documentación**: [INSTALACION_AUTOMATICA.md](INSTALACION_AUTOMATICA.md)

### Información Útil para Soporte

Si necesitas ayuda, proporciona la siguiente información:

1. **Versión de PHP**: `php -v`
2. **Versión de Composer**: `composer --version`
3. **Versión de PostgreSQL**: `psql --version`
4. **Sistema Operativo**: Windows/Linux/Mac y versión
5. **Mensaje de error completo** (si aplica)
6. **Últimas líneas del log**: `tail -n 50 storage/logs/laravel.log`

---

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor sigue estos pasos:

1. **Fork** el proyecto
2. **Crea** una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. **Commit** tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. **Push** a la rama (`git push origin feature/AmazingFeature`)
5. **Abre** un Pull Request

### Guía de Contribución

- Sigue las convenciones de código de Laravel
- Escribe código limpio y comentado
- Agrega tests para nuevas funcionalidades
- Actualiza la documentación si es necesario

---

## 📄 Licencia

Este proyecto está bajo la **Licencia MIT**. Ver el archivo `LICENSE` para más detalles.

---

## 👨‍💻 Autor

**Daniel González Arce**

- GitHub: [@danielgonzalesarce](https://github.com/danielgonzalesarce)
- Repositorio: [DigitalXpress](https://github.com/danielgonzalesarce/DigitalXpress)

---

<div align="center">

### ⭐ Si te gusta este proyecto, ¡dale una estrella! ⭐

**Desarrollado con ❤️ usando Laravel**

</div>
