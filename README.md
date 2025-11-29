# 🛒 DigitalXpress - E-commerce Store with Repair Services

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.7.1-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-17/18-316192?style=for-the-badge&logo=postgresql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)

**Una aplicación web completa desarrollada en Laravel que combina una tienda de e-commerce con servicios técnicos especializados.**

[Características](#-características-principales) • [Instalación](#-instalación-rápida) • [Documentación](#-documentación) • [Soporte](#-soporte)

</div>

---

## 📋 Tabla de Contenidos

- [Características Principales](#-características-principales)
- [Tecnologías Utilizadas](#-tecnologías-utilizadas)
- [Requisitos del Sistema](#-requisitos-del-sistema)
- [Instalación Rápida](#-instalación-rápida)
- [Instalación Manual](#-instalación-manual)
- [Configuración](#-configuración)
- [Uso](#-uso)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Comandos Útiles](#-comandos-útiles)
- [Usuarios de Prueba](#-usuarios-de-prueba)
- [Documentación](#-documentación)
- [Contribuciones](#-contribuciones)
- [Licencia](#-licencia)

---

## 🚀 Características Principales

### 🛍️ E-commerce Completo
- ✅ **Catálogo de productos** con 8 categorías específicas
- ✅ **Carrito inteligente** que funciona para usuarios registrados e invitados
- ✅ **Sistema de categorías** organizado y filtrable
- ✅ **Gestión de stock** en tiempo real
- ✅ **Precios con descuentos** y ofertas especiales
- ✅ **Búsqueda avanzada** de productos

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
- ✅ **Sistema de roles** (Admin, Cliente, Técnico)
- ✅ **Gestión de sesiones** segura

### 👨‍💼 Panel de Administración
- ✅ **Dashboard completo** con estadísticas en tiempo real
- ✅ **Gestión de productos** (CRUD completo)
- ✅ **Gestión de inventario** y stock
- ✅ **Gestión de pedidos** y órdenes
- ✅ **Sistema de auditoría** para rastrear actividades
- ✅ **Gestión de usuarios** y permisos
- ✅ **Sistema de mensajería** entre usuarios y administradores

---

## 🛠️ Tecnologías Utilizadas

| Categoría | Tecnología |
|-----------|-----------|
| **Backend** | Laravel 12.7.1, PHP 8.3+ |
| **Base de Datos** | PostgreSQL 17/18 |
| **Frontend** | Bootstrap 5, HTML5, CSS3, JavaScript |
| **Autenticación** | Laravel Breeze, Laravel Socialite (Google OAuth) |
| **PDF** | DomPDF |
| **Iconos** | Font Awesome 6.0 |
| **Gestión de Paquetes** | Composer, NPM |

---

## 📋 Requisitos del Sistema

### Requisitos Mínimos

- **PHP**: 8.1 o superior
- **Composer**: Última versión
- **PostgreSQL**: 17 o 18
- **Extensiones PHP**: 
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - PDO_PGSQL
  - Tokenizer
  - XML

### Requisitos Opcionales

- **Node.js**: 18+ (para compilar assets)
- **NPM**: Última versión

---

## ⚡ Instalación Rápida

### Método Recomendado: Instalación Automática

DigitalXpress incluye scripts de instalación automática que configuran todo el proyecto en un solo comando.

#### 🪟 Windows

**Opción 1: PowerShell (Recomendado)**
```powershell
git clone https://github.com/danielgonzalesarce/DigitalXpress.git
cd DigitalXpress
.\install.ps1
```

**Opción 2: CMD**
```cmd
git clone https://github.com/danielgonzalesarce/DigitalXpress.git
cd DigitalXpress
install.bat
```

#### 🐧 Linux / 🍎 macOS

```bash
git clone https://github.com/danielgonzalesarce/DigitalXpress.git
cd DigitalXpress
chmod +x install.sh
./install.sh
```

### ¿Qué hace el script automáticamente?

El script de instalación ejecuta los siguientes pasos:

1. ✅ Verifica que PHP y Composer estén instalados
2. ✅ Instala todas las dependencias de PHP (`composer install`)
3. ✅ Crea el archivo `.env` desde `.env.example`
4. ✅ Solicita y configura las credenciales de PostgreSQL
5. ✅ Genera la clave de aplicación Laravel
6. ✅ Crea la base de datos PostgreSQL (si `psql` está disponible)
7. ✅ Ejecuta todas las migraciones (`php artisan migrate`)
8. ✅ Limpia el caché de Laravel
9. ✅ Crea enlaces simbólicos para storage

> 📖 Para más detalles sobre la instalación automática, consulta [INSTALACION_AUTOMATICA.md](INSTALACION_AUTOMATICA.md)

---

## 📝 Instalación Manual

Si prefieres instalar manualmente o el script automático no funciona en tu sistema:

### Paso 1: Clonar el Repositorio

```bash
git clone https://github.com/danielgonzalesarce/DigitalXpress.git
cd DigitalXpress
```

### Paso 2: Instalar Dependencias

   ```bash
# Instalar dependencias de PHP
   composer install

# Instalar dependencias de Node.js (opcional)
npm install
   ```

### Paso 3: Configurar Entorno

   ```bash
# Copiar archivo de configuración
   cp .env.example .env  # Linux/Mac
   copy .env.example .env  # Windows

# Generar clave de aplicación
   php artisan key:generate
   ```

### Paso 4: Configurar Base de Datos

1. **Editar archivo `.env`** con tus credenciales de PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=digitalxpress
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña
```

2. **Crear la base de datos** en PostgreSQL:

     ```sql
     CREATE DATABASE digitalxpress;
     ```

### Paso 5: Ejecutar Migraciones

   ```bash
php artisan migrate --force
   ```

### Paso 6: Compilar Assets (Opcional)

   ```bash
   npm run build
   ```

### Paso 7: Iniciar Servidor de Desarrollo

   ```bash
   php artisan serve --port=8081
   ```

### Paso 8: Acceder a la Aplicación

Abre tu navegador en: **http://127.0.0.1:8081**

---

## ⚙️ Configuración

### Configurar Autenticación con Google (Opcional)

Para habilitar el login con Google OAuth:

1. **Crear proyecto en Google Cloud Console**
   - Ve a [Google Cloud Console](https://console.cloud.google.com/)
   - Crea un nuevo proyecto o selecciona uno existente
   - Habilita la API de Google+

2. **Crear credenciales OAuth 2.0**
   - Ve a "Credenciales" → "Crear credenciales" → "ID de cliente OAuth 2.0"
   - Configura la pantalla de consentimiento OAuth
   - Agrega URI de redirección autorizado: `http://127.0.0.1:8081/auth/google/callback`

3. **Configurar en `.env`**

```env
GOOGLE_CLIENT_ID=tu_client_id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=tu_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8081/auth/google/callback
```

4. **Los usuarios podrán iniciar sesión y crear cuenta con Google**

### Configurar Correo Electrónico (Opcional)

Para habilitar el envío de correos electrónicos:

1. **Editar `.env`** con tus credenciales SMTP:

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

---

## 🎮 Uso

### Iniciar el Servidor de Desarrollo

```bash
# Usando Artisan
php artisan serve --port=8081

# O usando los scripts incluidos
.\serve.bat      # Windows
./serve.sh       # Linux/Mac
```

### Acceder al Panel de Administración

1. Inicia sesión con un usuario administrador
2. Navega a: `http://127.0.0.1:8081/admin/dashboard`

### Acceder a la Tienda

- **Página principal**: `http://127.0.0.1:8081`
- **Catálogo de productos**: `http://127.0.0.1:8081/productos`
- **Servicio de reparaciones**: `http://127.0.0.1:8081/reparaciones`

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
│   └── Traits/                # Traits reutilizables
├── bootstrap/                 # Archivos de arranque
├── config/                    # Archivos de configuración
├── database/
│   ├── migrations/            # Migraciones de base de datos
│   └── seeders/               # Seeders para datos de prueba
├── public/                    # Archivos públicos (punto de entrada)
├── resources/
│   ├── css/                  # Estilos CSS
│   ├── js/                   # JavaScript
│   └── views/                # Vistas Blade
│       ├── admin/            # Vistas del panel admin
│       ├── auth/             # Vistas de autenticación
│       └── ...
├── routes/
│   ├── auth.php              # Rutas de autenticación
│   └── web.php               # Rutas web principales
├── storage/                  # Archivos de almacenamiento
├── tests/                    # Pruebas automatizadas
└── vendor/                   # Dependencias de Composer
```

---

## 🔧 Comandos Útiles

### Gestión de Caché

```bash
# Limpiar todo el caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Limpiar todo de una vez
php artisan optimize:clear
```

### Base de Datos

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar migraciones con seeders
php artisan migrate:fresh --seed

# Crear nueva migración
php artisan make:migration create_nombre_tabla_table

# Crear nuevo seeder
php artisan make:seeder NombreSeeder
```

### Servidor

```bash
# Iniciar servidor de desarrollo
php artisan serve --port=8081

# Iniciar servidor en otro puerto
php artisan serve --port=8000 --host=0.0.0.0
```

### Desarrollo

```bash
# Crear nuevo controlador
php artisan make:controller NombreController

# Crear nuevo modelo
php artisan make:model NombreModel

# Crear nuevo modelo con migración y controlador
php artisan make:model NombreModel -mcr
```

---

## 👤 Usuarios de Prueba

El proyecto incluye usuarios de prueba predefinidos:

| Usuario | Email | Contraseña | Rol |
|---------|-------|------------|-----|
| **Daniel Admin** | admin@digitalxpress.com | password | Administrador |
| **María García** | cliente@digitalxpress.com | password | Cliente |
| **Carlos Técnico** | tecnico@digitalxpress.com | password | Técnico |
| **Ana VIP** | vip@digitalxpress.com | password | VIP |

> ⚠️ **Importante**: Cambia las contraseñas en producción.

---

## 📱 Categorías de Productos

El sistema incluye 8 categorías principales:

- 💻 **Laptops** - Computadoras portátiles
- ⌚ **Relojes** - Smartwatches y relojes inteligentes
- 📺 **Televisores** - Smart TVs y televisores HD
- 🖱️ **Mouses** - Mouse gaming y ergonómicos
- ⌨️ **Teclados** - Teclados mecánicos y ergonómicos
- 🎧 **Audífonos** - Audífonos inalámbricos y con cable
- 📱 **Celulares** - Smartphones y teléfonos móviles
- 📷 **Cámaras** - Cámaras digitales y de acción

---

## 📚 Documentación

- **[INSTALACION_AUTOMATICA.md](INSTALACION_AUTOMATICA.md)** - Guía completa de instalación automática
- **[Laravel Documentation](https://laravel.com/docs)** - Documentación oficial de Laravel
- **[PostgreSQL Documentation](https://www.postgresql.org/docs/)** - Documentación oficial de PostgreSQL

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

## 📞 Soporte

Si tienes alguna pregunta o necesitas ayuda:

- 📧 Abre un [Issue](https://github.com/danielgonzalesarce/DigitalXpress/issues) en GitHub
- 📖 Consulta la [documentación de instalación](INSTALACION_AUTOMATICA.md)
- 🔍 Revisa los logs en `storage/logs/laravel.log`

---

<div align="center">

### ⭐ Si te gusta este proyecto, ¡dale una estrella! ⭐

**Desarrollado con ❤️ usando Laravel**

</div>
