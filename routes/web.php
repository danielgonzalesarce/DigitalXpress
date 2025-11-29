<?php

/**
 * Archivo de Rutas Web - DigitalXpress
 * 
 * Define todas las rutas públicas y protegidas de la aplicación.
 * 
 * Estructura:
 * - Rutas públicas (sin autenticación)
 * - Rutas de autenticación (login, registro, etc.)
 * - Rutas protegidas (requieren autenticación)
 * - Rutas de administración (requieren autenticación + rol admin)
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepairController;
use Illuminate\Support\Facades\Route;

/* ============================================
   RUTAS PÚBLICAS (Sin autenticación requerida)
   ============================================ */

/**
 * Página principal - Home
 * Muestra el carrusel de productos destacados y últimas novedades
 * Accesible sin autenticación
 */
Route::get('/', [HomeController::class, 'index'])->name('home');

/**
 * ============================================
 * PRODUCTOS
 * ============================================
 * 
 * Rutas para visualización de productos:
 * - Listado de productos con filtros y búsqueda
 * - Detalle de producto individual
 */
Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/productos/{product}', [ProductController::class, 'show'])->name('products.show');

/**
 * ============================================
 * REPARACIONES - Rutas Públicas y de Usuario
 * ============================================
 * 
 * Rutas para el servicio de reparaciones:
 * - Ver información del servicio (público)
 * - Dashboard de reparaciones del usuario (requiere auth)
 * - Crear nueva solicitud de reparación (requiere auth)
 * - Ver detalles de una reparación (requiere auth)
 * - Agendar cita (requiere auth)
 * - Contactar soporte (requiere auth)
 * - Descargar reporte de reparación (requiere auth)
 */
Route::get('/reparaciones', [RepairController::class, 'index'])->name('repairs.index'); // Página informativa (público)
Route::get('/reparaciones/dashboard', [RepairController::class, 'dashboard'])->name('repairs.dashboard'); // Dashboard del usuario
Route::get('/reparaciones/nueva', [RepairController::class, 'create'])->name('repairs.create'); // Formulario de creación
Route::post('/reparaciones', [RepairController::class, 'store'])->name('repairs.store'); // Guardar nueva reparación
Route::get('/reparaciones/{repair}', [RepairController::class, 'show'])->name('repairs.show'); // Ver detalles de reparación
Route::get('/reparaciones/agendar/cita', [RepairController::class, 'schedule'])->name('repairs.schedule'); // Agendar cita
Route::get('/reparaciones/contactar/soporte', [RepairController::class, 'contact'])->name('repairs.contact'); // Contactar soporte
Route::get('/reparaciones/descargar/reporte', [RepairController::class, 'downloadReport'])->name('repairs.download-report'); // Descargar PDF

/**
 * ============================================
 * CARRITO DE COMPRAS
 * ============================================
 * 
 * Rutas para gestión del carrito:
 * Funciona tanto para usuarios autenticados como invitados (usando session_id)
 * - Ver carrito
 * - Agregar producto al carrito
 * - Actualizar cantidad de un item
 * - Eliminar item del carrito
 * - Vaciar carrito completo
 * - Limpiar productos no disponibles
 */
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/carrito/actualizar/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/eliminar/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/carrito/vaciar', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/carrito/limpiar', [CartController::class, 'cleanup'])->name('cart.cleanup');

/**
 * ============================================
 * CHECKOUT - Proceso de Compra
 * ============================================
 * 
 * Rutas para el proceso de finalización de compra:
 * - Mostrar formulario de checkout
 * - Procesar el pedido y crear la orden
 * - Página de éxito después de la compra
 */
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

