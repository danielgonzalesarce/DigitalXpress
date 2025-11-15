@extends('layouts.admin')

@section('title', 'Gestión de Inventario')
@section('page-title', 'Inventario')
@section('page-subtitle', 'Gestiona el stock de tus productos • DigitalXpress')

@section('content')
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fas fa-cube"></i>
                </div>
                <div class="metric-value">{{ $totalProducts }}</div>
                <p class="metric-label">Total Productos</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #f0fdf4; color: #10b981;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="metric-value">{{ $inStockProducts }}</div>
                <p class="metric-label">Con Stock</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="metric-value">{{ $lowStockProducts }}</div>
                <p class="metric-label">Stock Bajo</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fee2e2; color: #ef4444;">
                    <i class="fas fa-box"></i>
                </div>
                <div class="metric-value">{{ $outOfStockProducts }}</div>
                <p class="metric-label">Sin Stock</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="orders-section mb-4">
        <form method="GET" action="{{ route('admin.inventory') }}" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Buscar por nombre o SKU..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="stock_status" class="form-select">
                    <option value="">Todos los productos</option>
                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>Con Stock</option>
                    <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Stock Bajo</option>
                    <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Sin Stock</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="orders-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Lista de Productos</h3>
            <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Registrar Movimiento
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>SKU</th>
                        <th>Stock</th>
                        <th>Precio</th>
                        <th>Valor en Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <strong>{{ $product->name }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $product->category->name }}</span>
                        </td>
                        <td>
                            <code class="text-muted">{{ $product->sku }}</code>
                        </td>
                        <td>
                            @if($product->stock_quantity > 0 && $product->stock_quantity < 10)
                                <span class="badge bg-warning">{{ $product->stock_quantity }} unidades</span>
                            @elseif($product->stock_quantity > 0)
                                <span class="badge bg-success">{{ $product->stock_quantity }} unidades</span>
                            @else
                                <span class="badge bg-danger">Sin Stock</span>
                            @endif
                        </td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>
                            <strong>${{ number_format($product->price * $product->stock_quantity, 2) }}</strong>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.inventory.edit', $product) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Editar Stock">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.inventory.destroy', $product) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de resetear el stock a 0?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Resetear Stock">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-cube fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No se encontraron productos</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="mt-4">
            {{ $products->links() }}
        </div>
        @endif
    </div>
@endsection
