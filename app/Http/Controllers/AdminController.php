<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Total de productos (datos reales)
        $totalProducts = Product::where('is_active', true)->count();
        
        // Productos creados antes del mes pasado (datos reales)
        $totalProductsLastMonth = Product::where('is_active', true)
            ->where('created_at', '<', now()->subMonth())
            ->count();
        $productsChange = $totalProducts - $totalProductsLastMonth;

        // Valor del inventario actual (precio * stock_quantity) - datos reales
        $inventoryValue = Product::where('is_active', true)
            ->get()
            ->sum(function($product) {
                return $product->price * $product->stock_quantity;
            });
        
        // Valor del inventario del mes pasado (datos reales)
        // Calculamos el valor basado en productos que existían hace un mes
        $inventoryValueLastMonth = Product::where('is_active', true)
            ->where('created_at', '<', now()->subMonth())
            ->get()
            ->sum(function($product) {
                // Usamos el precio y stock actual, ya que no tenemos histórico de cambios de precio/stock
                // En producción, podrías tener una tabla de historial de inventario
                return $product->price * $product->stock_quantity;
            });
        
        // Calcular porcentaje de cambio real
        $inventoryChangePercent = 0;
        if ($inventoryValueLastMonth > 0) {
            $inventoryChangePercent = round((($inventoryValue - $inventoryValueLastMonth) / $inventoryValueLastMonth) * 100, 0);
        } elseif ($inventoryValue > 0) {
            // Si no hay datos del mes pasado pero sí hay inventario actual, es 100% de crecimiento
            $inventoryChangePercent = 100;
        }

        // Stock bajo (menos de 10 unidades) - datos reales
        $lowStockCount = Product::where('is_active', true)
            ->where('stock_quantity', '<', 10)
            ->where('stock_quantity', '>', 0)
            ->count();

        // Sin stock (stock_quantity = 0 o in_stock = false) - datos reales
        $outOfStockCount = Product::where('is_active', true)
            ->where(function($query) {
                $query->where('stock_quantity', '<=', 0)
                      ->orWhere('in_stock', false);
            })
            ->count();

        // Órdenes recientes (excluyendo simulaciones) - datos reales
        $recentOrders = Order::where('status', '!=', 'demo_simulation')
            ->with(['orderItems.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'productsChange',
            'inventoryValue',
            'inventoryChangePercent',
            'lowStockCount',
            'outOfStockCount',
            'recentOrders'
        ));
    }

    public function orders()
    {
        $orders = Order::where('status', '!=', 'demo_simulation')
            ->with(['orderItems.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function orderDetails(Order $order)
    {
        $order->load(['orderItems.product', 'user']);
        
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Estado de la orden actualizado exitosamente');
    }

    public function revenue()
    {
        // Ingresos por día (últimos 30 días) - excluyendo simulaciones
        $dailyRevenue = Order::where('status', '!=', 'demo_simulation')
            ->where('status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Ingresos por método de pago (excluyendo simulaciones)
        $revenueByPaymentMethod = Order::where('status', '!=', 'demo_simulation')
            ->where('status', 'paid')
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('payment_method')
            ->get();

        // Ingresos totales por mes (excluyendo simulaciones) - Compatible con SQLite
        $monthlyRevenue = Order::where('status', '!=', 'demo_simulation')
            ->where('status', 'paid')
            ->select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return view('admin.revenue', compact(
            'dailyRevenue',
            'revenueByPaymentMethod',
            'monthlyRevenue'
        ));
    }

    public function products(Request $request)
    {
        $query = Product::with('category');

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

        return view('admin.products.index', compact('products'));
    }

    public function inventory()
    {
        // Productos con stock bajo (menos de 10 unidades)
        $lowStockProducts = Product::where('is_active', true)
            ->where('stock_quantity', '<', 10)
            ->where('stock_quantity', '>', 0)
            ->with('category')
            ->orderBy('stock_quantity', 'asc')
            ->get();

        // Productos sin stock
        $outOfStockProducts = Product::where('is_active', true)
            ->where(function($query) {
                $query->where('stock_quantity', '<=', 0)
                      ->orWhere('in_stock', false);
            })
            ->with('category')
            ->get();

        // Resumen de inventario
        $totalProducts = Product::where('is_active', true)->count();
        $totalValue = Product::where('is_active', true)
            ->get()
            ->sum(function($product) {
                return $product->price * $product->stock_quantity;
            });
        $lowStockCount = $lowStockProducts->count();
        $outOfStockCount = $outOfStockProducts->count();

        return view('admin.inventory.index', compact(
            'lowStockProducts',
            'outOfStockProducts',
            'totalProducts',
            'totalValue',
            'lowStockCount',
            'outOfStockCount'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();

        // Filtrar por búsqueda
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filtrar por tipo de usuario (admin o no)
        if ($request->has('type') && $request->type) {
            if ($request->type === 'admin') {
                $query->where('email', 'like', '%@digitalxpress.com');
            } elseif ($request->type === 'customer') {
                $query->where('email', 'not like', '%@digitalxpress.com');
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->query());

        // Estadísticas
        $totalUsers = User::count();
        $adminUsers = User::where('email', 'like', '%@digitalxpress.com')->count();
        $customerUsers = $totalUsers - $adminUsers;

        return view('admin.users.index', compact('users', 'totalUsers', 'adminUsers', 'customerUsers'));
    }

    public function settings()
    {
        return view('admin.settings.index');
    }

    // ========== CRUD USUARIOS ==========
    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,customer,technician,vip',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'email_verified_at' => now(),
            ]);

            // Forzar guardado inmediato
            $user->save();

            DB::commit();

            return redirect()->route('admin.users')
                ->with('success', 'Usuario creado exitosamente y guardado en la base de datos.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,customer,technician,vip',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
            $user->save(); // Forzar guardado inmediato

            DB::commit();

            return redirect()->route('admin.users')
                ->with('success', 'Usuario actualizado exitosamente y guardado en la base de datos.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    public function destroyUser(User $user)
    {
        // No permitir eliminar al usuario actual
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        try {
            DB::beginTransaction();

            $userEmail = $user->email;
            $user->delete();

            DB::commit();

            return redirect()->route('admin.users')
                ->with('success', 'Usuario eliminado exitosamente de la base de datos.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.users')
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    // ========== CRUD PRODUCTOS ==========
    public function createProduct()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'in_stock' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'short_description' => $request->short_description,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'sku' => $request->sku,
                'stock_quantity' => $request->stock_quantity,
                'category_id' => $request->category_id,
                'is_featured' => $request->has('is_featured'),
                'is_active' => $request->has('is_active'),
                'in_stock' => $request->has('in_stock') ? true : ($request->stock_quantity > 0),
                'manage_stock' => true,
                'rating' => 0,
                'review_count' => 0,
            ]);

            // Forzar guardado inmediato
            $product->save();

            DB::commit();

            return redirect()->route('admin.products')
                ->with('success', 'Producto creado exitosamente y guardado en la base de datos.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el producto: ' . $e->getMessage());
        }
    }

    public function editProduct(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'in_stock' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'short_description' => $request->short_description,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'sku' => $request->sku,
                'stock_quantity' => $request->stock_quantity,
                'category_id' => $request->category_id,
                'is_featured' => $request->has('is_featured'),
                'is_active' => $request->has('is_active'),
                'in_stock' => $request->has('in_stock') ? true : ($request->stock_quantity > 0),
            ]);

            // Forzar guardado inmediato
            $product->save();

            DB::commit();

            return redirect()->route('admin.products')
                ->with('success', 'Producto actualizado exitosamente y guardado en la base de datos.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    public function destroyProduct(Product $product)
    {
        try {
            DB::beginTransaction();

            $productName = $product->name;
            $product->delete();

            DB::commit();

            return redirect()->route('admin.products')
                ->with('success', 'Producto eliminado exitosamente de la base de datos.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.products')
                ->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }
}