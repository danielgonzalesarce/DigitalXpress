<?php

/**
 * AppServiceProvider
 * 
 * Proveedor de servicios principal de la aplicación.
 * Se ejecuta cuando la aplicación Laravel se inicia.
 * 
 * Responsabilidades:
 * - Configurar paginación de Laravel
 * - Compartir datos globales con las vistas usando View Composers
 * - Registrar servicios personalizados
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registrar servicios de la aplicación
     * 
     * Este método se ejecuta cuando el contenedor de servicios se registra.
     * Aquí se pueden registrar servicios, bindings, singletons, etc.
     * 
     * @return void
     */
    public function register(): void
    {
        // Por ahora no hay servicios personalizados para registrar
    }

    /**
     * Inicializar servicios de la aplicación
     * 
     * Este método se ejecuta después de que todos los servicios han sido registrados.
     * Aquí se configuran aspectos globales de la aplicación como:
     * - Paginación
     * - View Composers (compartir datos con vistas)
     * - Event listeners
     * - etc.
     * 
     * @return void
     */
    public function boot(): void
    {
        /**
         * Configurar paginación para usar Bootstrap 5
         * Esto hace que los links de paginación usen las clases de Bootstrap 5
         */
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        /**
         * View Composer: Compartir categorías con productos en el layout principal
         * 
         * Este composer se ejecuta cada vez que se renderiza la vista 'layouts.app'.
         * Comparte las categorías activas que tienen productos disponibles
         * para mostrarlas en la navegación de categorías.
         * 
         * Solo incluye categorías específicas en un orden determinado.
         */
        View::composer('layouts.app', function ($view) {
            // Categorías específicas que se mostrarán en la navegación (en el orden deseado)
            $allowedCategorySlugs = ['accesorios', 'laptops', 'relojes', 'televisores', 'celulares', 'camaras'];
            
            // Obtener solo las categorías permitidas que tienen productos activos y en stock
            $categories = Category::where('is_active', true)
                ->whereIn('slug', $allowedCategorySlugs) // Solo categorías permitidas
                ->whereHas('products', function ($query) {
                    // Solo categorías que tienen al menos un producto activo y en stock
                    $query->where('is_active', true)
                          ->where('in_stock', true);
                })
                ->get();

            // Ordenar según el orden especificado en $allowedCategorySlugs
            $categoriesWithProducts = $categories->sortBy(function ($category) use ($allowedCategorySlugs) {
                // Retornar el índice de la categoría en el array para mantener el orden
                return array_search($category->slug, $allowedCategorySlugs);
            })->values(); // Reindexar el array para mantener índices numéricos consecutivos

            // Compartir las categorías con la vista
            $view->with('categoriesWithProducts', $categoriesWithProducts);
        });
    }
}
