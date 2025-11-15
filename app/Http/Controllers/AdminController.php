<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Repair;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

    public function inventoryOld()
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

    // ========== CRUD CATEGORÍAS ==========
    public function categories(Request $request)
    {
        $query = Category::query();

        // Filtrar por búsqueda
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filtrar por estado
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        $categories = $query->orderBy('sort_order')->orderBy('name')->paginate(20)->appends($request->query());

        // Estadísticas
        $totalCategories = Category::count();
        $activeCategories = Category::where('is_active', true)->count();
        $inactiveCategories = $totalCategories - $activeCategories;

        return view('admin.categories.index', compact('categories', 'totalCategories', 'activeCategories', 'inactiveCategories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $category = Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'image' => $request->image,
                'is_active' => $request->has('is_active'),
                'sort_order' => $request->sort_order ?? 0,
            ]);

            DB::commit();

            return redirect()->route('admin.categories')
                ->with('success', 'Categoría creada exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la categoría: ' . $e->getMessage());
        }
    }

    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'description' => $request->description,
                'image' => $request->image,
                'is_active' => $request->has('is_active'),
                'sort_order' => $request->sort_order ?? 0,
            ]);

            DB::commit();

            return redirect()->route('admin.categories')
                ->with('success', 'Categoría actualizada exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la categoría: ' . $e->getMessage());
        }
    }

    public function destroyCategory(Category $category)
    {
        try {
            DB::beginTransaction();

            // Verificar si tiene productos asociados
            $productsCount = $category->products()->count();
            
            if ($productsCount > 0) {
                return redirect()->route('admin.categories')
                    ->with('error', 'No se puede eliminar la categoría porque tiene ' . $productsCount . ' producto(s) asociado(s).');
            }

            $categoryName = $category->name;
            $category->delete();

            DB::commit();

            return redirect()->route('admin.categories')
                ->with('success', 'Categoría "' . $categoryName . '" eliminada exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.categories')
                ->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }

    // ========== CRUD REPARACIONES ==========
    public function repairs(Request $request)
    {
        $query = Repair::with('user');

        // Filtrar por búsqueda
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('repair_number', 'like', '%' . $request->search . '%')
                  ->orWhere('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('device_type', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%')
                  ->orWhere('model', 'like', '%' . $request->search . '%');
            });
        }

        // Filtrar por estado
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $repairs = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->query());

        // Estadísticas
        $totalRepairs = Repair::count();
        $pendingRepairs = Repair::where('status', 'pending')->count();
        $inProgressRepairs = Repair::where('status', 'in_progress')->count();
        $completedRepairs = Repair::where('status', 'completed')->count();

        return view('admin.repairs.index', compact('repairs', 'totalRepairs', 'pendingRepairs', 'inProgressRepairs', 'completedRepairs'));
    }

    public function createRepair()
    {
        $users = User::where('email', 'not like', '%@digitalxpress.com')->orderBy('name')->get();
        return view('admin.repairs.create', compact('users'));
    }

    public function storeRepair(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'device_type' => 'required|string|max:100',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'problem_description' => 'required|string|min:20',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'estimated_cost' => 'nullable|numeric|min:0',
            'final_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'device_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $repairData = [
                'repair_number' => Repair::generateRepairNumber(),
                'user_id' => $request->user_id,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'device_type' => $request->device_type,
                'brand' => $request->brand,
                'model' => $request->model,
                'problem_description' => $request->problem_description,
                'status' => $request->status,
                'estimated_cost' => $request->estimated_cost,
                'final_cost' => $request->final_cost,
                'notes' => $request->notes,
            ];

            // Manejar subida de imagen
            if ($request->hasFile('device_image')) {
                $imagePath = $request->file('device_image')->store('repairs', 'public');
                $repairData['device_image'] = $imagePath;
            }

            Repair::create($repairData);

            DB::commit();

            return redirect()->route('admin.repairs')
                ->with('success', 'Reparación creada exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la reparación: ' . $e->getMessage());
        }
    }

    public function editRepair(Repair $repair)
    {
        $users = User::where('email', 'not like', '%@digitalxpress.com')->orderBy('name')->get();
        return view('admin.repairs.edit', compact('repair', 'users'));
    }

    public function updateRepair(Request $request, Repair $repair)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'device_type' => 'required|string|max:100',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'problem_description' => 'required|string|min:20',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'estimated_cost' => 'nullable|numeric|min:0',
            'final_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'device_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            DB::beginTransaction();

            $repairData = [
                'user_id' => $request->user_id,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'device_type' => $request->device_type,
                'brand' => $request->brand,
                'model' => $request->model,
                'problem_description' => $request->problem_description,
                'status' => $request->status,
                'estimated_cost' => $request->estimated_cost,
                'final_cost' => $request->final_cost,
                'notes' => $request->notes,
            ];

            // Manejar subida de imagen
            if ($request->hasFile('device_image')) {
                // Eliminar imagen anterior si existe
                if ($repair->device_image) {
                    Storage::disk('public')->delete($repair->device_image);
                }
                $imagePath = $request->file('device_image')->store('repairs', 'public');
                $repairData['device_image'] = $imagePath;
            }

            $repair->update($repairData);

            DB::commit();

            return redirect()->route('admin.repairs')
                ->with('success', 'Reparación actualizada exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la reparación: ' . $e->getMessage());
        }
    }

    public function destroyRepair(Repair $repair)
    {
        try {
            DB::beginTransaction();

            // Eliminar imagen si existe
            if ($repair->device_image) {
                Storage::disk('public')->delete($repair->device_image);
            }

            $repairNumber = $repair->repair_number;
            $repair->delete();

            DB::commit();

            return redirect()->route('admin.repairs')
                ->with('success', 'Reparación "' . $repairNumber . '" eliminada exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.repairs')
                ->with('error', 'Error al eliminar la reparación: ' . $e->getMessage());
        }
    }

    // ========== CRUD PEDIDOS ==========
    public function createOrder()
    {
        $users = User::where('email', 'not like', '%@digitalxpress.com')->orderBy('name')->get();
        $products = Product::where('is_active', true)->where('in_stock', true)->orderBy('name')->get();
        return view('admin.orders.create', compact('users', 'products'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'payment_method' => 'nullable|string|max:255',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'billing_address' => 'required|array',
            'shipping_address' => 'required|array',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Generar número de pedido único
            $orderNumber = 'ORD' . date('Ymd') . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $request->user_id,
                'status' => $request->status,
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'subtotal' => $request->subtotal,
                'tax_amount' => $request->tax_amount ?? 0,
                'shipping_amount' => $request->shipping_amount ?? 0,
                'total_amount' => $request->total_amount,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'billing_address' => $request->billing_address,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
            ]);

            // Crear items del pedido
            foreach ($request->products as $productData) {
                $order->orderItems()->create([
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                    'price' => $productData['price'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.orders')
                ->with('success', 'Pedido creado exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el pedido: ' . $e->getMessage());
        }
    }

    public function editOrder(Order $order)
    {
        $users = User::where('email', 'not like', '%@digitalxpress.com')->orderBy('name')->get();
        $order->load('orderItems.product');
        return view('admin.orders.edit', compact('order', 'users'));
    }

    public function updateOrder(Request $request, Order $order)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'payment_method' => 'nullable|string|max:255',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'billing_address' => 'required|array',
            'shipping_address' => 'required|array',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $order->update([
                'user_id' => $request->user_id,
                'status' => $request->status,
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'subtotal' => $request->subtotal,
                'tax_amount' => $request->tax_amount ?? 0,
                'shipping_amount' => $request->shipping_amount ?? 0,
                'total_amount' => $request->total_amount,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'billing_address' => $request->billing_address,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->route('admin.orders')
                ->with('success', 'Pedido actualizado exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el pedido: ' . $e->getMessage());
        }
    }

    public function destroyOrder(Order $order)
    {
        try {
            DB::beginTransaction();

            $orderNumber = $order->order_number;
            
            // Eliminar items del pedido
            $order->orderItems()->delete();
            
            // Eliminar pedido
            $order->delete();

            DB::commit();

            return redirect()->route('admin.orders')
                ->with('success', 'Pedido "' . $orderNumber . '" eliminado exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.orders')
                ->with('error', 'Error al eliminar el pedido: ' . $e->getMessage());
        }
    }

    // ========== CRUD INVENTARIO ==========
    public function inventoryIndex(Request $request)
    {
        $query = Product::where('is_active', true);

        // Filtrar por búsqueda
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // Filtrar por stock
        if ($request->has('stock_status') && $request->stock_status !== '') {
            switch ($request->stock_status) {
                case 'in_stock':
                    $query->where('in_stock', true)->where('stock_quantity', '>', 0);
                    break;
                case 'low_stock':
                    $query->where('stock_quantity', '>', 0)->where('stock_quantity', '<', 10);
                    break;
                case 'out_of_stock':
                    $query->where(function($q) {
                        $q->where('stock_quantity', 0)->orWhere('in_stock', false);
                    });
                    break;
            }
        }

        $products = $query->with('category')->orderBy('name')->paginate(20)->appends($request->query());

        // Estadísticas
        $totalProducts = Product::where('is_active', true)->count();
        $inStockProducts = Product::where('is_active', true)->where('in_stock', true)->where('stock_quantity', '>', 0)->count();
        $lowStockProducts = Product::where('is_active', true)->where('stock_quantity', '>', 0)->where('stock_quantity', '<', 10)->count();
        $outOfStockProducts = Product::where('is_active', true)->where(function($q) {
            $q->where('stock_quantity', 0)->orWhere('in_stock', false);
        })->count();

        return view('admin.inventory.index', compact('products', 'totalProducts', 'inStockProducts', 'lowStockProducts', 'outOfStockProducts'));
    }

    public function createInventoryMovement()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.inventory.create', compact('products'));
    }

    public function storeInventoryMovement(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'movement_type' => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);

            $oldStock = $product->stock_quantity;

            switch ($request->movement_type) {
                case 'add':
                    $newStock = $oldStock + $request->quantity;
                    break;
                case 'subtract':
                    $newStock = max(0, $oldStock - $request->quantity);
                    break;
                case 'set':
                    $newStock = $request->quantity;
                    break;
            }

            $product->update([
                'stock_quantity' => $newStock,
                'in_stock' => $newStock > 0,
            ]);

            // Aquí podrías crear un registro en una tabla de movimientos de inventario si la creas
            // Por ahora solo actualizamos el stock del producto

            DB::commit();

            return redirect()->route('admin.inventory')
                ->with('success', 'Movimiento de inventario registrado exitosamente. Stock actualizado de ' . $oldStock . ' a ' . $newStock . '.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar el movimiento: ' . $e->getMessage());
        }
    }

    public function editInventoryMovement(Product $product)
    {
        return view('admin.inventory.edit', compact('product'));
    }

    public function updateInventoryMovement(Request $request, Product $product)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'in_stock' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $product->update([
                'stock_quantity' => $request->stock_quantity,
                'in_stock' => $request->has('in_stock') ? ($request->stock_quantity > 0) : false,
            ]);

            DB::commit();

            return redirect()->route('admin.inventory')
                ->with('success', 'Inventario actualizado exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el inventario: ' . $e->getMessage());
        }
    }

    public function destroyInventoryMovement(Product $product)
    {
        // Para inventario, no tiene sentido "eliminar" un movimiento
        // En su lugar, podríamos resetear el stock a 0
        try {
            DB::beginTransaction();

            $product->update([
                'stock_quantity' => 0,
                'in_stock' => false,
            ]);

            DB::commit();

            return redirect()->route('admin.inventory')
                ->with('success', 'Stock del producto "' . $product->name . '" reseteado a 0.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.inventory')
                ->with('error', 'Error al resetear el stock: ' . $e->getMessage());
        }
    }
}