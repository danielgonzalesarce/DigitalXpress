<?php

/**
 * CartServiceProvider
 * 
 * Proveedor de servicios para compartir datos del carrito y favoritos
 * con todas las vistas de la aplicación.
 * 
 * Responsabilidades:
 * - Calcular y compartir el conteo de items en el carrito
 * - Calcular y compartir el conteo de productos favoritos
 * - Funciona tanto para usuarios autenticados como invitados
 * 
 * Los datos se comparten automáticamente en todas las vistas,
 * permitiendo mostrar contadores en el navbar sin necesidad de
 * pasarlos manualmente desde cada controlador.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Providers;

use App\Models\CartItem;
use App\Models\Favorite;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Registrar servicios
     * 
     * @return void
     */
    public function register(): void
    {
        // Por ahora no hay servicios para registrar
    }

    /**
     * Inicializar servicios
     * 
     * View Composer que se ejecuta en TODAS las vistas (*).
     * Comparte automáticamente:
     * - cartCount: Total de items en el carrito
     * - favoritesCount: Total de productos favoritos
     * 
     * @return void
     */
    public function boot(): void
    {
        /**
         * View Composer global: Compartir datos del carrito y favoritos
         * 
         * Se ejecuta en todas las vistas ('*') para asegurar que
         * los contadores del navbar siempre estén disponibles.
         */
        View::composer('*', function ($view) {
            // Calcular cantidad de items en el carrito
            $cartCount = 0;
            if (Auth::check()) {
                // Usuario autenticado: contar items por user_id
                $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
            } else {
                // Usuario invitado: contar items por session_id
                $cartCount = CartItem::where('session_id', session()->getId())
                    ->whereNull('user_id') // Asegurar que no pertenezcan a otro usuario
                    ->sum('quantity');
            }
            
            // Calcular cantidad de productos favoritos (solo usuarios autenticados)
            $favoritesCount = 0;
            if (Auth::check()) {
                $favoritesCount = Favorite::where('user_id', Auth::id())->count();
            }
            
            // Calcular cantidad de mensajes no leídos (solo usuarios autenticados)
            $unreadMessagesCount = 0;
            if (Auth::check()) {
                $unreadMessagesCount = Message::where('receiver_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
            }
            
            // Compartir los contadores con la vista
            $view->with('cartCount', $cartCount);
            $view->with('favoritesCount', $favoritesCount);
            $view->with('unreadMessagesCount', $unreadMessagesCount);
        });
    }
}
