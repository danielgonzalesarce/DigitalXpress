@extends('layouts.admin')

@section('title', 'Detalles del Pedido')
@section('page-title', 'Detalles del Pedido #' . $order->id)
@section('page-subtitle', 'Información completa del pedido • DigitalXpress')

@section('content')
    <div class="row g-4">
        <!-- Order Info -->
        <div class="col-lg-8">
            <div class="orders-section mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Información del Pedido</h3>
                    <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Volver a Pedidos
                    </a>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>ID del Pedido:</strong> #{{ $order->id }}</p>
                                <p class="mb-2"><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                <p class="mb-2"><strong>Estado:</strong> 
                                    @php
                                        $statusClass = 'bg-secondary';
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
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Método de Pago:</strong> 
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</span>
                                </p>
                                <p class="mb-2"><strong>Estado de Pago:</strong> 
                                    <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->payment_status ?? 'Pendiente') }}
                                    </span>
                                </p>
                                <p class="mb-2"><strong>Total:</strong> 
                                    <span class="h5 text-primary">${{ number_format($order->total_amount, 2) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Productos del Pedido</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio Unitario</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $item->product->image_url ?? 'https://via.placeholder.com/50' }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                                <div>
                                                    <strong>{{ $item->product->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $item->product->category->name ?? 'Sin categoría' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td class="fw-bold">${{ number_format($item->quantity * $item->price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td class="fw-bold text-primary">${{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Info & Actions -->
        <div class="col-lg-4">
            <div class="orders-section mb-4">
                <h5 class="mb-3">Cliente</h5>
                <div class="card">
                    <div class="card-body">
                        <p class="mb-2"><strong>Nombre:</strong><br>{{ $order->customer_name ?? ($order->user->name ?? 'N/A') }}</p>
                        <p class="mb-2"><strong>Email:</strong><br>{{ $order->customer_email ?? ($order->user->email ?? 'N/A') }}</p>
                        @if($order->customer_phone)
                        <p class="mb-0"><strong>Teléfono:</strong><br>{{ $order->customer_phone }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="orders-section">
                <h5 class="mb-3">Actualizar Estado</h5>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.order.update-status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="status" class="form-label">Estado del Pedido</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Procesando</option>
                                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Pagado</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Enviado</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Entregado</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save me-2"></i> Actualizar Estado
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

