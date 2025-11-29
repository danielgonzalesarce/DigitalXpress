<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    /**
     * ============================================
     * AUTENTICACIÓN CON GOOGLE OAuth 2.0
     * ============================================
     * 
     * Rutas para autenticación OAuth con Google usando Laravel Socialite.
     * Permite a los usuarios iniciar sesión y crear cuenta usando su cuenta de Google.
     * 
     * Flujo:
     * 1. Usuario hace clic en "Continuar con Google"
     * 2. Redirige a Google para autorización (google.auth)
     * 3. Google redirige de vuelta con código de autorización (google.callback)
     * 4. Se crea/actualiza usuario y se autentica en la sesión
     * 
     * Restricciones:
     * - Solo se permiten registros con @gmail.com
     * - Los usuarios @digitalxpress.com no pueden crearse desde Google
     * 
     * Requisitos:
     * - Credenciales configuradas en .env:
     *   * GOOGLE_CLIENT_ID: ID del cliente OAuth 2.0
     *   * GOOGLE_CLIENT_SECRET: Secreto del cliente OAuth 2.0
     *   * GOOGLE_REDIRECT_URI: URI de callback (ej: http://127.0.0.1:8081/auth/google/callback)
     * - Laravel Socialite instalado y configurado en config/services.php
     */
    Route::get('auth/google', [GoogleAuthController::class, 'redirect'])
        ->name('google.auth'); // Ruta: /auth/google - Inicia el flujo OAuth, redirige a Google
    
    Route::get('auth/google/callback', [GoogleAuthController::class, 'callback'])
        ->name('google.callback'); // Ruta: /auth/google/callback - Maneja respuesta de Google, crea/actualiza usuario y autentica

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
