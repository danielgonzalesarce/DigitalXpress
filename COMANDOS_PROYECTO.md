# 🚀 COMANDOS PARA EJECUTAR EL PROYECTO DIGITALXPRESS

## ⚡ COMANDO PRINCIPAL

### **Iniciar el Servidor de Desarrollo (Puerto 8081):**

**Opción 1 - Usando Composer (Recomendado):**
```bash
composer serve
```

**Opción 2 - Usando Script Helper (Windows):**
```bash
serve.bat
```

**Opción 3 - Usando Script Helper (Linux/Mac):**
```bash
./serve.sh
```

**Opción 4 - Comando Artisan Directo:**
```bash
php artisan serve --port=8081
```

### **Iniciar en una IP y Puerto Específicos:**
```bash
php artisan serve --host=127.0.0.1 --port=8081
```

---

## 📋 COMANDOS ADICIONALES ÚTILES

### **🔧 Configuración Inicial (Primera Vez):**

```bash
# Instalar dependencias de PHP
composer install

# Instalar dependencias de Node.js
npm install

# Copiar archivo de configuración
copy .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders (datos de prueba)
php artisan db:seed

# Compilar assets (CSS/JS)
npm run build
```

### **🗄️ Base de Datos:**

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar migraciones con seeders
php artisan migrate:fresh --seed

# Ejecutar un seeder específico
php artisan db:seed --class=TestDataSeeder
```

### **🎨 Assets (CSS/JS):**

```bash
# Compilar assets para producción
npm run build

# Compilar assets en modo desarrollo (con watch)
npm run dev
```

### **🧹 Limpieza:**

```bash
# Limpiar caché de configuración
php artisan config:clear

# Limpiar caché de rutas
php artisan route:clear

# Limpiar caché de vistas
php artisan view:clear

# Limpiar todo el caché
php artisan cache:clear
```

### **📊 Información del Proyecto:**

```bash
# Ver todas las rutas
php artisan route:list

# Ver información del sistema
php artisan about
```

---

## 🌐 ACCESO AL PROYECTO

Una vez que ejecutes `php artisan serve --port=8081`, el proyecto estará disponible en:

- **URL Local**: `http://127.0.0.1:8081`
- **URL Alternativa**: `http://localhost:8081`

---

## 📱 PÁGINAS PRINCIPALES

- **Inicio**: `http://127.0.0.1:8081/`
- **Login**: `http://127.0.0.1:8081/login`
- **Registro**: `http://127.0.0.1:8081/register`
- **Productos**: `http://127.0.0.1:8081/productos`
- **Carrito**: `http://127.0.0.1:8081/carrito`
- **Checkout**: `http://127.0.0.1:8081/checkout`
- **Panel Admin**: `http://127.0.0.1:8081/admin/dashboard`

---

## ⚠️ NOTAS IMPORTANTES

1. **Asegúrate de tener PHP instalado** (versión 8.1 o superior)
2. **Asegúrate de tener Composer instalado**
3. **Asegúrate de tener Node.js instalado** (para compilar assets)
4. **La base de datos SQLite** se crea automáticamente en `database/database.sqlite`

---

## 🎯 COMANDO RÁPIDO (Todo en Uno)

Si es la primera vez que ejecutas el proyecto:

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve --port=8081
```

¡Listo! El proyecto estará corriendo en `http://127.0.0.1:8081` 🚀
