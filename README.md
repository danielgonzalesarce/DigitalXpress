# 🛒 DigitalXpress - E-commerce Store with Repair Services

Una aplicación web completa desarrollada en Laravel que combina una tienda de e-commerce con servicios técnicos especializados. Incluye sistema de autenticación, carrito de compras funcional, gestión de reparaciones y panel de administración.

## 🚀 Características Principales

- **🛍️ Tienda Completa**: Catálogo de productos con 8 categorías específicas
- **🛒 Carrito Inteligente**: Funciona para usuarios registrados e invitados
- **🔧 Servicio Técnico**: Dashboard completo para gestión de reparaciones
- **🔐 Autenticación Avanzada**: Modal personalizado con validaciones
- **📄 Reportes PDF**: Generación automática de reportes de reparaciones
- **📱 Diseño Responsive**: Optimizado para todos los dispositivos

## 🛠️ Tecnologías Utilizadas

- **Backend**: Laravel 12.7.1, PHP 8.3+
- **Base de Datos**: PostgreSQL 17/18
- **Frontend**: Bootstrap 5, HTML5, CSS3, JavaScript
- **Autenticación**: Laravel Breeze
- **PDF**: DomPDF
- **Iconos**: Font Awesome

## 📦 Instalación

### Requisitos Previos
- PHP 8.1 o superior
- Composer
- PostgreSQL 17 o 18
- Node.js y NPM (opcional, para compilar assets)

### 🚀 Instalación Automática (Recomendado)

La forma más fácil de instalar DigitalXpress es usando nuestros scripts de instalación automática:

#### Para Linux/Mac:
```bash
git clone https://github.com/danielgonzalesarce/DigitalXpress.git
cd DigitalXpress
chmod +x install.sh
./install.sh
```

#### Para Windows:
```batch
git clone https://github.com/danielgonzalesarce/DigitalXpress.git
cd DigitalXpress
install.bat
```

Los scripts automáticamente:
- ✅ Verificarán que tengas todos los requisitos instalados
- ✅ Instalarán las dependencias de PHP (Composer)
- ✅ Instalarán las dependencias de Node.js (NPM)
- ✅ Crearán el archivo `.env` desde `.env.example`
- ✅ Generarán la clave de aplicación
- ✅ Te preguntarán si deseas ejecutar las migraciones
- ✅ Compilarán los assets
- ✅ Limpiarán el caché

### 📝 Instalación Manual

Si prefieres instalar manualmente:

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/danielgonzalesarce/DigitalXpress.git
   cd DigitalXpress
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   npm install  # Opcional
   ```

3. **Configurar entorno**
   ```bash
   cp .env.example .env  # Linux/Mac
   copy .env.example .env  # Windows
   php artisan key:generate
   ```

4. **Configurar base de datos**
   - Editar `.env` con tus credenciales de PostgreSQL
   - Crear la base de datos en PostgreSQL:
     ```sql
     CREATE DATABASE digitalxpress;
     ```

5. **Ejecutar migraciones y seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Compilar assets (opcional)**
   ```bash
   npm run build
   ```

7. **Iniciar servidor**
   ```bash
   php artisan serve
   ```

8. **Acceder a la aplicación**
   - Abrir navegador en `http://127.0.0.1:8000`

> 📖 Para más detalles, consulta la [Guía de Instalación Completa](INSTALL.md)

## 👤 Usuarios de Prueba

| Usuario | Email | Contraseña | Rol |
|---------|-------|------------|-----|
| Daniel Admin | admin@digitalxpress.com | password | Administrador |
| María García | cliente@digitalxpress.com | password | Cliente |
| Carlos Técnico | tecnico@digitalxpress.com | password | Técnico |
| Ana VIP | vip@digitalxpress.com | password | VIP |

## 📱 Categorías de Productos

- 💻 **Laptops** - Computadoras portátiles
- ⌚ **Relojes** - Smartwatches y relojes inteligentes
- 📺 **Televisores** - Smart TVs y televisores HD
- 🖱️ **Mouses** - Mouse gaming y ergonómicos
- ⌨️ **Teclados** - Teclados mecánicos y ergonómicos
- 🎧 **Audífonos** - Audífonos inalámbricos y con cable
- 📱 **Celulares** - Smartphones y teléfonos móviles
- 📷 **Cámaras** - Cámaras digitales y de acción

## 🎯 Funcionalidades

### 🛍️ E-commerce
- Catálogo de productos con filtros y búsqueda
- Carrito de compras persistente (usuarios e invitados)
- Sistema de categorías
- Gestión de stock
- Precios con descuentos

### 🔧 Servicio Técnico
- Dashboard de reparaciones
- Formulario de solicitud de reparaciones
- Subida de imágenes de dispositivos
- Seguimiento de estado de reparaciones
- Generación de reportes en PDF
- Sistema de citas y contacto

### 👤 Gestión de Usuarios
- Registro e inicio de sesión
- Perfil de usuario personalizable
- Historial de compras y reparaciones
- Eliminación de cuenta
- Mensajes de bienvenida personalizados

## 📁 Estructura del Proyecto

```
DigitalXpress/
├── app/
│   ├── Http/Controllers/     # Controladores
│   ├── Models/              # Modelos Eloquent
│   └── Http/Middleware/     # Middleware personalizado
├── database/
│   ├── migrations/          # Migraciones de base de datos
│   └── seeders/            # Seeders para datos de prueba
├── resources/
│   └── views/              # Vistas Blade
├── routes/
│   └── web.php             # Rutas web
└── public/                 # Archivos públicos
```

## 🔧 Comandos Útiles

```bash
# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Regenerar migraciones
php artisan migrate:fresh --seed

# Ejecutar servidor en puerto específico
php artisan serve --port=8080
```

## 📄 Licencia

Este proyecto está bajo la Licencia MIT.

## 👨‍💻 Autor

**Daniel González Arce**
- GitHub: [@danielgonzalesarce](https://github.com/danielgonzalesarce)

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📞 Soporte

Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarme.

---

⭐ **¡No olvides darle una estrella al repositorio si te gusta el proyecto!** ⭐