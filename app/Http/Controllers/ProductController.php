<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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
        $categories = Category::where('is_active', true)->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        // Obtener productos relacionados de la misma categoría
        // Priorizar productos destacados y con mejor rating
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('in_stock', true)
            ->orderBy('is_featured', 'desc') // Productos destacados primero
            ->orderBy('rating', 'desc') // Mejor rating después
            ->orderBy('review_count', 'desc') // Más reseñas después
            ->limit(4)
            ->get();

        // Solo pasar productos relacionados si hay al menos 1
        // Si no hay productos de la misma categoría, no mostrar la sección
        if ($relatedProducts->count() == 0) {
            $relatedProducts = collect(); // Colección vacía
        }

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
