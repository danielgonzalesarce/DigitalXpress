<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirigir al usuario a la página de autenticación de Google.
     */
    public function redirect(): RedirectResponse
    {
        // Verificar que las credenciales estén configuradas
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirect = config('services.google.redirect');

        if (empty($clientId) || empty($clientSecret) || empty($redirect)) {
            return redirect()->route('login')
                ->with('error', 'La autenticación con Google no está configurada. Por favor, configura las credenciales en el archivo .env');
        }

        return Socialite::driver('google')
            ->setScopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    /**
     * Obtener la información del usuario de Google.
     */
    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Buscar si el usuario ya existe por email o google_id
            $user = User::where('email', $googleUser->getEmail())
                ->orWhere('google_id', $googleUser->getId())
                ->first();

            if ($user) {
                // Si el usuario existe pero no tiene google_id, actualizarlo
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    if ($googleUser->getAvatar() && !$user->avatar) {
                        $user->avatar = $googleUser->getAvatar();
                    }
                    $user->save();
                } else {
                    // Actualizar avatar si está disponible
                    if ($googleUser->getAvatar() && $user->avatar !== $googleUser->getAvatar()) {
                        $user->avatar = $googleUser->getAvatar();
                        $user->save();
                    }
                }
            } else {
                // Verificar el dominio del email
                $email = strtolower(trim($googleUser->getEmail()));
                $emailParts = explode('@', $email);
                $emailDomain = isset($emailParts[1]) ? strtolower(trim($emailParts[1])) : '';

                // No permitir registro con @digitalxpress.com
                if ($emailDomain === 'digitalxpress.com') {
                    return redirect()->route('home')
                        ->with('error', 'Los usuarios con dominio @digitalxpress.com solo pueden ser creados por el administrador desde el panel administrativo. Por favor, utiliza una cuenta de Google con dominio @gmail.com.');
                }

                // Solo permitir registro con @gmail.com
                if ($emailDomain !== 'gmail.com') {
                    return redirect()->route('home')
                        ->with('error', 'Solo se permiten registros con direcciones de correo @gmail.com.');
                }

                // Crear nuevo usuario
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(uniqid()), // Contraseña aleatoria (no se usará)
                    'email_verified_at' => now(), // Google ya verificó el email
                ]);
            }

            // Autenticar al usuario
            Auth::login($user, true);

            // Redirigir a la página de "en desarrollo"
            return redirect()->route('pages.development');

        } catch (\Exception $e) {
            // En caso de error, redirigir al login con mensaje de error
            return redirect()->route('login')
                ->with('error', 'No se pudo autenticar con Google. Por favor, intenta de nuevo.');
        }
    }
}

