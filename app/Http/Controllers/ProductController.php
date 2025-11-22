<?php

/**
 * ProductController
 * 
 * Controlador para la gestión de productos en el área pública (no admin).
 * Maneja la visualización de productos para los usuarios finales:
 * - Listado de productos con filtros y búsqueda
 * - Detalle de producto individual
 * - Productos relacionados
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Mostrar listado de productos disponibles
     * 
     * Permite filtrar productos por:
     * - Categoría (slug)
     * - Búsqueda por texto (nombre o descripción)
     * - Rango de precios (menos de $100, $100-$500, $500-$1000, más de $1000)
     * - Ordenamiento (nombre, precio, rating, más recientes)
     * 
     * Solo muestra productos activos y en stock.
     * Muestra 12 productos por página.
     * 
     * @param Request $request Parámetros de filtrado y búsqueda
     * @return \Illuminate\View\View Vista con productos paginados y categorías
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where('in_stock', true)
            ->with('category');

        // Filtrar por categoría
        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtrar por búsqueda
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filtrar por precio
        if ($request->has('price_range') && $request->price_range) {
            switch ($request->price_range) {
                case 'under_100':
                    $query->where('price', '<', 100);
                    break;
                case '100_500':
                    $query->whereBetween('price', [100, 500]);
                    break;
                case '500_1000':
                    $query->whereBetween('price', [500, 1000]);
                    break;
                case 'over_1000':
                    $query->where('price', '>', 1000);
                    break;
            }
        }

        // Ordenar
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');
        
        switch ($sortBy) {
            case 'price':
                $query->orderBy('price', $sortOrder);
                break;
            case 'rating':
                $query->orderBy('rating', $sortOrder);
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', $sortOrder);
        }

        $products = $query->paginate(12)->appends($request->query());
        
        // Categorías específicas que se mostrarán (las mismas que en la navegación)
        $allowedCategorySlugs = ['accesorios', 'laptops', 'relojes', 'televisores', 'celulares', 'camaras'];
        
        $categories = Category::where('is_active', true)
            ->whereIn('slug', $allowedCategorySlugs)
            ->whereHas('products', function ($query) {
                $query->where('is_active', true)
                      ->where('in_stock', true);
            })
            ->get()
            ->sortBy(function ($category) use ($allowedCategorySlugs) {
                return array_search($category->slug, $allowedCategorySlugs);
            })
            ->values();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Mostrar detalle de un producto específico
     * 
     * Muestra toda la información del producto:
     * - Imágenes
     * - Descripción completa
     * - Precio y precio de oferta (si aplica)
     * - Stock disponible
     * - Categoría
     * - Rating y reseñas
     * 
     * También muestra productos relacionados de la misma categoría,
     * priorizando productos destacados y con mejor rating.
     * 
     * @param Product $product Modelo del producto (inyectado automáticamente por Laravel)
     * @return \Illuminate\View\View Vista con detalle del producto y productos relacionados
     */
    public function show(Product $product)
    {
        // Obtener productos relacionados de la misma categoría
        // Priorizar productos destacados y con mejor rating
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id) // Excluir el producto actual
            ->where('is_active', true) // Solo productos activos
            ->where('in_stock', true) // Solo productos en stock
            ->orderBy('is_featured', 'desc') // Productos destacados primero
            ->orderBy('rating', 'desc') // Mejor rating después
            ->orderBy('review_count', 'desc') // Más reseñas después
            ->limit(4) // Máximo 4 productos relacionados
            ->get();

        // Solo pasar productos relacionados si hay al menos 1
        // Si no hay productos de la misma categoría, no mostrar la sección
        if ($relatedProducts->count() == 0) {
            $relatedProducts = collect(); // Colección vacía para evitar errores en la vista
        }

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
