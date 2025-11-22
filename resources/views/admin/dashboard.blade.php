@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Panel de administración profesional • DigitalXpress')

@section('content')
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Productos -->
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fas fa-cube"></i>
                </div>
                <div class="metric-value">{{ $totalProducts }}</div>
                <p class="metric-label">Total Productos</p>
                @if($productsChange != 0)
                <p class="metric-change">
                    {{ $productsChange > 0 ? '+' : '' }}{{ $productsChange }} desde el mes pasado
                </p>
                @else
                <p class="metric-change" style="color: #6b7280;">
                    Sin cambios desde el mes pasado
                </p>
                @endif
            </div>
        </div>

        <!-- Valor Inventario -->
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #f0fdf4; color: #10b981;">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="metric-value">S/ {{ number_format($inventoryValue, 0, ',', '.') }}</div>
                <p class="metric-label">Valor Inventario</p>
                @if($inventoryChangePercent != 0)
                <p class="metric-change">
                    {{ $inventoryChangePercent > 0 ? '+' : '' }}{{ $inventoryChangePercent }}% desde el mes pasado
                </p>
                @else
                <p class="metric-change" style="color: #6b7280;">
                    Sin cambios desde el mes pasado
                </p>
                @endif
            </div>
        </div>

        <!-- Stock Bajo -->
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="metric-value">{{ $lowStockCount }}</div>
                <p class="metric-label">Stock Bajo</p>
                <p class="metric-label" style="font-size: 0.75rem; margin-top: 0.25rem;">
                    Productos con menos de 10 unidades
                </p>
            </div>
        </div>

        <!-- Sin Stock -->
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fee2e2; color: #ef4444;">
                    <i class="fas fa-box"></i>
                </div>
                <div class="metric-value">{{ $outOfStockCount }}</div>
                <p class="metric-label">Sin Stock</p>
                <p class="metric-label" style="font-size: 0.75rem; margin-top: 0.25rem;">
                    Productos agotados
                </p>
            </div>
        </div>
    </div>

    <!-- Alert Banner -->
    @if($lowStockCount > 0 || $outOfStockCount > 0)
    <div class="alert-banner">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Atención:</strong> Tienes {{ $lowStockCount }} productos con stock bajo y {{ $outOfStockCount }} productos sin stock.
        </div>
        @if($lowStockCount > 0 && $outOfStockCount > 0)
            {{-- Si hay ambos, mostrar todos los productos con problemas de stock --}}
            <a href="{{ route('admin.inventory', ['stock_status' => 'low_stock']) }}">Ver detalles</a>
        @elseif($lowStockCount > 0)
            <a href="{{ route('admin.inventory', ['stock_status' => 'low_stock']) }}">Ver detalles</a>
        @elseif($outOfStockCount > 0)
            <a href="{{ route('admin.inventory', ['stock_status' => 'out_of_stock']) }}">Ver detalles</a>
        @endif
    </div>
    @endif

    <!-- Recent Orders -->
    @if($recentOrders->count() > 0)
    <div class="orders-section">
        <h3>Pedidos Recientes</h3>
        <div class="order-list">
            @foreach($recentOrders as $order)
            <div class="order-item">
                <div class="order-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="order-info">
                    <h6>Pedido #{{ $order->id }}</h6>
                    <p>{{ $order->customer_name ?? ($order->user->name ?? 'Cliente') }} • {{ $order->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="order-total">S/ {{ number_format($order->total_amount, 2, ',', '.') }}</div>
                <div>
                    @php
                        $statusClass = 'pending';
                        $statusText = 'Pendiente';
                        if($order->status == 'processing') {
                            $statusClass = 'processing';
                            $statusText = 'Procesando';
                        } elseif($order->status == 'paid' || $order->status == 'delivered') {
                            $statusClass = 'completed';
                            $statusText = 'Completado';
                        }
                    @endphp
                    <span class="order-status {{ $statusClass }}">{{ $statusText }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="orders-section">
        <h3>Pedidos Recientes</h3>
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <p class="text-muted">No hay pedidos recientes</p>
        </div>
    </div>
    @endif
@endsection
