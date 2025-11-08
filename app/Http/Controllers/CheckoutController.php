<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        // Obtener items del carrito
        if (Auth::check()) {
            $cartItems = CartItem::where('user_id', Auth::id())
                ->with('product')
                ->get();
        } else {
            $sessionId = session()->getId();
            $cartItems = CartItem::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->with('product')
                ->get();
        }

        // Verificar que hay items en el carrito
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }

        // Verificar que todos los productos estén disponibles
        $unavailableItems = $cartItems->filter(function ($item) {
            return !$item->product || !$item->product->is_active || !$item->product->in_stock;
        });

        if ($unavailableItems->isNotEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Algunos productos en tu carrito ya no están disponibles');
        }

        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });

        return view('checkout.index', compact('cartItems', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|in:credit_card,debit_card,paypal,yape',
            // Validaciones para tarjeta de crédito
            'card_number' => 'required_if:payment_method,credit_card,debit_card|nullable|string',
            'card_expiry_month' => 'required_if:payment_method,credit_card,debit_card|nullable|integer|min:1|max:12',
            'card_expiry_year' => 'required_if:payment_method,credit_card,debit_card|nullable|integer|min:' . date('Y'),
            'card_cvv' => 'required_if:payment_method,credit_card,debit_card|nullable|string|size:3',
            'cardholder_name' => 'required_if:payment_method,credit_card,debit_card|nullable|string|max:255',
            // Validaciones para Yape
            'yape_phone' => 'required_if:payment_method,yape|nullable|string|regex:/^9\d{8}$/',
        ]);

        // Obtener items del carrito
        if (Auth::check()) {
            $cartItems = CartItem::where('user_id', Auth::id())
                ->with('product')
                ->get();
        } else {
            $sessionId = session()->getId();
            $cartItems = CartItem::where('session_id', $sessionId)
                ->whereNull('user_id')
                ->with('product')
                ->get();
        }

        // Verificar que hay items en el carrito
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío');
        }

        // Verificar stock antes de procesar
        foreach ($cartItems as $cartItem) {
            if ($cartItem->quantity > $cartItem->product->stock_quantity) {
                return redirect()->back()->with('error', "No hay suficiente stock para {$cartItem->product->name}");
            }
        }

        // Validar método de pago
        $paymentService = new PaymentService();
        $paymentResult = null;
        
        if (in_array($request->payment_method, ['credit_card', 'debit_card'])) {
            $paymentResult = $paymentService->validateCreditCard(
                $request->card_number,
                $request->card_expiry_month,
                $request->card_expiry_year,
                $request->card_cvv,
                $request->cardholder_name
            );
        } elseif ($request->payment_method === 'yape') {
            $paymentResult = $paymentService->validateYape(
                $request->yape_phone,
                $cartItems->sum(function($item) {
                    return $item->quantity * $item->price;
                })
            );
        } else {
            // Para PayPal, simular éxito
            $paymentResult = [
                'valid' => true,
                'message' => 'Pago con PayPal procesado exitosamente',
                'transaction_id' => 'PAYPAL' . time() . rand(1000, 9999)
            ];
        }
        
        if (!$paymentResult['valid']) {
            return redirect()->back()
                ->with('error', $paymentResult['message'])
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Crear la orden
            $order = Order::create([
                'order_number' => 'DX' . time() . rand(1000, 9999),
                'user_id' => Auth::id() ?: null,
                'status' => 'demo_simulation', // Estado especial para simulaciones
                'subtotal' => $cartItems->sum(function($item) {
                    return $item->quantity * $item->price;
                }),
                'tax_amount' => 0,
                'shipping_amount' => 0,
                'total_amount' => $cartItems->sum(function($item) {
                    return $item->quantity * $item->price;
                }),
                'payment_status' => 'demo_simulation',
                'payment_method' => $request->payment_method,
                'billing_address' => [
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone' => $request->customer_phone,
                    'address' => $request->shipping_address
                ],
                'shipping_address' => [
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone' => $request->customer_phone,
                    'address' => $request->shipping_address
                ],
                'notes' => 'Transaction ID: ' . ($paymentResult['transaction_id'] ?? 'N/A'),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'transaction_id' => $paymentResult['transaction_id'] ?? null,
                'session_id' => session()->getId()
            ]);

            // Crear items de la orden y actualizar stock
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);

                // Actualizar stock del producto
                $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
            }

            // Limpiar el carrito
            if (Auth::check()) {
                CartItem::where('user_id', Auth::id())->delete();
            } else {
                CartItem::where('session_id', session()->getId())
                    ->whereNull('user_id')
                    ->delete();
            }

            DB::commit();

            return redirect()->route('checkout.success', $order->id)
                ->with('success', '¡Orden procesada exitosamente!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Hubo un error al procesar tu orden. Por favor, intenta de nuevo.');
        }
    }

    public function success(Order $order)
    {
        // Verificar que el usuario tenga acceso a esta orden
        if (Auth::check()) {
            if ($order->user_id !== Auth::id()) {
                abort(403, 'No tienes permisos para ver esta orden');
            }
        } else {
            if ($order->session_id !== session()->getId()) {
                abort(403, 'No tienes permisos para ver esta orden');
            }
        }

        return view('checkout.success', compact('order'));
    }
}