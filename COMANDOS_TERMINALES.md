# 🚀 Comandos para Ejecutar el Proyecto DigitalXpress

## 📋 Configuración Inicial (Solo Primera Vez)

### Terminal 1 - Configuración del Proyecto:

```bash
# 1. Instalar dependencias de PHP
composer install

# 2. Instalar dependencias de Node.js
npm install

# 3. Copiar archivo de configuración
copy .env.example .env

# 4. Generar clave de aplicación
php artisan key:generate

# 5. Configurar base de datos en el archivo .env
# Editar: DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 6. Crear base de datos (PostgreSQL)
# Conéctate a PostgreSQL y ejecuta:
# CREATE DATABASE digitalxpress;

# 7. Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# 8. Compilar assets para producción
npm run build
```

---

## 🎯 Ejecutar el Proyecto (Desarrollo)

### Opción A: Desarrollo Simple (Una Terminal)

**Terminal 1 - Servidor Laravel:**
```bash
php artisan serve --port=8081
```

El proyecto estará disponible en: `http://127.0.0.1:8081`

---

### Opción B: Desarrollo con Hot Reload (Dos Terminales)

**Terminal 1 - Servidor Laravel:**
```bash
php artisan serve --port=8081
```

**Terminal 2 - Servidor Vite (Hot Reload para CSS/JS):**
```bash
npm run dev
```

Con esta configuración:
- Laravel corre en: `http://127.0.0.1:8081`
- Vite corre en: `http://localhost:5173` (automáticamente)
- Los cambios en CSS/JS se reflejan automáticamente sin recargar

---

## 🔧 Comandos Útiles Durante el Desarrollo

### Limpiar Caché (Si hay problemas):
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Ver Rutas del Proyecto:
```bash
php artisan route:list
```

### Ver Información del Sistema:
```bash
php artisan about
```

---

## 🗄️ Comandos de Base de Datos

### Ejecutar Migraciones:
```bash
php artisan migrate
```

### Reiniciar Base de Datos (Elimina todo y recrea):
```bash
php artisan migrate:fresh --seed
```

### Ejecutar un Seeder Específico:
```bash
php artisan db:seed --class=TestDataSeeder
```

---

## 🎨 Comandos de Assets (CSS/JS)

### Compilar para Producción:
```bash
npm run build
```

### Modo Desarrollo con Watch:
```bash
npm run dev
```

---

## 🌐 URLs del Proyecto

Una vez ejecutado `php artisan serve --port=8081`:

- **Inicio**: `http://127.0.0.1:8081/`
- **Login**: `http://127.0.0.1:8081/login`
- **Registro**: `http://127.0.0.1:8081/register`
- **Productos**: `http://127.0.0.1:8081/productos`
- **Carrito**: `http://127.0.0.1:8081/carrito`
- **Checkout**: `http://127.0.0.1:8081/checkout`
- **Panel Admin**: `http://127.0.0.1:8081/admin/dashboard`

---

## 👤 Usuarios de Prueba

Después de ejecutar los seeders, puedes usar estos usuarios:

| Usuario        | Email                           | Contraseña | Rol           |
|----------------|---------------------------------|------------|---------------|
| Daniel Admin   | admin@digitalxpress.com        | password   | Administrador |
| María García   | cliente@digitalxpress.com      | password   | Cliente       |
| Carlos Técnico | tecnico@digitalxpress.com      | password   | Técnico       |
| Ana VIP        | vip@digitalxpress.com          | password   | VIP           |

---

## ⚡ Comandos Rápidos por Escenario

### Primera Vez (Setup Completo):
```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
# Configurar .env con datos de base de datos
php artisan migrate:fresh --seed
npm run build
php artisan serve --port=8081
```

### Desarrollo Diario (Solo Iniciar):
```bash
php artisan serve --port=8081
```

### Desarrollo con Hot Reload:
```bash
# Terminal 1:
php artisan serve --port=8081

# Terminal 2:
npm run dev
```

### Si hay Problemas (Limpiar Todo):
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan serve --port=8081
```

---

## ⚠️ Requisitos Previos

- **PHP 8.1 o superior**
- **Composer**
- **PostgreSQL 17 o 18**
- **Node.js y NPM** (para compilar assets)

---

## 📝 Notas Importantes

1. El puerto por defecto es **8081**
2. Si el puerto está ocupado, puedes usar otro: `php artisan serve --port=8082`
3. Para desarrollo con cambios en tiempo real de CSS/JS, usa `npm run dev` en una segunda terminal
4. Para producción, siempre compila los assets con `npm run build` antes de desplegar

