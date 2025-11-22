@extends('layouts.admin')

@section('title', 'Análisis')
@section('page-title', 'Análisis')
@section('page-subtitle', 'Reportes de ingresos y ventas • DigitalXpress')

@section('content')
    <!-- Ingresos por Método de Pago -->
    <div class="orders-section mb-4">
        <h3 class="mb-4">Ingresos por Método de Pago</h3>
        <div class="row g-4">
            @forelse($revenueByPaymentMethod as $method)
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small text-uppercase">{{ ucfirst(str_replace('_', ' ', $method->payment_method)) }}</p>
                                <h4 class="mb-0">${{ number_format($method->revenue, 2) }}</h4>
                                <small class="text-muted">{{ $method->orders_count }} órdenes</small>
                            </div>
                            <div class="fs-2">
                                @if($method->payment_method === 'credit_card' || $method->payment_method === 'debit_card')
                                    <i class="fas fa-credit-card text-primary"></i>
                                @elseif($method->payment_method === 'yape')
                                    <i class="fas fa-mobile-alt text-success"></i>
                                @else
                                    <i class="fas fa-money-bill-wave text-info"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No hay datos de ingresos disponibles.
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Ingresos Diarios (Últimos 30 días) -->
    @if($dailyRevenue->count() > 0)
    <div class="orders-section mb-4">
        <h3 class="mb-4">Ingresos Diarios (Últimos 30 días)</h3>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Órdenes</th>
                        <th>Ingresos</th>
                        <th>Promedio por Orden</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyRevenue->reverse() as $day)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($day->date)->format('d/m/Y') }}</td>
                        <td>{{ $day->orders_count }}</td>
                        <td class="text-success fw-bold">${{ number_format($day->revenue, 2) }}</td>
                        <td class="text-muted">${{ number_format($day->revenue / max($day->orders_count, 1), 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Ingresos Mensuales -->
    @if($monthlyRevenue->count() > 0)
    <div class="orders-section mb-4">
        <h3 class="mb-4">Ingresos Mensuales</h3>
        <div class="row g-4">
            @foreach($monthlyRevenue as $month)
            <div class="col-lg-4 col-md-6">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">
                                    @php
                                        try {
                                            $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $month->month);
                                            echo $monthDate->locale('es')->format('F Y');
                                        } catch (\Exception $e) {
                                            echo $month->month;
                                        }
                                    @endphp
                                </p>
                                <h3 class="mb-0">${{ number_format($month->revenue, 2) }}</h3>
                            </div>
                            <div class="text-primary fs-2">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Resumen de Ingresos -->
    @if($revenueByPaymentMethod->count() > 0)
    <div class="orders-section">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Resumen Total de Ingresos</h3>
                <div class="row text-center">
                    <div class="col-md-4">
                        <p class="mb-1 opacity-75">Total de Órdenes</p>
                        <h2 class="mb-0">{{ $revenueByPaymentMethod->sum('orders_count') }}</h2>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 opacity-75">Ingresos Totales</p>
                        <h2 class="mb-0">${{ number_format($revenueByPaymentMethod->sum('revenue'), 2) }}</h2>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-1 opacity-75">Promedio por Orden</p>
                        <h2 class="mb-0">${{ number_format($revenueByPaymentMethod->sum('revenue') / max($revenueByPaymentMethod->sum('orders_count'), 1), 2) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="orders-section">
        <div class="text-center py-5">
            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No hay datos de ingresos</h4>
            <p class="text-muted">Los ingresos aparecerán aquí una vez que haya pedidos pagados.</p>
        </div>
    </div>
    @endif

    <!-- Distribución de Stock - Gráfica de Pastel -->
    @if(array_sum($stockDistribution) > 0)
    <div class="orders-section mt-4">
        <h3 class="mb-4">Distribución de Stock</h3>
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <canvas id="stockDistributionChart"></canvas>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfica de Distribución de Stock (Pastel)
    @if(array_sum($stockDistribution) > 0)
    const stockDistributionCtx = document.getElementById('stockDistributionChart');
    if (stockDistributionCtx) {
        const stockDistributionData = {
            labels: ['En Stock', 'Stock Bajo', 'Sin Stock'],
            datasets: [{
                data: [
                    {{ $stockDistribution['in_stock'] }},
                    {{ $stockDistribution['low_stock'] }},
                    {{ $stockDistribution['out_of_stock'] }}
                ],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderColor: [
                    'rgba(16, 185, 129, 1)',
                    'rgba(245, 158, 11, 1)',
                    'rgba(239, 68, 68, 1)'
                ],
                borderWidth: 2
            }]
        };

        new Chart(stockDistributionCtx, {
            type: 'pie',
            data: stockDistributionData,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' productos (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
    @endif
});
</script>
@endpush