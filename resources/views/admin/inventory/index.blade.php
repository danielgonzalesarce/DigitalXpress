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
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="metric-value">S/ {{ number_format($totalValue, 0, ',', '.') }}</div>
                <p class="metric-label">Valor Total</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="metric-value">{{ $lowStockCount }}</div>
                <p class="metric-label">Stock Bajo</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fee2e2; color: #ef4444;">
                    <i class="fas fa-box"></i>
                </div>
                <div class="metric-value">{{ $outOfStockCount }}</div>
                <p class="metric-label">Sin Stock</p>
            </div>
        </div>
    </div>

    <!-- Stock Bajo -->
    @if($lowStockProducts->count() > 0)
    <div class="orders-section mb-4">
        <h3 class="mb-4">
            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
            Productos con Stock Bajo (menos de 10 unidades)
        </h3>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock Actual</th>
                        <th>Precio</th>
                        <th>Valor en Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <strong>{{ $product->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary">{{ $product->category->name }}</span></td>
                        <td>
                            <span class="badge bg-warning">{{ $product->stock_quantity }} unidades</span>
                        </td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>${{ number_format($product->price * $product->stock_quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Sin Stock -->
    @if($outOfStockProducts->count() > 0)
    <div class="orders-section">
        <h3 class="mb-4">
            <i class="fas fa-box text-danger me-2"></i>
            Productos Sin Stock
        </h3>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Estado</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($outOfStockProducts as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <strong>{{ $product->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary">{{ $product->category->name }}</span></td>
                        <td>
                            <span class="badge bg-danger">Sin Stock</span>
                        </td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($lowStockProducts->count() == 0 && $outOfStockProducts->count() == 0)
    <div class="orders-section">
        <div class="text-center py-5">
            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
            <h4 class="text-muted">¡Excelente!</h4>
            <p class="text-muted">Todos tus productos tienen stock suficiente.</p>
        </div>
    </div>
    @endif
@endsection

