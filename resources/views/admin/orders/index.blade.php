@extends('layouts.admin')

@section('title', 'Gestión de Pedidos')
@section('page-title', 'Pedidos')
@section('page-subtitle', 'Administra los pedidos del sistema • DigitalXpress')

@section('content')
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="metric-value">{{ $orders->total() }}</div>
                <p class="metric-label">Total Pedidos</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #f0fdf4; color: #10b981;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="metric-value">
                    {{ $orders->where('status', 'paid')->count() + $orders->where('status', 'delivered')->count() }}
                </div>
                <p class="metric-label">Completados</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="metric-value">{{ $orders->where('status', 'pending')->count() }}</div>
                <p class="metric-label">Pendientes</p>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="orders-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Lista de Pedidos</h3>
            <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Crear Pedido
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Método de Pago</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>
                            {{ $order->customer_name ?? ($order->user->name ?? 'Cliente') }}
                            <br>
                            <small class="text-muted">{{ $order->customer_email ?? ($order->user->email ?? 'N/A') }}</small>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            @php
                                $statusClass = 'bg-secondary';
                                $statusText = ucfirst($order->status);
                                if($order->status == 'paid' || $order->status == 'delivered') {
                                    $statusClass = 'bg-success';
                                } elseif($order->status == 'processing') {
                                    $statusClass = 'bg-primary';
                                } elseif($order->status == 'pending') {
                                    $statusClass = 'bg-warning';
                                } elseif($order->status == 'cancelled') {
                                    $statusClass = 'bg-danger';
                                }
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.order.details', $order) }}" 
                                   class="btn btn-sm btn-outline-info" 
                                   title="Ver Detalles">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.edit', $order) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.orders.destroy', $order) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar este pedido?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No hay pedidos</h4>
                            <p class="text-muted">Los pedidos aparecerán aquí una vez que los clientes realicen compras.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
@endsection

