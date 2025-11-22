<?php

/**
 * Middleware IsAdmin
 * 
 * Middleware de autenticación y autorización para el panel de administración.
 * 
 * Funcionalidad:
 * - Verifica que el usuario esté autenticado
 * - Verifica que el email del usuario termine en @digitalxpress.com
 * - Solo permite acceso al panel de administración a usuarios autorizados
 * 
 * Uso:
 * Se aplica automáticamente a todas las rutas del grupo 'admin' en routes/web.php
 * usando: Route::middleware(['auth', 'admin'])
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Manejar una petición entrante
     * 
     * Intercepta las peticiones a rutas protegidas y verifica:
     * 1. Que el usuario esté autenticado
     * 2. Que el dominio del email sea exactamente 'digitalxpress.com'
     * 
     * Si alguna verificación falla, redirige con un mensaje de error.
     * Si todo está bien, permite que la petición continúe.
     * 
     * @param Request $request Petición HTTP entrante
     * @param Closure $next Función que continúa con la petición
     * @return Response Respuesta HTTP (redirección o continuación)
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para acceder a esta sección.');
        }

        // Verificar que el email del usuario termine en @digitalxpress.com
        $user = auth()->user();
        
        // Extraer el dominio del email de forma más segura
        // Dividir el email en partes usando '@' como separador
        $emailParts = explode('@', $user->email);
        
        // Validar que el email tenga exactamente 2 partes (usuario@dominio)
        if (count($emailParts) !== 2) {
            return redirect()->route('home')
                ->with('error', 'Email inválido. No tienes permisos para acceder al panel de administración.');
        }
        
        // Obtener el dominio y normalizarlo (minúsculas, sin espacios)
        $emailDomain = strtolower(trim($emailParts[1]));
        
        // Solo permitir acceso si el dominio es exactamente 'digitalxpress.com'
        if ($emailDomain !== 'digitalxpress.com') {
            // Si no es admin, redirigir al home con un mensaje
            return redirect()->route('home')
                ->with('error', 'No tienes permisos para acceder al panel de administración. Solo usuarios con email @digitalxpress.com pueden acceder.');
        }

        // Si pasa todas las verificaciones, permitir que la petición continúe
        return $next($request);
    }
}

