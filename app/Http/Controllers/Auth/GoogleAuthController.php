<?php

/**
 * GoogleAuthController
 * 
 * Controlador para manejar la autenticación OAuth con Google.
 * Permite a los usuarios iniciar sesión y crear cuenta usando su cuenta de Google.
 * 
 * Funcionalidades:
 * - Redirigir al usuario a Google para autenticación
 * - Manejar el callback de Google después de la autenticación
 * - Crear o actualizar usuarios basado en datos de Google
 * - Aplicar restricciones de dominio (@gmail.com para registro, @digitalxpress.com para admin)
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirigir al usuario a la página de autenticación de Google
     * 
     * Este método inicia el flujo OAuth redirigiendo al usuario a Google
     * para que autorice la aplicación y permita acceder a su información.
     * 
     * @return RedirectResponse Redirección a Google OAuth o al login con error
     */
    /**
     * Redirigir al usuario a la página de autenticación de Google
     * 
     * Este método inicia el flujo OAuth redirigiendo al usuario a Google
     * para que autorice la aplicación y permita acceder a su información.
     * 
     * @return RedirectResponse Redirección a Google OAuth o al login con error
     */
    public function redirect(): RedirectResponse
    {
        // Obtener credenciales de Google desde config/services.php
        $clientId = config('services.google.client_id');           // ID del cliente OAuth
        $clientSecret = config('services.google.client_secret');   // Secreto del cliente OAuth
        $redirect = config('services.google.redirect');            // URI de callback

        // Verificar que todas las credenciales estén configuradas en .env
        if (empty($clientId) || empty($clientSecret) || empty($redirect)) {
            return redirect()->route('login')
                ->with('error', 'La autenticación con Google no está configurada. Por favor, configura las credenciales en el archivo .env');
        }

        // Redirigir a Google OAuth con los permisos (scopes) necesarios
        return Socialite::driver('google')
            ->setScopes(['openid', 'profile', 'email']) // Solicitar acceso a: identidad abierta, perfil y email
            ->redirect(); // Redirige a Google para autenticación
    }

    /**
     * Manejar el callback de Google después de la autenticación
     * 
     * Este método se ejecuta cuando Google redirige al usuario de vuelta
     * después de autorizar la aplicación. Obtiene los datos del usuario,
     * crea o actualiza el registro en la base de datos y autentica al usuario.
     * 
     * Flujo:
     * 1. Obtener datos del usuario desde Google
     * 2. Buscar si el usuario ya existe (por email o google_id)
     * 3. Si existe: actualizar google_id y avatar si es necesario
     * 4. Si no existe: crear nuevo usuario (solo @gmail.com)
     * 5. Autenticar al usuario
     * 6. Redirigir según el rol (admin o cliente)
     * 
     * @return RedirectResponse Redirección al dashboard o home según el rol
     */
    public function callback(): RedirectResponse
    {
        try {
            // Paso 1: Obtener datos del usuario desde Google OAuth
            $googleUser = Socialite::driver('google')->user();

            // Paso 2: Buscar si el usuario ya existe en nuestra base de datos
            // Buscar por email o por google_id (por si cambió el email)
            $user = User::where('email', $googleUser->getEmail())
                ->orWhere('google_id', $googleUser->getId())
                ->first();

            if ($user) {
                // CASO 1: Usuario existe - Actualizar datos de Google si es necesario
                if (!$user->google_id) {
                    // Si el usuario se registró con email/password y ahora usa Google
                    $user->google_id = $googleUser->getId(); // Agregar google_id
                    // Agregar avatar de Google si no tenía uno
                    if ($googleUser->getAvatar() && !$user->avatar) {
                        $user->avatar = $googleUser->getAvatar();
                    }
                    $user->save();
                } else {
                    // Si ya tenía google_id, solo actualizar avatar si cambió en Google
                    if ($googleUser->getAvatar() && $user->avatar !== $googleUser->getAvatar()) {
                        $user->avatar = $googleUser->getAvatar();
                        $user->save();
                    }
                }
            } else {
                // CASO 2: Usuario no existe - Crear nuevo usuario
                // Extraer dominio del email para validar restricciones
                $email = strtolower(trim($googleUser->getEmail()));
                $emailParts = explode('@', $email);
                $emailDomain = isset($emailParts[1]) ? strtolower(trim($emailParts[1])) : '';

                // Restricción 1: No permitir crear usuarios admin desde Google
                // Los administradores solo se crean desde el panel admin
                if ($emailDomain === 'digitalxpress.com') {
                    return redirect()->route('home')
                        ->with('error', 'Los usuarios con dominio @digitalxpress.com solo pueden ser creados por el administrador desde el panel administrativo. Por favor, utiliza una cuenta de Google con dominio @gmail.com.');
                }

                // Restricción 2: Solo permitir registro con @gmail.com
                // Por seguridad, solo aceptamos cuentas Gmail para registro público
                if ($emailDomain !== 'gmail.com') {
                    return redirect()->route('home')
                        ->with('error', 'Solo se permiten registros con direcciones de correo @gmail.com.');
                }

                // Crear nuevo usuario con datos obtenidos de Google
                $user = User::create([
                    'name' => $googleUser->getName(),              // Nombre completo de Google
                    'email' => $googleUser->getEmail(),           // Email de Google
                    'google_id' => $googleUser->getId(),          // ID único de Google (para futuras autenticaciones)
                    'avatar' => $googleUser->getAvatar(),         // URL del avatar de Google
                    'password' => bcrypt(uniqid()),               // Contraseña aleatoria (requerida por Laravel, no se usará)
                    'email_verified_at' => now(),                 // Email ya verificado por Google (confianza)
                ]);
            }

            // Paso 3: Autenticar al usuario en la sesión de Laravel
            // El segundo parámetro 'true' significa "recordar sesión" (remember me)
            Auth::login($user, true);

            // Paso 4: Determinar redirección según el rol del usuario
            $emailParts = explode('@', $user->email);
            $emailDomain = isset($emailParts[1]) ? strtolower(trim($emailParts[1])) : '';
            
            // Si es administrador (@digitalxpress.com), redirigir al panel admin
            if ($emailDomain === 'digitalxpress.com') {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            }

            // Si es cliente normal, redirigir al home de la tienda
            return redirect()->intended(route('home', absolute: false));

        } catch (\Exception $e) {
            // Manejar errores de autenticación (token inválido, usuario canceló, etc.)
            return redirect()->route('login')
                ->with('error', 'No se pudo autenticar con Google. Por favor, intenta de nuevo.');
        }
    }
}