/**
 * ============================================
 * PANEL DE ADMINISTRACIÓN
 * ============================================
 * 
 * Rutas protegidas para administradores:
 * Requieren autenticación + middleware 'admin' (usuarios con @digitalxpress.com)
 * Todas las rutas tienen el prefijo '/admin' y el nombre 'admin.*'
 * 
 * Secciones del panel:
 * - Dashboard: Estadísticas y resumen general
 * - Productos: CRUD completo de productos
 * - Inventario: Gestión de stock y movimientos
 * - Pedidos: Gestión de órdenes de compra
 * - Reparaciones: Gestión de servicios técnicos
 * - Análisis: Reportes de ingresos y estadísticas
 * - Usuarios: Gestión de usuarios del sistema
 * - Categorías: CRUD de categorías de productos
 * - Configuración: Ajustes de la tienda (envíos, pagos, información)
 */
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard principal del administrador
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    /**
     * PRODUCTOS - CRUD completo
     * Permite crear, leer, actualizar y eliminar productos del catálogo
     */
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
    
    /**
     * INVENTARIO - Gestión de Stock
     * Permite gestionar el inventario y movimientos de stock de productos
     */
    Route::get('/inventory', [AdminController::class, 'inventoryIndex'])->name('inventory');
    Route::get('/inventory/create', [AdminController::class, 'createInventoryMovement'])->name('inventory.create');
    Route::post('/inventory', [AdminController::class, 'storeInventoryMovement'])->name('inventory.store');
    Route::get('/inventory/{product}/edit', [AdminController::class, 'editInventoryMovement'])->name('inventory.edit');
    Route::put('/inventory/{product}', [AdminController::class, 'updateInventoryMovement'])->name('inventory.update');
    Route::delete('/inventory/{product}', [AdminController::class, 'destroyInventoryMovement'])->name('inventory.destroy');
    
    /**
     * PEDIDOS - Gestión de Órdenes
     * Permite ver, editar y gestionar los pedidos de los clientes
     */
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/create', [AdminController::class, 'createOrder'])->name('orders.create');
    Route::post('/orders', [AdminController::class, 'storeOrder'])->name('orders.store');
    Route::get('/orders/{order}', [AdminController::class, 'orderDetails'])->name('order.details');
    Route::get('/orders/{order}/edit', [AdminController::class, 'editOrder'])->name('orders.edit');
    Route::put('/orders/{order}', [AdminController::class, 'updateOrder'])->name('orders.update');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('order.update-status');
    Route::delete('/orders/{order}', [AdminController::class, 'destroyOrder'])->name('orders.destroy');
    
    /**
     * REPARACIONES - Gestión de Servicios Técnicos (Admin)
     * Permite gestionar las solicitudes de reparación de los clientes
     * Todas las rutas requieren autenticación y rol de administrador
     */
    Route::get('/repairs', [AdminController::class, 'repairs'])->name('repairs'); // Listar todas las reparaciones
    Route::get('/repairs/create', [AdminController::class, 'createRepair'])->name('repairs.create'); // Formulario crear (admin)
    Route::post('/repairs', [AdminController::class, 'storeRepair'])->name('repairs.store'); // Guardar reparación (admin)
    Route::get('/repairs/{repair}/edit', [AdminController::class, 'editRepair'])->name('repairs.edit'); // Formulario editar
    Route::put('/repairs/{repair}', [AdminController::class, 'updateRepair'])->name('repairs.update'); // Actualizar reparación
    Route::delete('/repairs/{repair}', [AdminController::class, 'destroyRepair'])->name('repairs.destroy'); // Eliminar reparación
    
    /**
     * ANÁLISIS - Reportes e Ingresos
     * Muestra estadísticas de ingresos, métodos de pago y distribución de stock
     */
    Route::get('/revenue', [AdminController::class, 'revenue'])->name('revenue');
    
    /**
     * ============================================
     * MENSAJERÍA - Sistema de Mensajería (Admin)
     * ============================================
     * 
     * Rutas para que los administradores gestionen conversaciones con usuarios.
     * Permite ver todas las conversaciones, responder mensajes y marcar como leídos.
     * 
     * Funcionalidades:
     * - Ver todas las conversaciones asignadas al administrador
     * - Ver detalles de una conversación específica con todos sus mensajes
     * - Responder a mensajes de usuarios
     * - Marcar mensajes como leídos manualmente
     * 
     * Relacionado con: Sistema de reparaciones (los usuarios pueden contactar por reparaciones)
     */
    Route::get('/messages', [\App\Http\Controllers\Admin\MessageController::class, 'index'])->name('messages.index'); // Listar todas las conversaciones
    Route::get('/messages/{conversation}', [\App\Http\Controllers\Admin\MessageController::class, 'show'])->name('messages.show'); // Ver conversación específica
    Route::post('/messages/{conversation}/reply', [\App\Http\Controllers\Admin\MessageController::class, 'reply'])->name('messages.reply'); // Responder mensaje
    Route::post('/messages/{conversation}/mark-read', [\App\Http\Controllers\Admin\MessageController::class, 'markAsRead'])->name('messages.markAsRead'); // Marcar como leído
    
    /**
     * AUDITORÍA - Registro de Actividades
     * Muestra el registro de todas las actividades realizadas por los administradores
     */
    Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs');
    
    /**
     * USUARIOS - Gestión de Usuarios
     * Permite crear, editar y eliminar usuarios del sistema
     */
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    
    /**
     * CATEGORÍAS - Gestión de Categorías de Productos
     * Permite crear, editar y eliminar categorías para organizar productos
     */
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');
    
    /**
     * CONFIGURACIÓN - Ajustes de la Tienda
     * Permite configurar información de la tienda, envíos y métodos de pago
     */
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::get('/settings/store', [AdminController::class, 'editStoreSettings'])->name('settings.store');
    Route::put('/settings/store', [AdminController::class, 'updateStoreSettings'])->name('settings.store.update');
    Route::get('/settings/shipping', [AdminController::class, 'editShippingSettings'])->name('settings.shipping');
    Route::put('/settings/shipping', [AdminController::class, 'updateShippingSettings'])->name('settings.shipping.update');
    Route::get('/settings/payment', [AdminController::class, 'editPaymentSettings'])->name('settings.payment');
    Route::put('/settings/payment', [AdminController::class, 'updatePaymentSettings'])->name('settings.payment.update');
});

