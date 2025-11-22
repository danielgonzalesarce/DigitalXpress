# Archivos de la Página de Inicio (Home)
## URL: http://127.0.0.1:8081/

---

## 📁 ARCHIVOS PRINCIPALES

### 1. **Rutas** 
📄 `routes/web.php` (línea 14)
```php
Route::get('/', [HomeController::class, 'index'])->name('home');
```

---

### 2. **Controlador**
📄 `app/Http/Controllers/HomeController.php`
- **Método:** `index()`
- **Funcionalidad:** 
  - Obtiene categorías activas
  - Obtiene productos para el carrusel (destacados o últimos)
  - Obtiene productos destacados
  - Obtiene últimos productos
  - Pasa datos a la vista `home`

---

### 3. **Vista Principal**
📄 `resources/views/home.blade.php`
- **Extiende:** `layouts.app`
- **Contenido:**
  - Hero Section (Carrusel de Productos)
  - Feature Cards (Tarjetas de características)
  - Featured Products (Productos Destacados)
  - Latest Products (Últimos Productos)
  - Call to Action
  - JavaScript para el carrusel con colores dinámicos

---

### 4. **Layout Principal**
📄 `resources/views/layouts/app.blade.php`
- **Contenido:**
  - HTML base y meta tags
  - Estilos CSS (incluyendo modo oscuro/claro)
  - Navbar (Barra de navegación superior)
  - Category Navigation (Navegación de categorías)
  - Footer (Pie de página)
  - Login Modal (Modal de inicio de sesión)
  - Delete Account Modal (Modal de eliminar cuenta)
  - Scripts JavaScript (modo oscuro/claro, validaciones)

---

### 5. **Modal de Login**
📄 `resources/views/auth/login-modal.blade.php`
- **Incluido en:** `layouts/app.blade.php` (línea 599)
- **Contenido:**
  - Tabs de Login y Registro
  - Formulario de inicio de sesión
  - Formulario de registro
  - Botón de Google OAuth (redirige a página de desarrollo)

---

### 6. **View Composer (Categorías)**
📄 `app/Providers/AppServiceProvider.php`
- **Método:** `boot()`
- **Funcionalidad:** 
  - Comparte categorías con productos activos en todas las vistas
  - Filtra solo categorías específicas: Accesorios, Laptops, Relojes, Televisores, Celulares, Cámaras
  - Variable compartida: `$categoriesWithProducts`

---

## 📦 MODELOS

### 7. **Modelo Product**
📄 `app/Models/Product.php`
- **Relaciones:**
  - `category()` - BelongsTo Category
  - `cartItems()` - HasMany CartItem
  - `orderItems()` - HasMany OrderItem
- **Atributos calculados:**
  - `current_price` - Precio actual (sale_price o price)
  - `is_on_sale` - Si está en oferta
  - `image_url` - URL de la imagen del producto

### 8. **Modelo Category**
📄 `app/Models/Category.php`
- **Relaciones:**
  - `products()` - HasMany Product

---

## 🎨 ESTILOS Y SCRIPTS

### 9. **Estilos CSS**
📄 `resources/css/app.css`
- Estilos base de Tailwind CSS

### 10. **JavaScript Principal**
📄 `resources/js/app.js`
- Inicialización de Alpine.js

### 11. **JavaScript Bootstrap**
📄 `resources/js/bootstrap.js`
- Configuración de Axios

---

## 🔧 COMPONENTES (Opcionales, no todos se usan en home)

### 12. **Componentes Blade**
📁 `resources/views/components/`
- `application-logo.blade.php`
- `auth-session-status.blade.php`
- `danger-button.blade.php`
- `dropdown-link.blade.php`
- `dropdown.blade.php`
- `input-error.blade.php`
- `input-label.blade.php`
- `modal.blade.php` ⭐ (Usado en login-modal)
- `nav-link.blade.php`
- `primary-button.blade.php`
- `responsive-nav-link.blade.php`
- `secondary-button.blade.php`
- `text-input.blade.php`

---

## 📋 ESTRUCTURA DE DATOS

### Variables disponibles en `home.blade.php`:

1. **`$categories`** - Todas las categorías activas
2. **`$carouselProducts`** - Productos para el carrusel (máximo 5)
3. **`$featuredProducts`** - Productos destacados (máximo 8)
4. **`$latestProducts`** - Últimos productos (máximo 8)

### Variables compartidas globalmente (vía View Composer):

1. **`$categoriesWithProducts`** - Categorías filtradas con productos activos

---

## 🎯 SECCIONES DE LA PÁGINA HOME

1. **Hero Section (Carrusel)**
   - Carrusel de productos destacados
   - Cambio automático cada 5 segundos
   - Colores dinámicos por producto
   - Botones de navegación funcionales

2. **Feature Cards**
   - Entrega Rápida
   - Garantía
   - Soporte 24/7
   - Pago Seguro

3. **Productos Destacados**
   - Grid de productos destacados
   - Máximo 8 productos

4. **Últimos Productos**
   - Grid de últimos productos agregados
   - Máximo 8 productos

5. **Call to Action**
   - Sección de llamada a la acción

---

## 🔗 DEPENDENCIAS

- **Bootstrap 5** - Framework CSS y JavaScript
- **Font Awesome** - Iconos
- **Alpine.js** - JavaScript reactivo
- **Axios** - Cliente HTTP

---

## 📝 NOTAS IMPORTANTES

- El carrusel solo se muestra si hay productos (`$carouselProducts->count() > 0`)
- Los productos se filtran por: `is_active = true` y `in_stock = true`
- El modo oscuro/claro se guarda en `localStorage`
- Las categorías en la navegación se filtran dinámicamente por productos activos

