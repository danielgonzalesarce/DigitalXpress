<?php

namespace App\Providers;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cartCount = 0;
            if (Auth::check()) {
                $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
            } else {
                $cartCount = CartItem::where('session_id', session()->getId())
                    ->whereNull('user_id')
                    ->sum('quantity');
            }
            $view->with('cartCount', $cartCount);
        });
    }
}
