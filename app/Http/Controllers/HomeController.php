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

        return view('home', compact('categories', 'featuredProducts', 'latestProducts'));
    }
}
