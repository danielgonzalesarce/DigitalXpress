<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepairController;
use Illuminate\Support\Facades\Route;

// Página principal
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

// Perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de autenticación
require __DIR__.'/auth.php';
