<?php

/**
 * FavoriteController
 * 
 * Controlador para la gestión de favoritos de productos.
 * Permite a los usuarios autenticados:
 * - Ver su lista de favoritos
 * - Agregar productos a favoritos
 * - Eliminar productos de favoritos
 * - Verificar si un producto está en favoritos (para AJAX)
 * 
 * Todas las operaciones requieren autenticación.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Mostrar todos los favoritos del usuario autenticado
     * 
     * Obtiene todos los productos marcados como favoritos por el usuario,
     * incluyendo la relación con la categoría del producto.
     * Muestra 12 favoritos por página.
     * 
     * @return \Illuminate\View\View Vista con lista de favoritos paginada
     * @return \Illuminate\Http\RedirectResponse Redirige al login si no está autenticado
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para ver tus favoritos');
        }

        $favorites = Favorite::where('user_id', Auth::id())
            ->with('product.category')
            ->latest()
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    /**
     * Agregar un producto a favoritos
     * 
     * Verifica que el usuario esté autenticado y que el producto
     * no esté ya en sus favoritos antes de agregarlo.
     * 
     * Soporta tanto peticiones AJAX como peticiones normales (formularios).
     * 
     * @param Request $request Petición HTTP (puede ser AJAX)
     * @param Product $product Modelo del producto a agregar
     * @return \Illuminate\Http\JsonResponse Respuesta JSON si es AJAX
     * @return \Illuminate\Http\RedirectResponse Redirige con mensaje si es petición normal
     */
    public function store(Request $request, Product $product)
    {
        // Verificar autenticación
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes iniciar sesión para agregar favoritos'
                ], 401); // Código 401: No autorizado
            }
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para agregar favoritos');
        }

        // Verificar si el producto ya está en favoritos del usuario
        $existingFavorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        // Si ya existe, retornar mensaje informativo
        if ($existingFavorite) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este producto ya está en tus favoritos'
                ]);
            }
            return redirect()->back()->with('info', 'Este producto ya está en tus favoritos');
        }

        // Crear nuevo favorito
        Favorite::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        // Retornar respuesta según el tipo de petición
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto agregado a favoritos'
            ]);
        }

        return redirect()->back()->with('success', 'Producto agregado a favoritos');
    }

    /**
     * Eliminar un producto de favoritos
     */
    public function destroy(Product $product)
    {
        if (!Auth::check()) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debes iniciar sesión para eliminar favoritos'
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para eliminar favoritos');
        }

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            $favorite->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Producto eliminado de favoritos'
                ]);
            }

            return redirect()->back()->with('success', 'Producto eliminado de favoritos');
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'El producto no está en tus favoritos'
            ]);
        }

        return redirect()->back()->with('error', 'El producto no está en tus favoritos');
    }

    /**
     * Verificar si un producto está en favoritos (para AJAX)
     */
    public function check(Product $product)
    {
        if (!Auth::check()) {
            return response()->json(['is_favorite' => false]);
        }

        $isFavorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();

        return response()->json(['is_favorite' => $isFavorite]);
    }
}
