<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Repair;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Compartir datos del admin con todas las vistas del layout admin
        View::composer('layouts.admin', function ($view) {
            // Notificaciones: pedidos pendientes, stock bajo, reparaciones pendientes
            $pendingOrders = Order::where('status', '!=', 'demo_simulation')
                ->whereIn('status', ['pending', 'processing'])
                ->count();
            
            $lowStockCount = Product::where('is_active', true)
                ->where('stock_quantity', '<', 10)
                ->where('stock_quantity', '>', 0)
                ->count();
            
            $outOfStockCount = Product::where('is_active', true)
                ->where(function($query) {
                    $query->where('stock_quantity', '<=', 0)
                          ->orWhere('in_stock', false);
                })
                ->count();
            
            $pendingRepairs = Repair::where('status', 'pending')
                ->count();
            
            // Total de notificaciones (suma de todas las alertas importantes)
            $totalNotifications = $pendingOrders + $lowStockCount + $outOfStockCount + $pendingRepairs;
            
            // Badge de inventario (stock bajo)
            $inventoryBadgeCount = $lowStockCount;
            
            $view->with([
                'adminNotifications' => $totalNotifications,
                'pendingOrders' => $pendingOrders,
                'lowStockCount' => $lowStockCount,
                'outOfStockCount' => $outOfStockCount,
                'pendingRepairs' => $pendingRepairs,
                'inventoryBadgeCount' => $inventoryBadgeCount,
            ]);
        });
    }
}