/**
 * ============================================
 * PERFIL DE USUARIO
 * ============================================
 * 
 * Rutas protegidas para gestión del perfil:
 * Requieren autenticación
 * - Ver y editar perfil
 * - Actualizar información personal
 * - Eliminar cuenta
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * ============================================
     * MENSAJERÍA - Bandeja de Entrada (Usuario)
     * ============================================
     * 
     * Rutas para que los usuarios se comuniquen con administradores.
     * Sistema de mensajería usado principalmente para consultas sobre reparaciones.
     * 
     * Funcionalidades:
     * - Ver bandeja de entrada con todas las conversaciones
     * - Crear nuevo mensaje/conversación con un administrador
     * - Ver conversación específica con todos sus mensajes
     * - Enviar mensaje en una conversación existente
     * - Los mensajes se marcan automáticamente como leídos al abrir la conversación
     * 
     * Relacionado con: Sistema de reparaciones (los usuarios pueden contactar por reparaciones)
     */
    Route::get('/mensajes', [MessageController::class, 'index'])->name('messages.index'); // Ver bandeja de entrada
    Route::get('/mensajes/nuevo', [MessageController::class, 'create'])->name('messages.create'); // Formulario para crear nuevo mensaje
    Route::post('/mensajes', [MessageController::class, 'store'])->name('messages.store'); // Crear nueva conversación y primer mensaje
    Route::get('/mensajes/{conversation}', [MessageController::class, 'show'])->name('messages.show'); // Ver conversación específica
    Route::post('/mensajes/{conversation}/enviar', [MessageController::class, 'sendMessage'])->name('messages.send'); // Enviar mensaje en conversación existente
});

/**
 * ============================================
 * FAVORITOS
 * ============================================
 * 
 * Rutas protegidas para gestión de favoritos:
 * Requieren autenticación
 * - Ver lista de favoritos
 * - Agregar producto a favoritos
 * - Eliminar producto de favoritos
 * - Verificar si un producto está en favoritos (para AJAX)
 */
Route::middleware('auth')->group(function () {
    Route::get('/favoritos', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favoritos/{product}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favoritos/{product}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::get('/favoritos/verificar/{product}', [FavoriteController::class, 'check'])->name('favorites.check');
});

/**
 * ============================================
 * PÁGINAS ESTÁTICAS
 * ============================================
 * 
 * Rutas públicas para páginas informativas:
 * - Centro de ayuda
 * - Garantías
 * - Devoluciones
 * - Contacto
 * - Sobre nosotros
 * - Términos y condiciones
 * - Política de privacidad
 * - Blog
 * - Página de desarrollo (en construcción)
 */
Route::get('/centro-ayuda', [PageController::class, 'helpCenter'])->name('pages.help-center');
Route::get('/garantias', [PageController::class, 'warranties'])->name('pages.warranties');
Route::get('/devoluciones', [PageController::class, 'returns'])->name('pages.returns');
Route::get('/contacto', [PageController::class, 'contact'])->name('pages.contact');
Route::post('/contacto', [PageController::class, 'sendContact'])->name('pages.contact.send');
Route::get('/sobre-nosotros', [PageController::class, 'about'])->name('pages.about');
Route::get('/terminos', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/privacidad', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/blog', [PageController::class, 'blog'])->name('pages.blog');
Route::get('/en-desarrollo', [PageController::class, 'development'])->name('pages.development');

/**
 * ============================================
 * RUTAS DE AUTENTICACIÓN
 * ============================================
 * 
 * Incluye todas las rutas relacionadas con autenticación:
 * - Login
 * - Registro
 * - Recuperación de contraseña
 * - Verificación de email
 * - etc.
 * 
 * Definidas en routes/auth.php
 */
require __DIR__.'/auth.php';
