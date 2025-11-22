<?php

/**
 * Middleware CartOwner
 * 
 * Middleware de autorización para verificar que un usuario solo pueda
 * modificar items del carrito que le pertenecen.
 * 
 * Funcionalidad:
 * - Verifica que el item del carrito pertenezca al usuario autenticado
 * - Previene que usuarios modifiquen o eliminen items de otros usuarios
 * 
 * Uso:
 * Se aplica a rutas que reciben un parámetro 'cartItem' en la URL.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartOwner
{
    /**
     * Manejar una petición entrante
     * 
     * Verifica que el item del carrito (obtenido del parámetro de ruta)
     * pertenezca al usuario autenticado antes de permitir la acción.
     * 
     * @param Request $request Petición HTTP entrante
     * @param Closure $next Función que continúa con la petición
     * @return Response Respuesta HTTP (403 si no tiene permisos, o continuación)
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Obtener el item del carrito desde el parámetro de la ruta
        $cartItem = $request->route('cartItem');
        
        // Verificar que el item existe y pertenece al usuario autenticado
        if ($cartItem && $cartItem->user_id !== auth()->id()) {
            // Si no pertenece al usuario, denegar acceso con error 403
            abort(403, 'No tienes permisos para realizar esta acción');
        }
        
        // Si pasa la verificación, permitir que la petición continúe
        return $next($request);
    }
}
