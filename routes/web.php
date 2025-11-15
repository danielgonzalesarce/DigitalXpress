<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepairController;
use Illuminate\Support\Facades\Route;

// Página principal - Accesible sin autenticación (sin login/registro)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Productos
Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/productos/{product}', [ProductController::class, 'show'])->name('products.show');

// Reparaciones
Route::get('/reparaciones', [RepairController::class, 'index'])->name('repairs.index');
Route::get('/reparaciones/dashboard', [RepairController::class, 'dashboard'])->name('repairs.dashboard');
Route::get('/reparaciones/nueva', [RepairController::class, 'create'])->name('repairs.create');
Route::post('/reparaciones', [RepairController::class, 'store'])->name('repairs.store');
Route::get('/reparaciones/{repair}', [RepairController::class, 'show'])->name('repairs.show');
Route::get('/reparaciones/agendar/cita', [RepairController::class, 'schedule'])->name('repairs.schedule');
Route::get('/reparaciones/contactar/soporte', [RepairController::class, 'contact'])->name('repairs.contact');
Route::get('/reparaciones/descargar/reporte', [RepairController::class, 'downloadReport'])->name('repairs.download-report');

// Carrito (funciona para usuarios autenticados e invitados)
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/carrito/actualizar/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/eliminar/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/carrito/vaciar', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/carrito/limpiar', [CartController::class, 'cleanup'])->name('cart.cleanup');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

// Panel de Administración (solo usuarios con @digitalxpress.com)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Productos
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
    
    // Inventario
    Route::get('/inventory', [AdminController::class, 'inventoryIndex'])->name('inventory');
    Route::get('/inventory/create', [AdminController::class, 'createInventoryMovement'])->name('inventory.create');
    Route::post('/inventory', [AdminController::class, 'storeInventoryMovement'])->name('inventory.store');
    Route::get('/inventory/{product}/edit', [AdminController::class, 'editInventoryMovement'])->name('inventory.edit');
    Route::put('/inventory/{product}', [AdminController::class, 'updateInventoryMovement'])->name('inventory.update');
    Route::delete('/inventory/{product}', [AdminController::class, 'destroyInventoryMovement'])->name('inventory.destroy');
    
    // Pedidos
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/create', [AdminController::class, 'createOrder'])->name('orders.create');
    Route::post('/orders', [AdminController::class, 'storeOrder'])->name('orders.store');
    Route::get('/orders/{order}', [AdminController::class, 'orderDetails'])->name('order.details');
    Route::get('/orders/{order}/edit', [AdminController::class, 'editOrder'])->name('orders.edit');
    Route::put('/orders/{order}', [AdminController::class, 'updateOrder'])->name('orders.update');
    Route::patch('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('order.update-status');
    Route::delete('/orders/{order}', [AdminController::class, 'destroyOrder'])->name('orders.destroy');
    
    // Reparaciones
    Route::get('/repairs', [AdminController::class, 'repairs'])->name('repairs');
    Route::get('/repairs/create', [AdminController::class, 'createRepair'])->name('repairs.create');
    Route::post('/repairs', [AdminController::class, 'storeRepair'])->name('repairs.store');
    Route::get('/repairs/{repair}/edit', [AdminController::class, 'editRepair'])->name('repairs.edit');
    Route::put('/repairs/{repair}', [AdminController::class, 'updateRepair'])->name('repairs.update');
    Route::delete('/repairs/{repair}', [AdminController::class, 'destroyRepair'])->name('repairs.destroy');
    
    // Análisis
    Route::get('/revenue', [AdminController::class, 'revenue'])->name('revenue');
    
    // Usuarios
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    
    // Categorías
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('/categories/create', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [AdminController::class, 'destroyCategory'])->name('categories.destroy');
    
    // Configuración
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
});

// Perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Páginas estáticas
Route::get('/centro-ayuda', [PageController::class, 'helpCenter'])->name('pages.help-center');
Route::get('/garantias', [PageController::class, 'warranties'])->name('pages.warranties');
Route::get('/devoluciones', [PageController::class, 'returns'])->name('pages.returns');
Route::get('/contacto', [PageController::class, 'contact'])->name('pages.contact');
Route::get('/sobre-nosotros', [PageController::class, 'about'])->name('pages.about');
Route::get('/terminos', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/privacidad', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/blog', [PageController::class, 'blog'])->name('pages.blog');
Route::get('/en-desarrollo', [PageController::class, 'development'])->name('pages.development');

// Rutas de autenticación
require __DIR__.'/auth.php';
