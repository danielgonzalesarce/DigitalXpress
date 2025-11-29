@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Panel de administración profesional • DigitalXpress')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.css">
<style>
    .metric-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        position: relative;
        overflow: hidden;
    }
    
    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--card-color, #2563eb);
    }
    
    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    
    .metric-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }
    
    .metric-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }
    
    .metric-change {
        font-size: 0.75rem;
        font-weight: 600;
        color: #10b981;
        margin: 0;
    }
    
    .metric-change.negative {
        color: #ef4444;
    }
    
    .chart-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }
    
    .chart-card h5 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
    }
    
    .activity-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: background 0.2s;
    }
    
    .activity-item:hover {
        background: #f9fafb;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        font-size: 0.875rem;
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-content p {
        margin: 0;
        font-size: 0.875rem;
        color: #1f2937;
    }
    
    .activity-content small {
        color: #6b7280;
        font-size: 0.75rem;
    }
    
    .top-product-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        background: #f9fafb;
    }
    
    .top-product-rank {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
        margin-right: 0.75rem;
        background: #eff6ff;
        color: #2563eb;
    }
    
    .top-product-info {
        flex: 1;
    }
    
    .top-product-info h6 {
        margin: 0;
        font-size: 0.875rem;
        font-weight: 600;
        color: #1f2937;
    }
    
    .top-product-info small {
        color: #6b7280;
        font-size: 0.75rem;
    }
    
    .top-product-sales {
        font-weight: 700;
        color: #10b981;
        font-size: 0.875rem;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .section-header h5 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
    }
    
    .view-all-link {
        color: #2563eb;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .view-all-link:hover {
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
    <!-- Summary Cards - Primera Fila -->
    <div class="row g-4 mb-4">
        <!-- Total Productos -->
        <div class="col-lg-3 col-md-6">
            <div class="metric-card" style="--card-color: #2563eb;">
                <div class="metric-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fas fa-cube"></i>
                </div>
                <div class="metric-value">{{ $totalProducts }}</div>
                <p class="metric-label">Total Productos</p>
                @if($productsChange != 0)
                <p class="metric-change {{ $productsChange < 0 ? 'negative' : '' }}">
                    <i class="fas fa-arrow-{{ $productsChange > 0 ? 'up' : 'down' }}"></i>
                    {{ abs($productsChange) }} desde el mes pasado
                </p>
                @else
                <p class="metric-change" style="color: #6b7280;">
                    Sin cambios desde el mes pasado
                </p>
                @endif
            </div>
        </div>

        <!-- Ventas del Mes -->
        <div class="col-lg-3 col-md-6">
            <div class="metric-card" style="--card-color: #10b981;">
                <div class="metric-icon" style="background: #f0fdf4; color: #10b981;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="metric-value">S/ {{ number_format($revenueThisMonth ?? 0, 0, ',', '.') }}</div>
                <p class="metric-label">Ventas del Mes</p>
                @if(isset($revenueChangePercent) && $revenueChangePercent != 0)
                <p class="metric-change {{ $revenueChangePercent < 0 ? 'negative' : '' }}">
                    <i class="fas fa-arrow-{{ $revenueChangePercent > 0 ? 'up' : 'down' }}"></i>
                    {{ abs($revenueChangePercent) }}% vs mes anterior
                </p>
                @else
                <p class="metric-change" style="color: #6b7280;">
                    Sin cambios desde el mes pasado
                </p>
                @endif
            </div>
        </div>

        <!-- Total Pedidos -->
        <div class="col-lg-3 col-md-6">
            <div class="metric-card" style="--card-color: #8b5cf6;">
                <div class="metric-icon" style="background: #f5f3ff; color: #8b5cf6;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="metric-value">{{ $totalOrders ?? 0 }}</div>
                <p class="metric-label">Total Pedidos</p>
                @if(isset($ordersChange) && $ordersChange != 0)
                <p class="metric-change {{ $ordersChange < 0 ? 'negative' : '' }}">
                    <i class="fas fa-arrow-{{ $ordersChange > 0 ? 'up' : 'down' }}"></i>
                    {{ abs($ordersChange) }} este mes
                </p>
                @else
                <p class="metric-change" style="color: #6b7280;">
                    Sin cambios este mes
                </p>
                @endif
            </div>
        </div>

        <!-- Total Usuarios -->
        <div class="col-lg-3 col-md-6">
            <div class="metric-card" style="--card-color: #f59e0b;">
                <div class="metric-icon" style="background: #fffbeb; color: #f59e0b;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="metric-value">{{ $totalUsers ?? 0 }}</div>
                <p class="metric-label">Total Usuarios</p>
                @if(isset($newUsersThisMonth) && $newUsersThisMonth > 0)
                <p class="metric-change">
                    <i class="fas fa-user-plus"></i>
                    {{ $newUsersThisMonth }} nuevos este mes
                </p>
                @else
                <p class="metric-change" style="color: #6b7280;">
                    Sin nuevos usuarios este mes
                </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Summary Cards - Segunda Fila -->
    <div class="row g-4 mb-4">
        <!-- Valor Inventario -->
        <div class="col-lg-3 col-md-6">
            <div class="metric-card" style="--card-color: #10b981;">
                <div class="metric-icon" style="background: #f0fdf4; color: #10b981;">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="metric-value">S/ {{ number_format($inventoryValue, 0, ',', '.') }}</div>
                <p class="metric-label">Valor Inventario</p>
                @if($inventoryChangePercent != 0)
                <p class="metric-change {{ $inventoryChangePercent < 0 ? 'negative' : '' }}">
                    <i class="fas fa-arrow-{{ $inventoryChangePercent > 0 ? 'up' : 'down' }}"></i>
                    {{ abs($inventoryChangePercent) }}% desde el mes pasado
                </p>
                @else
                <p class="metric-change" style="color: #6b7280;">
                    Sin cambios desde el mes pasado
                </p>
                @endif
            </div>
        </div>

        <!-- Reparaciones -->
        <div class="col-lg-3 col-md-6">
            <div class="metric-card" style="--card-color: #3b82f6;">
                <div class="metric-icon" style="background: #eff6ff; color: #3b82f6;">
                    <i class="fas fa-wrench"></i>
                </div>
                <div class="metric-value">{{ $totalRepairs ?? 0 }}</div>
                <p class="metric-label">Total Reparaciones</p>
                <p class="metric-label" style="font-size: 0.75rem; margin-top: 0.25rem;">
                    {{ $pendingRepairs ?? 0 }} pendientes • {{ $inProgressRepairs ?? 0 }} en progreso
                </p>
            </div>
        </div>

        <!-- Stock Bajo -->
        <div class="col-lg-3 col-md-6">
            <div class="metric-card" style="--card-color: #f59e0b;">
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
            <div class="metric-card" style="--card-color: #ef4444;">
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
    <div class="alert-banner mb-4">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Atención:</strong> Tienes {{ $lowStockCount }} productos con stock bajo y {{ $outOfStockCount }} productos sin stock.
        </div>
        @if($lowStockCount > 0 && $outOfStockCount > 0)
            <a href="{{ route('admin.inventory', ['stock_status' => 'low_stock']) }}">Ver detalles</a>
        @elseif($lowStockCount > 0)
            <a href="{{ route('admin.inventory', ['stock_status' => 'low_stock']) }}">Ver detalles</a>
        @elseif($outOfStockCount > 0)
            <a href="{{ route('admin.inventory', ['stock_status' => 'out_of_stock']) }}">Ver detalles</a>
        @endif
    </div>
    @endif

    <!-- Gráficos y Estadísticas -->
    <div class="row g-4 mb-4">
        <!-- Gráfico de Ventas -->
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="section-header">
                    <h5><i class="fas fa-chart-area me-2"></i>Ventas por Mes (Últimos 6 Meses)</h5>
                </div>
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="section-header">
                    <h5><i class="fas fa-trophy me-2"></i>Productos Más Vendidos</h5>
                    <a href="{{ route('admin.products') }}" class="view-all-link">Ver todos</a>
                </div>
                @if(isset($topProducts) && $topProducts->count() > 0)
                <div>
                    @foreach($topProducts as $index => $item)
                    <div class="top-product-item">
                        <div class="top-product-rank">#{{ $index + 1 }}</div>
                        <div class="top-product-info">
                            <h6>{{ $item->product->name ?? 'Producto eliminado' }}</h6>
                            <small>{{ $item->product->category->name ?? 'Sin categoría' }}</small>
                        </div>
                        <div class="top-product-sales">
                            {{ $item->total_sold }} vendidos
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">No hay datos disponibles</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Pedidos Recientes y Actividad -->
    <div class="row g-4">
        <!-- Pedidos Recientes -->
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="section-header">
                    <h5><i class="fas fa-shopping-cart me-2"></i>Pedidos Recientes</h5>
                    <a href="{{ route('admin.orders') }}" class="view-all-link">Ver todos</a>
                </div>
                @if(isset($recentOrders) && $recentOrders->count() > 0)
                <div class="order-list">
                    @foreach($recentOrders as $order)
                    <div class="order-item">
                        <div class="order-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="order-info">
                            <h6>Pedido #{{ $order->id }}</h6>
                            <p>{{ $order->customer_name ?? ($order->user->name ?? 'Cliente') }} • {{ $order->created_at->format('d/m/Y H:i') }}</p>
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
                @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay pedidos recientes</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="col-lg-6">
            <div class="chart-card">
                <div class="section-header">
                    <h5><i class="fas fa-history me-2"></i>Actividad Reciente</h5>
                    @if(Auth::user()->email === 'admin@digitalxpress.com')
                    <a href="{{ route('admin.activity-logs') }}" class="view-all-link">Ver todos</a>
                    @endif
                </div>
                @if(isset($recentActivity) && $recentActivity->count() > 0)
                <div>
                    @foreach($recentActivity as $activity)
                    <div class="activity-item">
                        @php
                            $iconClass = 'fa-info-circle';
                            $iconColor = '#3b82f6';
                            if($activity->action == 'create') {
                                $iconClass = 'fa-plus-circle';
                                $iconColor = '#10b981';
                            } elseif($activity->action == 'update') {
                                $iconClass = 'fa-edit';
                                $iconColor = '#3b82f6';
                            } elseif($activity->action == 'delete') {
                                $iconClass = 'fa-trash';
                                $iconColor = '#ef4444';
                            }
                        @endphp
                        <div class="activity-icon" style="background: {{ $iconColor }}20; color: {{ $iconColor }};">
                            <i class="fas {{ $iconClass }}"></i>
                        </div>
                        <div class="activity-content">
                            <p>{{ $activity->user_name ?? 'Sistema' }} {{ strtolower($activity->description) }}</p>
                            <small>{{ $activity->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay actividad reciente</p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Gráfico de Ventas por Mes
    @if(isset($salesByMonth) && $salesByMonth->count() > 0)
    const salesCtx = document.getElementById('salesChart');
    if (salesCtx) {
        const salesData = @json($salesByMonth);
        const labels = salesData.map(item => {
            const [year, month] = item.month.split('-');
            const date = new Date(year, month - 1);
            return date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
        });
        const values = salesData.map(item => parseFloat(item.total));
        
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventas (S/)',
                    data: values,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                return 'S/ ' + context.parsed.y.toLocaleString('es-PE');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'S/ ' + value.toLocaleString('es-PE');
                            }
                        },
                        grid: {
                            color: '#e5e7eb'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    @endif
</script>
@endpush
