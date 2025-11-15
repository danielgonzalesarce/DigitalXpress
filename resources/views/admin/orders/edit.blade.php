@extends('layouts.admin')

@section('title', 'Editar Pedido')
@section('page-title', 'Editar Pedido')
@section('page-subtitle', 'Modificar información del pedido • DigitalXpress')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="orders-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Editar Pedido: {{ $order->order_number }}</h3>
                    <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Volver
                    </a>
                </div>

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Información del Cliente</h5>

                            <div class="mb-3">
                                <label for="user_id" class="form-label">Cliente <span class="text-danger">*</span></label>
                                <select class="form-select @error('user_id') is-invalid @enderror" 
                                        id="user_id" name="user_id" required>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $order->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="customer_name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                               id="customer_name" name="customer_name" 
                                               value="{{ old('customer_name', $order->customer_name) }}" required>
                                        @error('customer_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                               id="customer_email" name="customer_email" 
                                               value="{{ old('customer_email', $order->customer_email) }}" required>
                                        @error('customer_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="customer_phone" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                                               id="customer_phone" name="customer_phone" 
                                               value="{{ old('customer_phone', $order->customer_phone) }}">
                                        @error('customer_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Productos del Pedido</h5>
                            
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
                                                <strong>{{ $item->product->name }}</strong>
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                            <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted">Los productos del pedido no se pueden modificar. Crea un nuevo pedido para cambiar los productos.</small>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Direcciones</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Dirección de Facturación</h6>
                                    @php
                                        $billing = is_array($order->billing_address) ? $order->billing_address : json_decode($order->billing_address, true);
                                    @endphp
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="billing_address[street]" 
                                               placeholder="Calle" value="{{ old('billing_address.street', $billing['street'] ?? '') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="billing_address[city]" 
                                               placeholder="Ciudad" value="{{ old('billing_address.city', $billing['city'] ?? '') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="billing_address[state]" 
                                               placeholder="Estado/Provincia" value="{{ old('billing_address.state', $billing['state'] ?? '') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="billing_address[zip]" 
                                               placeholder="Código Postal" value="{{ old('billing_address.zip', $billing['zip'] ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Dirección de Envío</h6>
                                    @php
                                        $shipping = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
                                    @endphp
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="shipping_address[street]" 
                                               placeholder="Calle" value="{{ old('shipping_address.street', $shipping['street'] ?? '') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="shipping_address[city]" 
                                               placeholder="Ciudad" value="{{ old('shipping_address.city', $shipping['city'] ?? '') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="shipping_address[state]" 
                                               placeholder="Estado/Provincia" value="{{ old('shipping_address.state', $shipping['state'] ?? '') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="shipping_address[zip]" 
                                               placeholder="Código Postal" value="{{ old('shipping_address.zip', $shipping['zip'] ?? '') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Información del Pedido</h5>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Estado <span class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>Procesando</option>
                                            <option value="shipped" {{ old('status', $order->status) == 'shipped' ? 'selected' : '' }}>Enviado</option>
                                            <option value="delivered" {{ old('status', $order->status) == 'delivered' ? 'selected' : '' }}>Entregado</option>
                                            <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="payment_status" class="form-label">Estado de Pago <span class="text-danger">*</span></label>
                                        <select class="form-select" id="payment_status" name="payment_status" required>
                                            <option value="pending" {{ old('payment_status', $order->payment_status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="paid" {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>Pagado</option>
                                            <option value="failed" {{ old('payment_status', $order->payment_status) == 'failed' ? 'selected' : '' }}>Fallido</option>
                                            <option value="refunded" {{ old('payment_status', $order->payment_status) == 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Método de Pago</label>
                                        <input type="text" class="form-control" id="payment_method" name="payment_method" 
                                               value="{{ old('payment_method', $order->payment_method) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="subtotal" class="form-label">Subtotal <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="subtotal" name="subtotal" 
                                               value="{{ old('subtotal', $order->subtotal) }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tax_amount" class="form-label">Impuestos</label>
                                        <input type="number" class="form-control" id="tax_amount" name="tax_amount" 
                                               value="{{ old('tax_amount', $order->tax_amount) }}" step="0.01" min="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="shipping_amount" class="form-label">Envío</label>
                                        <input type="number" class="form-control" id="shipping_amount" name="shipping_amount" 
                                               value="{{ old('shipping_amount', $order->shipping_amount) }}" step="0.01" min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="total_amount" class="form-label">Total <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="total_amount" name="total_amount" 
                                       value="{{ old('total_amount', $order->total_amount) }}" step="0.01" min="0" required>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notas</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Actualizar Pedido
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

