<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        // Usar Bootstrap 5 para la paginación
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        // Compartir categorías con productos en todas las vistas
        View::composer('layouts.app', function ($view) {
            // Categorías específicas que se mostrarán en la navegación (en el orden deseado)
            $allowedCategorySlugs = ['accesorios', 'laptops', 'relojes', 'televisores', 'celulares', 'camaras'];
            
            // Obtener solo las categorías permitidas que tienen productos activos y en stock
            $categories = Category::where('is_active', true)
                ->whereIn('slug', $allowedCategorySlugs)
                ->whereHas('products', function ($query) {
                    $query->where('is_active', true)
                          ->where('in_stock', true);
                })
                ->get();

            // Ordenar según el orden especificado
            $categoriesWithProducts = $categories->sortBy(function ($category) use ($allowedCategorySlugs) {
                return array_search($category->slug, $allowedCategorySlugs);
            })->values();

            $view->with('categoriesWithProducts', $categoriesWithProducts);
        });
    }
}
