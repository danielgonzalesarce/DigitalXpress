<?php

/**
 * AdminController
 * 
 * Controlador principal para el panel de administración de DigitalXpress.
 * Maneja todas las operaciones CRUD (Create, Read, Update, Delete) para:
 * - Productos
 * - Categorías
 * - Pedidos (Orders)
 * - Reparaciones
 * - Usuarios
 * - Configuración (Settings)
 * - Inventario
 * - Análisis de ingresos
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Repair;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Exception;

class AdminController extends Controller
{
    /**
     * ============================================
     * DASHBOARD - Panel Principal
     * ============================================
     * 
     * Muestra el dashboard del administrador con:
     * - Total de productos activos
     * - Cambio en cantidad de productos (mes actual vs mes anterior)
     * - Valor total del inventario
     * - Porcentaje de cambio del inventario
     * - Productos con stock bajo (< 10 unidades)
     * - Productos sin stock (0 unidades)
     * - Pedidos recientes (últimos 5)
     * 
     * @return \Illuminate\View\View Vista del dashboard con todos los datos
     */
    public function dashboard()
    {
        // Datos reales de la base de datos
        $totalProducts = Product::where('is_active', true)->count();
        
        // Productos creados antes del mes pasado (para calcular cambio)
        $totalProductsLastMonth = Product::where('is_active', true)
            ->where('created_at', '<', now()->subMonth())
            ->count();
        $productsChange = $totalProducts - $totalProductsLastMonth;
        
        // Valor del inventario actual
        $inventoryValue = Product::where('is_active', true)
            ->get()
            ->sum(function($product) {
                return $product->price * $product->stock_quantity;
            });
        
        // Valor del inventario del mes pasado
        $inventoryValueLastMonth = Product::where('is_active', true)
            ->where('created_at', '<', now()->subMonth())
            ->get()
            ->sum(function($product) {
                return $product->price * $product->stock_quantity;
            });
        
        // Calcular porcentaje de cambio del inventario
        $inventoryChangePercent = 0;
        if ($inventoryValueLastMonth > 0) {
            $inventoryChangePercent = round((($inventoryValue - $inventoryValueLastMonth) / $inventoryValueLastMonth) * 100, 0);
        } elseif ($inventoryValue > 0) {
            $inventoryChangePercent = 100;
        }
        
        $lowStockCount = Product::where('is_active', true)
            ->where('stock_quantity', '<', 10)
            ->where('stock_quantity', '>', 0)
            ->count();
        $outOfStockCount = Product::where('is_active', true)
            ->where(function($query) {
                $query->where('stock_quantity', '<=', 0)->orWhere('in_stock', false);
            })
            ->count();
        $recentOrders = Order::with(['orderItems.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
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

    /**
     * ============================================
     * PRODUCTOS - Gestión de Productos
     * ============================================
     */
    
    /**
     * Listar todos los productos del catálogo
     * 
     * Permite buscar productos por nombre o SKU.
     * Muestra 20 productos por página ordenados por fecha de creación (más recientes primero).
     * 
     * @param Request $request Contiene parámetros de búsqueda (search)
     * @return \Illuminate\View\View Vista con lista de productos paginada
     */
    public function products(Request $request)
    {
        // Iniciar consulta con relación de categoría cargada
        $query = Product::with('category');

        // Si hay búsqueda, filtrar por nombre o SKU
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // Ordenar por fecha de creación descendente y paginar (20 por página)
        $products = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Mostrar formulario para crear un nuevo producto
     * 
     * Obtiene todas las categorías activas para mostrarlas en el formulario.
     * 
     * @return \Illuminate\View\View Vista del formulario de creación
     */
    public function createProduct()
    {
        // Obtener solo categorías activas ordenadas por nombre
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Guardar un nuevo producto en la base de datos
     * 
     * Valida los datos del formulario, procesa las imágenes subidas,
     * crea el producto y limpia la caché relacionada.
     * 
     * @param Request $request Datos del formulario (name, description, price, etc.)
     * @return \Illuminate\Http\RedirectResponse Redirige a la lista de productos con mensaje de éxito/error
     */
    public function storeProduct(Request $request)
    {
        // Validar datos del formulario
        $request->validate([
            'name' => 'required|string|max:255', // Nombre obligatorio, máximo 255 caracteres
            'description' => 'required|string', // Descripción obligatoria
            'price' => 'required|numeric|min:0', // Precio obligatorio, debe ser numérico y >= 0
            'sku' => 'required|string|unique:products', // SKU obligatorio y único en la tabla products
            'stock_quantity' => 'required|integer|min:0', // Cantidad de stock obligatoria, entero >= 0
            'category_id' => 'required|exists:categories,id', // Categoría obligatoria, debe existir en la tabla categories
            'image_files' => 'nullable|array', // Imágenes opcionales, debe ser un array
            'image_files.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Cada imagen: tipo imagen, formatos permitidos, máximo 2MB
        ]);

        try {
            // Iniciar transacción de base de datos para asegurar integridad
            DB::beginTransaction();

            // Procesar imágenes subidas
            $images = [];
            if ($request->hasFile('image_files')) {
                foreach ($request->file('image_files') as $file) {
                    if ($file->isValid()) {
                        // Guardar imagen en storage/app/public/products y obtener la ruta
                        $images[] = $file->store('products', 'public');
                    }
                }
            }

            // Crear el producto con los datos validados
            $product = Product::create([
                'name' => trim($request->name),
                'slug' => Str::slug(trim($request->name)),
                'description' => trim($request->description),
                'short_description' => $request->short_description ? trim($request->short_description) : null,
                'price' => (float) $request->price,
                'sale_price' => $request->sale_price ? (float) $request->sale_price : null,
                'sku' => trim($request->sku),
                'stock_quantity' => (int) $request->stock_quantity,
                'category_id' => (int) $request->category_id,
                'is_featured' => $request->has('is_featured'),
                'is_active' => $request->has('is_active'),
                'in_stock' => $request->has('in_stock') ? true : ($request->stock_quantity > 0),
                'images' => !empty($images) ? $images : null,
            ]);

            $product->save();
            DB::commit();
            $product->refresh();

            Cache::forget('products_list');
            Cache::forget('products_featured');

            return redirect()->route('admin.products')
                ->with('success', 'Producto creado exitosamente y guardado en la base de datos.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el producto: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar un producto existente
     * 
     * Obtiene el producto y todas las categorías activas para el formulario.
     * 
     * @param Product $product Modelo del producto a editar
     * @return \Illuminate\View\View Vista del formulario de edición
     */
    public function editProduct(Product $product)
    {
        // Obtener solo categorías activas ordenadas por nombre
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Actualizar un producto existente en la base de datos
     * 
     * Valida los datos, procesa imágenes (agregar nuevas, eliminar existentes),
     * actualiza el producto y limpia la caché relacionada.
     * 
     * @param Request $request Datos del formulario
     * @param Product $product Modelo del producto a actualizar
     * @return \Illuminate\Http\RedirectResponse Redirige a la lista de productos con mensaje
     */
    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image_files' => 'nullable|array',
            'image_files.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => trim($request->name),
                'slug' => Str::slug(trim($request->name)),
                'description' => trim($request->description),
                'short_description' => $request->short_description ? trim($request->short_description) : null,
                'price' => (float) $request->price,
                'sale_price' => $request->sale_price ? (float) $request->sale_price : null,
                'sku' => trim($request->sku),
                'stock_quantity' => (int) $request->stock_quantity,
                'category_id' => (int) $request->category_id,
                'is_featured' => $request->has('is_featured'),
                'is_active' => $request->has('is_active'),
                'in_stock' => $request->has('in_stock') ? true : ($request->stock_quantity > 0),
            ];

            // Manejar imágenes
            $images = [];
            if ($request->has('images') && is_array($request->images)) {
                $images = array_filter($request->images, function($img) {
                    return !empty($img) && trim($img) !== '';
                });
            }

            if ($request->has('images_to_delete') && is_array($request->images_to_delete)) {
                foreach ($request->images_to_delete as $imageToDelete) {
                    if ($imageToDelete && !str_starts_with($imageToDelete, 'http')) {
                        $imagePath = storage_path('app/public/' . $imageToDelete);
                        if (file_exists($imagePath)) {
                            @unlink($imagePath);
                        }
                    }
                }
            }

            if ($request->hasFile('image_files')) {
                foreach ($request->file('image_files') as $file) {
                    if ($file->isValid()) {
                        $images[] = $file->store('products', 'public');
                    }
                }
            }

            $data['images'] = array_values($images);

            $product->update($data);
            $product->save();

            DB::commit();
            $product->refresh();

            Log::info('Producto actualizado exitosamente', [
                'product_id' => $product->id,
                'product_name' => $product->name
            ]);

            Cache::forget('product_' . $product->id);
            Cache::forget('products_list');
            Cache::forget('products_featured');

            return redirect()->route('admin.products')
                ->with('success', 'Producto "' . $product->name . '" actualizado exitosamente y guardado en la base de datos.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Por favor, corrige los errores en el formulario.');
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
            $productId = $product->id;

            if ($product->images && is_array($product->images)) {
                foreach ($product->images as $image) {
                    if ($image && !str_starts_with($image, 'http')) {
                        $imagePath = storage_path('app/public/' . $image);
                        if (file_exists($imagePath)) {
                            @unlink($imagePath);
                        }
                    }
                }
            }

            $product->delete();
            DB::commit();

            Cache::forget('product_' . $productId);
            Cache::forget('products_list');
            Cache::forget('products_featured');

            return redirect()->route('admin.products')
                ->with('success', 'Producto "' . $productName . '" eliminado exitosamente de la base de datos.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.products')
                ->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    // ========== CATEGORÍAS ==========
    public function categories(Request $request)
    {
        $query = Category::query();

        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->orderBy('name')->paginate(20);
        $totalCategories = Category::count();
        $activeCategories = Category::where('is_active', true)->count();
        $inactiveCategories = Category::where('is_active', false)->count();

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
        ]);

        try {
            DB::beginTransaction();

            Category::create([
                'name' => trim($request->name),
                'slug' => Str::slug(trim($request->name)),
                'description' => $request->description ? trim($request->description) : null,
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
        ]);

        try {
            DB::beginTransaction();

            $category->update([
                'name' => trim($request->name),
                'slug' => Str::slug(trim($request->name)),
                'description' => $request->description ? trim($request->description) : null,
                'is_active' => $request->has('is_active'),
                'sort_order' => $request->sort_order ?? 0,
            ]);

            $category->save();
            DB::commit();
            $category->refresh();

            Log::info('Categoría actualizada exitosamente', [
                'category_id' => $category->id,
                'category_name' => $category->name
            ]);

            Cache::forget('categories_list');
            Cache::forget('category_' . $category->id);

            return redirect()->route('admin.categories')
                ->with('success', 'Categoría "' . $category->name . '" actualizada exitosamente y guardada en la base de datos.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Por favor, corrige los errores en el formulario.');
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

            $categoryName = $category->name;
            $productsCount = $category->products()->count();

            if ($productsCount > 0) {
                return redirect()->route('admin.categories')
                    ->with('error', 'No se puede eliminar la categoría porque tiene ' . $productsCount . ' producto(s) asociado(s).');
            }

            $category->delete();
            DB::commit();

            Cache::forget('categories_list');
            Cache::forget('category_' . $category->id);

            return redirect()->route('admin.categories')
                ->with('success', 'Categoría "' . $categoryName . '" eliminada exitosamente de la base de datos.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.categories')
                ->with('error', 'Error al eliminar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * ============================================
     * PEDIDOS - Gestión de Órdenes
     * ============================================
     */
    
    /**
     * Listar todos los pedidos del sistema
     * 
     * Permite buscar pedidos por número, cliente, email, etc.
     * Muestra 20 pedidos por página ordenados por fecha (más recientes primero).
     * 
     * @param Request $request Parámetros de búsqueda
     * @return \Illuminate\View\View Vista con lista de pedidos paginada
     */
    public function orders()
    {
        $orders = Order::with(['orderItems.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function createOrder()
    {
        $users = User::where('email', 'not like', '%@digitalxpress.com')->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.orders.create', compact('users', 'products'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
        ]);

        try {
            DB::beginTransaction();

            $orderNumber = 'ORD' . date('Ymd') . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => (int) $request->user_id,
                'status' => $request->status,
                'payment_status' => $request->payment_status,
                'subtotal' => (float) $request->subtotal,
                'tax_amount' => $request->tax_amount ? (float) $request->tax_amount : 0,
                'shipping_amount' => $request->shipping_amount ? (float) $request->shipping_amount : 0,
                'total_amount' => (float) $request->total_amount,
                'customer_name' => trim($request->customer_name),
                'customer_email' => trim($request->customer_email),
                'customer_phone' => $request->customer_phone ? trim($request->customer_phone) : null,
                'notes' => $request->notes ? trim($request->notes) : null,
            ]);

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

    public function orderDetails(Order $order)
    {
        $order->load('orderItems.product', 'user');
        return view('admin.orders.show', compact('order'));
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
            'subtotal' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
        ]);

        try {
            DB::beginTransaction();

            $order->update([
                'user_id' => (int) $request->user_id,
                'status' => $request->status,
                'payment_status' => $request->payment_status,
                'subtotal' => (float) $request->subtotal,
                'tax_amount' => $request->tax_amount ? (float) $request->tax_amount : 0,
                'shipping_amount' => $request->shipping_amount ? (float) $request->shipping_amount : 0,
                'total_amount' => (float) $request->total_amount,
                'customer_name' => trim($request->customer_name),
                'customer_email' => trim($request->customer_email),
                'customer_phone' => $request->customer_phone ? trim($request->customer_phone) : null,
                'notes' => $request->notes ? trim($request->notes) : null,
            ]);

            $order->save();
            DB::commit();
            $order->refresh();

            Log::info('Pedido actualizado exitosamente', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

            return redirect()->route('admin.orders')
                ->with('success', 'Pedido "' . $order->order_number . '" actualizado exitosamente y guardado en la base de datos.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Por favor, corrige los errores en el formulario.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el pedido: ' . $e->getMessage());
        }
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        try {
            DB::beginTransaction();

            $order->update(['status' => $request->status]);
            $order->save();
            DB::commit();
            $order->refresh();

            return redirect()->route('admin.orders')
                ->with('success', 'Estado del pedido actualizado exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al actualizar el estado: ' . $e->getMessage());
        }
    }

    public function destroyOrder(Order $order)
    {
        try {
            DB::beginTransaction();

            $orderNumber = $order->order_number;
            $order->orderItems()->delete();
            $order->delete();
            DB::commit();

            return redirect()->route('admin.orders')
                ->with('success', 'Pedido "' . $orderNumber . '" eliminado exitosamente de la base de datos.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.orders')
                ->with('error', 'Error al eliminar el pedido: ' . $e->getMessage());
        }
    }

    /**
     * ============================================
     * REPARACIONES - Gestión de Servicios Técnicos
     * ============================================
     */
    
    /**
     * Listar todas las reparaciones del sistema
     * 
     * Permite buscar reparaciones por número, nombre del cliente, email, teléfono,
     * tipo de dispositivo, marca o modelo.
     * Permite filtrar por estado (pending, in_progress, completed, cancelled).
     * 
     * Muestra estadísticas reales:
     * - Total de reparaciones
     * - Reparaciones pendientes
     * - Reparaciones en progreso
     * - Reparaciones completadas
     * 
     * Muestra 20 reparaciones por página ordenadas por fecha (más recientes primero).
     * 
     * @param Request $request Parámetros de búsqueda y filtrado
     * @return \Illuminate\View\View Vista con lista de reparaciones y estadísticas
     */
    /**
     * Listar todas las reparaciones con filtros y búsqueda
     * 
     * Permite buscar y filtrar reparaciones por estado.
     * Muestra estadísticas de reparaciones por estado.
     * 
     * @param Request $request Solicitud HTTP con parámetros de búsqueda y filtro
     * @return \Illuminate\View\View Vista con lista de reparaciones y estadísticas
     */
    public function repairs(Request $request)
    {
        // Cargar relación de usuario para evitar consultas N+1
        $query = Repair::with('user');

        // Búsqueda por múltiples campos si se proporciona término de búsqueda
        if ($request->has('search') && $request->search) {
            $searchTerm = '%' . $request->search . '%';
            // Buscar en múltiples campos usando OR
            $query->where(function($q) use ($searchTerm) {
                $q->where('repair_number', 'like', $searchTerm)      // Buscar por número de reparación
                  ->orWhere('full_name', 'like', $searchTerm)          // Buscar por nombre del cliente
                  ->orWhere('email', 'like', $searchTerm)              // Buscar por email
                  ->orWhere('phone', 'like', $searchTerm)              // Buscar por teléfono
                  ->orWhere('device_type', 'like', $searchTerm)        // Buscar por tipo de dispositivo
                  ->orWhere('brand', 'like', $searchTerm)              // Buscar por marca
                  ->orWhere('model', 'like', $searchTerm);             // Buscar por modelo
            });
        }

        // Filtrar por estado si se proporciona
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Ordenar reparaciones: primero por estado (completadas al final), luego por fecha (más recientes primero)
        // Las reparaciones completadas aparecerán al final de la lista
        $repairs = $query->orderByRaw("CASE WHEN status = 'completed' THEN 1 ELSE 0 END")
                         ->orderBy('created_at', 'desc')
                         ->paginate(20);

        // Calcular estadísticas totales de reparaciones por estado
        $totalRepairs = Repair::count();                                    // Total de todas las reparaciones
        $pendingRepairs = Repair::where('status', 'pending')->count();      // Reparaciones pendientes
        $inProgressRepairs = Repair::where('status', 'in_progress')->count(); // Reparaciones en progreso
        $completedRepairs = Repair::where('status', 'completed')->count();  // Reparaciones completadas
        $cancelledRepairs = Repair::where('status', 'cancelled')->count();  // Reparaciones canceladas

        // Retornar vista con datos
        return view('admin.repairs.index', compact(
            'repairs', 
            'totalRepairs', 
            'pendingRepairs', 
            'inProgressRepairs', 
            'completedRepairs',
            'cancelledRepairs'
        ));
    }

    /**
     * Mostrar formulario para crear nueva reparación (Admin)
     * 
     * Obtiene lista de usuarios clientes (no administradores) para asignar la reparación.
     * 
     * @return \Illuminate\View\View Vista del formulario de creación
     */
    public function createRepair()
    {
        // Obtener solo usuarios clientes (excluir administradores con @digitalxpress.com)
        $users = User::where('email', 'not like', '%@digitalxpress.com')->orderBy('name')->get();
        return view('admin.repairs.create', compact('users'));
    }

    /**
     * Guardar nueva reparación creada por administrador
     * 
     * Valida los datos, genera número único y crea el registro en la base de datos.
     * 
     * @param Request $request Datos del formulario
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito/error
     */
    public function storeRepair(Request $request)
    {
        // Validar datos del formulario
        $request->validate([
            'user_id' => 'required|exists:users,id',              // Usuario debe existir
            'full_name' => 'required|string|max:255',             // Nombre obligatorio
            'email' => 'required|email|max:255',                  // Email válido obligatorio
            'phone' => 'required|string|max:20',                  // Teléfono obligatorio
            'device_type' => 'required|string|max:100',           // Tipo de dispositivo obligatorio
            'brand' => 'required|string|max:100',                 // Marca obligatoria
            'model' => 'required|string|max:100',                 // Modelo obligatorio
            'problem_description' => 'required|string|min:20',    // Descripción mínima 20 caracteres
            'status' => 'required|in:pending,in_progress,completed,cancelled', // Estado válido
        ]);

        try {
            // Iniciar transacción de base de datos
            DB::beginTransaction();

            // Crear nueva reparación con datos validados
            Repair::create([
                'repair_number' => Repair::generateRepairNumber(), // Generar número único
                'user_id' => $request->user_id,                    // ID del usuario cliente
                'full_name' => trim($request->full_name),           // Limpiar espacios
                'email' => trim($request->email),                  // Limpiar espacios
                'phone' => trim($request->phone),                  // Limpiar espacios
                'device_type' => trim($request->device_type),      // Limpiar espacios
                'brand' => trim($request->brand),                  // Limpiar espacios
                'model' => trim($request->model),                  // Limpiar espacios
                'problem_description' => trim($request->problem_description), // Limpiar espacios
                'status' => $request->status,                      // Estado de la reparación
                'estimated_cost' => $request->estimated_cost ? (float) $request->estimated_cost : null, // Convertir a float si existe
                'final_cost' => $request->final_cost ? (float) $request->final_cost : null,              // Convertir a float si existe
                'notes' => $request->notes ? trim($request->notes) : null, // Notas opcionales
            ]);

            // Confirmar transacción
            DB::commit();

            // Redirigir con mensaje de éxito
            return redirect()->route('admin.repairs')
                ->with('success', 'Reparación creada exitosamente.');
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la reparación: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar reparación existente
     * 
     * @param Repair $repair Reparación a editar
     * @return \Illuminate\View\View Vista del formulario de edición
     */
    public function editRepair(Repair $repair)
    {
        // Obtener lista de usuarios clientes para el selector
        $users = User::where('email', 'not like', '%@digitalxpress.com')->orderBy('name')->get();
        return view('admin.repairs.edit', compact('repair', 'users'));
    }

    /**
     * Actualizar reparación existente
     * 
     * Valida y actualiza los datos de la reparación en la base de datos.
     * 
     * @param Request $request Datos del formulario
     * @param Repair $repair Reparación a actualizar
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito/error
     */
    public function updateRepair(Request $request, Repair $repair)
    {
        // Validar datos del formulario
        $request->validate([
            'user_id' => 'required|exists:users,id',              // Usuario debe existir
            'full_name' => 'required|string|max:255',             // Nombre obligatorio
            'email' => 'required|email|max:255',                  // Email válido obligatorio
            'phone' => 'required|string|max:20',                  // Teléfono obligatorio
            'device_type' => 'required|string|max:100',           // Tipo de dispositivo obligatorio
            'brand' => 'required|string|max:100',                 // Marca obligatoria
            'model' => 'required|string|max:100',                 // Modelo obligatorio
            'problem_description' => 'required|string|min:20',    // Descripción mínima 20 caracteres
            'status' => 'required|in:pending,in_progress,completed,cancelled', // Estado válido
        ]);

        try {
            // Iniciar transacción de base de datos
            DB::beginTransaction();

            // Actualizar campos de la reparación
            $repair->update([
                'user_id' => (int) $request->user_id,            // Convertir a entero
                'full_name' => trim($request->full_name),        // Limpiar espacios
                'email' => trim($request->email),                // Limpiar espacios
                'phone' => trim($request->phone),                // Limpiar espacios
                'device_type' => trim($request->device_type),     // Limpiar espacios
                'brand' => trim($request->brand),                // Limpiar espacios
                'model' => trim($request->model),                // Limpiar espacios
                'problem_description' => trim($request->problem_description), // Limpiar espacios
                'status' => $request->status,                     // Estado actualizado
                'estimated_cost' => $request->estimated_cost ? (float) $request->estimated_cost : null, // Convertir a float si existe
                'final_cost' => $request->final_cost ? (float) $request->final_cost : null,              // Convertir a float si existe
                'notes' => $request->notes ? trim($request->notes) : null, // Notas opcionales
            ]);

            // Guardar cambios explícitamente
            $repair->save();
            
            // Confirmar transacción
            DB::commit();
            
            // Refrescar modelo para obtener datos actualizados
            $repair->refresh();

            // Registrar en log la actualización exitosa
            Log::info('Reparación actualizada exitosamente', [
                'repair_id' => $repair->id,
                'repair_number' => $repair->repair_number
            ]);

            // Redirigir con mensaje de éxito
            return redirect()->route('admin.repairs')
                ->with('success', 'Reparación "' . $repair->repair_number . '" actualizada exitosamente y guardada en la base de datos.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Revertir transacción si hay errores de validación
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Por favor, corrige los errores en el formulario.');
        } catch (Exception $e) {
            // Revertir transacción si hay error general
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la reparación: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar reparación permanentemente
     * 
     * Elimina la reparación y su imagen asociada si existe.
     * 
     * @param Repair $repair Reparación a eliminar
     * @return \Illuminate\Http\RedirectResponse Redirección con mensaje de éxito/error
     */
    public function destroyRepair(Repair $repair)
    {
        try {
            // Iniciar transacción de base de datos
            DB::beginTransaction();

            // Guardar número de reparación para el mensaje (se perderá después del delete)
            $repairNumber = $repair->repair_number;

            // Eliminar imagen del dispositivo si existe
            if ($repair->device_image) {
                $imagePath = storage_path('app/public/' . $repair->device_image);
                // Verificar que el archivo exista antes de eliminarlo
                if (file_exists($imagePath)) {
                    @unlink($imagePath); // Eliminar archivo físico
                }
            }

            // Eliminar registro de la base de datos
            $repair->delete();
            
            // Confirmar transacción
            DB::commit();

            // Redirigir con mensaje de éxito
            return redirect()->route('admin.repairs')
                ->with('success', 'Reparación "' . $repairNumber . '" eliminada exitosamente de la base de datos.');
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            return redirect()->route('admin.repairs')
                ->with('error', 'Error al eliminar la reparación: ' . $e->getMessage());
        }
    }

    // ========== USUARIOS ==========
    public function users(Request $request)
    {
        // Consulta base (datos reales de la base de datos)
        $query = User::query();

        // Búsqueda por nombre o email (datos reales)
        if ($request->has('search') && $request->search) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm);
            });
        }

        // Filtro por tipo de usuario (datos reales)
        if ($request->has('type') && $request->type !== '') {
            if ($request->type === 'admin') {
                $query->where(function($q) {
                    $q->where('role', 'admin')
                      ->orWhere('email', 'like', '%@digitalxpress.com');
                });
            } elseif ($request->type === 'customer') {
                $query->where('role', '!=', 'admin')
                      ->where('email', 'not like', '%@digitalxpress.com');
            } elseif ($request->type === 'google') {
                // Filtrar solo usuarios registrados con Google
                $query->whereNotNull('google_id');
            }
        }

        // Obtener usuarios ordenados por nombre (datos reales)
        $users = $query->orderBy('name')->paginate(20);

        // Estadísticas reales de la base de datos
        $totalUsers = User::count();
        $adminUsers = User::where(function($q) {
            $q->where('role', 'admin')
              ->orWhere('email', 'like', '%@digitalxpress.com');
        })->count();
        $customerUsers = User::where('role', '!=', 'admin')
            ->where('email', 'not like', '%@digitalxpress.com')
            ->count();
        $googleUsers = User::whereNotNull('google_id')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'adminUsers', 'customerUsers', 'googleUsers'));
    }

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

            User::create([
                'name' => trim($request->name),
                'email' => trim($request->email),
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            DB::commit();

            return redirect()->route('admin.users')
                ->with('success', 'Usuario creado exitosamente.');
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
                'name' => trim($request->name),
                'email' => trim($request->email),
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);
            $user->save();

            DB::commit();
            $user->refresh();

            Log::info('Usuario actualizado exitosamente', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email
            ]);

            return redirect()->route('admin.users')
                ->with('success', 'Usuario "' . $user->name . '" actualizado exitosamente y guardado en la base de datos.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Por favor, corrige los errores en el formulario.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        try {
            DB::beginTransaction();

            $userName = $user->name;
            $user->delete();
            DB::commit();

            return redirect()->route('admin.users')
                ->with('success', 'Usuario "' . $userName . '" eliminado exitosamente de la base de datos.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.users')
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * ============================================
     * INVENTARIO - Gestión de Stock
     * ============================================
     */
    
    /**
     * Mostrar página de inventario con productos y estadísticas de stock
     * 
     * Permite buscar productos por nombre o SKU.
     * Permite filtrar por estado de stock:
     * - in_stock: Productos en stock (cantidad > 0 y in_stock = true)
     * - low_stock: Productos con stock bajo (cantidad > 0 y < 10)
     * - out_of_stock: Productos sin stock (cantidad = 0 o in_stock = false)
     * 
     * Muestra estadísticas reales:
     * - Total de productos activos
     * - Productos en stock
     * - Productos con stock bajo
     * - Productos sin stock
     * 
     * Muestra 20 productos por página ordenados por nombre.
     * 
     * @param Request $request Parámetros de búsqueda y filtrado
     * @return \Illuminate\View\View Vista con productos y estadísticas de inventario
     */
    public function inventoryIndex(Request $request)
    {
        // Consulta base: solo productos activos
        $query = Product::where('is_active', true);

        // Búsqueda por nombre o SKU
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        // Filtro por estado de stock
        if ($request->has('stock_status') && $request->stock_status !== '') {
            switch ($request->stock_status) {
                case 'in_stock':
                    // Productos en stock: cantidad > 0 y in_stock = true
                    $query->where('in_stock', true)->where('stock_quantity', '>', 0);
                    break;
                case 'low_stock':
                    // Productos con stock bajo: cantidad > 0 y < 10
                    $query->where('stock_quantity', '>', 0)->where('stock_quantity', '<', 10);
                    break;
                case 'out_of_stock':
                    // Productos sin stock: cantidad = 0 o in_stock = false
                    $query->where(function($q) {
                        $q->where('stock_quantity', 0)->orWhere('in_stock', false);
                    });
                    break;
            }
        }

        // Obtener productos con categoría cargada, ordenados por nombre, paginados (20 por página)
        $products = $query->with('category')->orderBy('name')->paginate(20);

        // Calcular estadísticas reales de la base de datos
        $totalProducts = Product::where('is_active', true)->count(); // Total de productos activos
        $inStockProducts = Product::where('is_active', true)->where('in_stock', true)->where('stock_quantity', '>', 0)->count(); // En stock
        $lowStockProducts = Product::where('is_active', true)->where('stock_quantity', '>', 0)->where('stock_quantity', '<', 10)->count(); // Stock bajo
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
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            $oldStock = $product->stock_quantity;

            if ($request->movement_type === 'add') {
                $product->stock_quantity += $request->quantity;
            } elseif ($request->movement_type === 'subtract') {
                if ($product->stock_quantity < $request->quantity) {
                    throw new Exception('No hay suficiente stock para restar.');
                }
                $product->stock_quantity -= $request->quantity;
            } elseif ($request->movement_type === 'set') {
                $product->stock_quantity = $request->quantity;
            }

            $product->in_stock = $product->stock_quantity > 0;
            $product->save();

            DB::commit();
            $product->refresh();

            return redirect()->route('admin.inventory')
                ->with('success', 'Movimiento de inventario registrado exitosamente. Nuevo stock: ' . $product->stock_quantity);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar movimiento: ' . $e->getMessage());
        }
    }

    public function editInventoryMovement(Product $product)
    {
        return view('admin.inventory.edit', compact('product'));
    }

    /**
     * Actualizar stock de un producto
     * 
     * Permite actualizar la cantidad en stock de un producto.
     * Soporta tanto peticiones AJAX como peticiones normales.
     * 
     * @param Request $request Datos del formulario (stock_quantity)
     * @param Product $product Modelo del producto a actualizar
     * @return \Illuminate\Http\JsonResponse Respuesta JSON si es AJAX
     * @return \Illuminate\Http\RedirectResponse Redirige si es petición normal
     */
    public function updateInventoryMovement(Request $request, Product $product)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'in_stock' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $oldStock = $product->stock_quantity;
            $newStock = (int) $request->stock_quantity;

            $product->update([
                'stock_quantity' => $newStock,
                'in_stock' => $request->has('in_stock') ? true : ($newStock > 0),
            ]);

            $product->save();
            DB::commit();
            $product->refresh();

            // Limpiar caché relacionado
            Cache::forget('products_list');
            Cache::forget('products_featured');
            Cache::forget('products_category_' . $product->category_id);

            Log::info('Stock actualizado exitosamente', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'old_stock' => $oldStock,
                'new_stock' => $newStock
            ]);

            // Si es petición AJAX, retornar JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Stock actualizado exitosamente',
                    'stock_quantity' => $product->stock_quantity,
                    'in_stock' => $product->in_stock
                ]);
            }

            // Si es petición normal, redirigir
            return redirect()->route('admin.inventory')
                ->with('success', 'Stock del producto "' . $product->name . '" actualizado exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación: ' . implode(', ', Arr::flatten($e->errors()))
                ], 422);
            }
            
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Por favor, corrige los errores en el formulario.');
        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Error al actualizar stock', [
                'product_id' => $product->id,
                'error' => $e->getMessage()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el stock: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el stock: ' . $e->getMessage());
        }
    }

    public function destroyInventoryMovement(Product $product)
    {
        // No se elimina un movimiento, solo se puede editar el stock directamente
        return redirect()->route('admin.inventory');
    }

    // ========== REVENUE ==========
    public function revenue()
    {
        // Ingresos por método de pago (datos reales)
        $revenueByPaymentMethod = Order::where('payment_status', 'paid')
            ->whereNotNull('payment_method')
            ->selectRaw('payment_method, SUM(total_amount) as revenue, COUNT(*) as orders_count')
            ->groupBy('payment_method')
            ->orderByRaw('SUM(total_amount) DESC')
            ->get();

        // Ingresos diarios últimos 30 días (datos reales) - Compatible con SQLite y MySQL
        $dailyRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->get()
            ->groupBy(function($order) {
                return $order->created_at->format('Y-m-d');
            })
            ->map(function($orders, $date) {
                return (object) [
                    'date' => $date,
                    'revenue' => $orders->sum('total_amount'),
                    'orders_count' => $orders->count()
                ];
            })
            ->values()
            ->sortBy('date');

        // Ingresos mensuales (datos reales) - Compatible con SQLite y MySQL
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->get()
            ->groupBy(function($order) {
                return $order->created_at->format('Y-m');
            })
            ->map(function($orders, $month) {
                return (object) [
                    'month' => $month,
                    'revenue' => $orders->sum('total_amount'),
                    'orders_count' => $orders->count()
                ];
            })
            ->values()
            ->sortByDesc('month');

        // Distribución de stock (En stock, Stock bajo, Sin stock)
        $stockDistribution = [
            'in_stock' => Product::where('is_active', true)
                ->where('in_stock', true)
                ->where('stock_quantity', '>', 0)
                ->count(),
            'low_stock' => Product::where('is_active', true)
                ->where('stock_quantity', '>', 0)
                ->where('stock_quantity', '<', 10)
                ->count(),
            'out_of_stock' => Product::where('is_active', true)
                ->where(function($query) {
                    $query->where('stock_quantity', '<=', 0)->orWhere('in_stock', false);
                })
                ->count()
        ];

        return view('admin.revenue', compact(
            'revenueByPaymentMethod',
            'dailyRevenue',
            'monthlyRevenue',
            'stockDistribution'
        ));
    }

    // ========== SETTINGS ==========
    public function settings()
    {
        $storeSettings = Setting::where('group', 'store')->get()->pluck('value', 'key');
        $shippingSettings = Setting::where('group', 'shipping')->get()->pluck('value', 'key');
        $paymentSettings = Setting::where('group', 'payment')->get()->pluck('value', 'key');
        
        return view('admin.settings.index', compact('storeSettings', 'shippingSettings', 'paymentSettings'));
    }

    public function editStoreSettings()
    {
        $settings = Setting::where('group', 'store')->get();
        return view('admin.settings.store', compact('settings'));
    }

    public function updateStoreSettings(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'store_description' => 'required|string|max:500',
            'store_email' => 'required|email|max:255',
            'store_phone' => 'required|string|max:20',
            'store_address' => 'nullable|string|max:500',
            'store_website' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            Setting::set('store_name', $request->store_name);
            Setting::set('store_description', $request->store_description);
            Setting::set('store_email', $request->store_email);
            Setting::set('store_phone', $request->store_phone);
            Setting::set('store_address', $request->store_address ?? '');
            Setting::set('store_website', $request->store_website ?? '');

            DB::commit();

            return redirect()->route('admin.settings')
                ->with('success', 'Configuración de la tienda actualizada exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la configuración: ' . $e->getMessage());
        }
    }

    public function editShippingSettings()
    {
        $settings = Setting::where('group', 'shipping')->get();
        return view('admin.settings.shipping', compact('settings'));
    }

    public function updateShippingSettings(Request $request)
    {
        $request->validate([
            'shipping_enabled' => 'boolean',
            'shipping_cost' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            Setting::set('shipping_enabled', $request->has('shipping_enabled') ? '1' : '0');
            Setting::set('shipping_cost', $request->shipping_cost);
            Setting::set('free_shipping_threshold', $request->free_shipping_threshold ?? '0');

            DB::commit();

            return redirect()->route('admin.settings')
                ->with('success', 'Configuración de envíos actualizada exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la configuración: ' . $e->getMessage());
        }
    }

    public function editPaymentSettings()
    {
        $settings = Setting::where('group', 'payment')->get();
        return view('admin.settings.payment', compact('settings'));
    }

    public function updatePaymentSettings(Request $request)
    {
        $request->validate([
            'payment_credit_card' => 'boolean',
            'payment_debit_card' => 'boolean',
            'payment_yape' => 'boolean',
            'payment_cash' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            Setting::set('payment_credit_card', $request->has('payment_credit_card') ? '1' : '0');
            Setting::set('payment_debit_card', $request->has('payment_debit_card') ? '1' : '0');
            Setting::set('payment_yape', $request->has('payment_yape') ? '1' : '0');
            Setting::set('payment_cash', $request->has('payment_cash') ? '1' : '0');

            DB::commit();

            return redirect()->route('admin.settings')
                ->with('success', 'Configuración de métodos de pago actualizada exitosamente.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la configuración: ' . $e->getMessage());
        }
    }

    /**
     * ============================================
     * AUDITORÍA - Registro de Actividades
     * ============================================
     * 
     * Muestra el registro de todas las actividades realizadas por los administradores
     * SOLO ACCESIBLE PARA admin@digitalxpress.com
     */
    public function activityLogs(Request $request)
    {
        // Verificar que solo admin@digitalxpress.com pueda acceder
        if (Auth::user()->email !== 'admin@digitalxpress.com') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $query = ActivityLog::with('user')->latest();

        // Filtro por usuario
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filtro por acción
        if ($request->has('action') && $request->action) {
            $query->where('action', $request->action);
        }

        // Filtro por categoría
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filtro por fecha
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Búsqueda por descripción o nombre del modelo
        if ($request->has('search') && $request->search) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('description', 'like', $searchTerm)
                  ->orWhere('model_name', 'like', $searchTerm)
                  ->orWhere('user_name', 'like', $searchTerm);
            });
        }

        $activities = $query->paginate(50);

        // Estadísticas
        $totalActivities = ActivityLog::count();
        $todayActivities = ActivityLog::whereDate('created_at', today())->count();
        $thisWeekActivities = ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        
        // Actividades por categoría
        $activitiesByCategory = ActivityLog::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->get();

        // Usuarios activos (con más actividades)
        $activeUsers = ActivityLog::select('user_id', 'user_name', DB::raw('count(*) as total'))
            ->whereNotNull('user_id')
            ->groupBy('user_id', 'user_name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Obtener lista de usuarios para el filtro
        $users = User::where('email', 'like', '%@digitalxpress.com')
            ->orWhere('role', 'admin')
            ->orderBy('name')
            ->get();

        return view('admin.activity-logs.index', compact(
            'activities',
            'totalActivities',
            'todayActivities',
            'thisWeekActivities',
            'activitiesByCategory',
            'activeUsers',
            'users'
        ));
    }
}