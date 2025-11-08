<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
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
        $emailParts = explode('@', $user->email);
        if (count($emailParts) !== 2) {
            return redirect()->route('home')
                ->with('error', 'Email inválido. No tienes permisos para acceder al panel de administración.');
        }
        
        $emailDomain = strtolower(trim($emailParts[1]));
        
        // Solo permitir acceso si el dominio es exactamente 'digitalxpress.com'
        if ($emailDomain !== 'digitalxpress.com') {
            // Si no es admin, redirigir al home con un mensaje
            return redirect()->route('home')
                ->with('error', 'No tienes permisos para acceder al panel de administración. Solo usuarios con email @digitalxpress.com pueden acceder.');
        }

        return $next($request);
    }
}

