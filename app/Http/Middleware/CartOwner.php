<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cartItem = $request->route('cartItem');
        
        if ($cartItem && $cartItem->user_id !== auth()->id()) {
            abort(403, 'No tienes permisos para realizar esta acción');
        }
        
        return $next($request);
    }
}
