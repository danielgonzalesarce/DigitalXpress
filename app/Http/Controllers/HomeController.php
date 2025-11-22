<?php

/**
 * HomeController
 * 
 * Controlador para la página principal (home) de DigitalXpress.
 * Maneja la visualización del contenido principal de la tienda:
 * - Carrusel de productos destacados
 * - Productos destacados
 * - Últimos productos agregados
 * - Categorías activas
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Mostrar la página principal de la tienda
     * 
     * Obtiene y prepara los datos para mostrar en el home:
     * 1. Categorías activas ordenadas por sort_order
     * 2. Productos para el carrusel (destacados primero, luego últimos si no hay suficientes)
     * 3. Productos destacados (máximo 8)
     * 4. Últimos productos agregados (máximo 8)
     * 
     * Solo muestra productos activos y en stock.
     * 
     * @return \Illuminate\View\View Vista home con todos los datos
     */
    public function index()
    {
        // Obtener categorías activas ordenadas por orden de visualización
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Productos para el carrusel: priorizar productos destacados
        $carouselProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->where('in_stock', true)
            ->with('category') // Cargar relación de categoría para evitar N+1 queries
            ->orderBy('created_at', 'desc')
            ->limit(5) // Máximo 5 productos en el carrusel
            ->get();

        // Si no hay suficientes productos destacados (menos de 3), completar con últimos productos
        if ($carouselProducts->count() < 3) {
            $additionalProducts = Product::where('is_active', true)
                ->where('in_stock', true)
                ->whereNotIn('id', $carouselProducts->pluck('id')) // Excluir los que ya están en el carrusel
                ->with('category')
                ->orderBy('created_at', 'desc')
                ->limit(5 - $carouselProducts->count()) // Completar hasta 5 productos
                ->get();
            
            // Combinar productos destacados con últimos productos
            $carouselProducts = $carouselProducts->merge($additionalProducts);
        }

        // Productos destacados para la sección "Productos Destacados"
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->where('in_stock', true)
            ->with('category')
            ->limit(8) // Mostrar máximo 8 productos destacados
            ->get();

        // Últimos productos agregados para la sección "Últimas Novedades"
        $latestProducts = Product::where('is_active', true)
            ->where('in_stock', true)
            ->with('category')
            ->orderBy('created_at', 'desc') // Más recientes primero
            ->limit(8) // Mostrar máximo 8 productos
            ->get();

        return view('home', compact('categories', 'carouselProducts', 'featuredProducts', 'latestProducts'));
    }
}
