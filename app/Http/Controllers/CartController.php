<?php

/**
 * CartController
 * 
 * Controlador para la gestión del carrito de compras.
 * Maneja tanto usuarios autenticados como invitados (usando session_id).
 * 
 * Funcionalidades:
 * - Ver carrito
 * - Agregar productos al carrito
 * - Actualizar cantidad de productos
 * - Eliminar productos del carrito
 * - Vaciar carrito completo
 * - Limpiar productos no disponibles
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Mostrar el contenido del carrito de compras
     * 
     * Obtiene todos los items del carrito según el tipo de usuario:
     * - Usuario autenticado: busca por user_id
     * - Usuario invitado: busca por session_id
     * 
     * Filtra automáticamente productos que ya no están disponibles
     * y calcula el total del carrito.
     * 
     * @return \Illuminate\View\View Vista con items del carrito y total
     */
    public function index()
    {
        if (Auth::check()) {
            // Usuario autenticado
            $cartItems = CartItem::where('user_id', Auth::id())
                ->with('product')
                ->get();
        } else {
            // Usuario invitado - usar session_id
            $sessionId = session()->getId();
            $cartItems = CartItem::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->with('product')
                ->get();
        }

        // Verificar que los productos sigan disponibles
        $cartItems = $cartItems->filter(function ($item) {
            return $item->product && $item->product->is_active && $item->product->in_stock;
        });

        $total = $cartItems->sum('total');

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Agregar un producto al carrito de compras
     * 
     * Verifica que el producto esté disponible y en stock.
     * Si el producto ya está en el carrito, suma la cantidad solicitada.
     * Si no está, crea un nuevo item en el carrito.
     * 
     * Maneja tanto usuarios autenticados como invitados.
     * 
     * @param Request $request Contiene la cantidad a agregar
     * @param Product $product Modelo del producto a agregar
     * @return \Illuminate\Http\RedirectResponse Redirige con mensaje de éxito/error
     */
    public function add(Request $request, Product $product)
    {
        // Verificar que el producto esté activo y en stock
        if (!$product->is_active || !$product->in_stock) {
            return redirect()->back()->with('error', 'Este producto no está disponible');
        }

        // Validar cantidad: debe ser entero, mínimo 1, máximo el stock disponible
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock_quantity
        ]);

        // Buscar si el producto ya está en el carrito
        if (Auth::check()) {
            // Usuario autenticado: buscar por user_id
            $existingItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();
        } else {
            // Usuario invitado: buscar por session_id
            $sessionId = session()->getId();
            $existingItem = CartItem::where('session_id', $sessionId)
                ->whereNull('user_id') // Asegurar que no pertenezca a otro usuario
                ->where('product_id', $product->id)
                ->first();
        }

        if ($existingItem) {
            // Si ya existe, sumar la cantidad nueva a la existente
            $newQuantity = $existingItem->quantity + $request->quantity;
            
            // Verificar que la cantidad total no exceda el stock disponible
            if ($newQuantity > $product->stock_quantity) {
                return redirect()->back()->with('error', 'No hay suficiente stock disponible');
            }
            
            // Actualizar cantidad y precio (por si cambió)
            $existingItem->quantity = $newQuantity;
            $existingItem->price = $product->current_price; // Actualizar precio por si cambió
            $existingItem->save();
        } else {
            // Si no existe, crear nuevo item en el carrito
            $cartData = [
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->current_price, // Guardar precio actual del producto
            ];

            // Asignar user_id o session_id según el tipo de usuario
            if (Auth::check()) {
                $cartData['user_id'] = Auth::id();
            } else {
                $cartData['session_id'] = session()->getId();
            }

            CartItem::create($cartData);
        }

        return redirect()->back()->with('success', 'Producto agregado al carrito');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        // Verificar que el usuario tenga permisos para modificar este item
        if (Auth::check()) {
            if ($cartItem->user_id !== Auth::id()) {
                abort(403, 'No tienes permisos para realizar esta acción');
            }
        } else {
            if ($cartItem->session_id !== session()->getId() || $cartItem->user_id !== null) {
                abort(403, 'No tienes permisos para realizar esta acción');
            }
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock_quantity
        ]);

        $cartItem->update([
            'quantity' => $request->quantity,
            'price' => $cartItem->product->current_price // Actualizar precio
        ]);

        return redirect()->back()->with('success', 'Carrito actualizado');
    }

    public function remove(CartItem $cartItem)
    {
        // Verificar que el usuario tenga permisos para eliminar este item
        if (Auth::check()) {
            if ($cartItem->user_id !== Auth::id()) {
                abort(403, 'No tienes permisos para realizar esta acción');
            }
        } else {
            if ($cartItem->session_id !== session()->getId() || $cartItem->user_id !== null) {
                abort(403, 'No tienes permisos para realizar esta acción');
            }
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Producto eliminado del carrito');
    }

    public function clear()
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            CartItem::where('session_id', session()->getId())
                ->whereNull('user_id')
                ->delete();
        }

        return redirect()->back()->with('success', 'Carrito vaciado');
    }

    public static function getCartCount()
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())->sum('quantity');
        } else {
            return CartItem::where('session_id', session()->getId())
                ->whereNull('user_id')
                ->sum('quantity');
        }
    }

    public function cleanup()
    {
        // Eliminar productos que ya no están disponibles
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                ->whereHas('product', function ($query) {
                    $query->where('is_active', false)
                          ->orWhere('in_stock', false);
                })
                ->delete();
        } else {
            CartItem::where('session_id', session()->getId())
                ->whereNull('user_id')
                ->whereHas('product', function ($query) {
                    $query->where('is_active', false)
                          ->orWhere('in_stock', false);
                })
                ->delete();
        }

        return redirect()->back()->with('info', 'Se eliminaron productos no disponibles del carrito');
    }
}
