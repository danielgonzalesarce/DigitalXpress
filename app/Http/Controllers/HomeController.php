<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Productos para el carrusel (productos destacados o últimos productos)
        $carouselProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->where('in_stock', true)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Si no hay suficientes productos destacados, agregar los últimos productos
        if ($carouselProducts->count() < 3) {
            $additionalProducts = Product::where('is_active', true)
                ->where('in_stock', true)
                ->whereNotIn('id', $carouselProducts->pluck('id'))
                ->with('category')
                ->orderBy('created_at', 'desc')
                ->limit(5 - $carouselProducts->count())
                ->get();
            
            $carouselProducts = $carouselProducts->merge($additionalProducts);
        }

        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->where('in_stock', true)
            ->with('category')
            ->limit(8)
            ->get();

        $latestProducts = Product::where('is_active', true)
            ->where('in_stock', true)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('home', compact('categories', 'carouselProducts', 'featuredProducts', 'latestProducts'));
    }
}
