@extends('layouts.admin')

@section('title', 'Crear Pedido')
@section('page-title', 'Crear Pedido')
@section('page-subtitle', 'Agregar un nuevo pedido al sistema • DigitalXpress')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="orders-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Nuevo Pedido</h3>
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

                <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
                    @csrf

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Información del Cliente</h5>

                            <div class="mb-3">
                                <label for="user_id" class="form-label">Cliente <span class="text-danger">*</span></label>
                                <select class="form-select @error('user_id') is-invalid @enderror" 
                                        id="user_id" name="user_id" required>
                                    <option value="">Selecciona un cliente</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                                               id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                        @error('customer_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="customer_email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                               id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required>
                                        @error('customer_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="customer_phone" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" 
                                               id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}">
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
                            
                            <div id="productsContainer">
                                <div class="product-row mb-3 p-3 border rounded">
                                    <div class="row g-3">
                                        <div class="col-md-5">
                                            <label class="form-label">Producto <span class="text-danger">*</span></label>
                                            <select class="form-select product-select" name="products[0][product_id]" required>
                                                <option value="">Selecciona un producto</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                        {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Cantidad <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control quantity-input" name="products[0][quantity]" 
                                                   value="1" min="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Precio Unitario <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control price-input" name="products[0][price]" 
                                                   step="0.01" min="0" required>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-danger w-100 remove-product" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-primary" id="addProduct">
                                <i class="fas fa-plus me-2"></i> Agregar Producto
                            </button>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Direcciones</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Dirección de Facturación</h6>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="billing_address[street]" 
                                               placeholder="Calle" value="{{ old('billing_address.street') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="billing_address[city]" 
                                               placeholder="Ciudad" value="{{ old('billing_address.city') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="billing_address[state]" 
                                               placeholder="Estado/Provincia" value="{{ old('billing_address.state') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="billing_address[zip]" 
                                               placeholder="Código Postal" value="{{ old('billing_address.zip') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6>Dirección de Envío</h6>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="shipping_address[street]" 
                                               placeholder="Calle" value="{{ old('shipping_address.street') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="shipping_address[city]" 
                                               placeholder="Ciudad" value="{{ old('shipping_address.city') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="shipping_address[state]" 
                                               placeholder="Estado/Provincia" value="{{ old('shipping_address.state') }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" class="form-control" name="shipping_address[zip]" 
                                               placeholder="Código Postal" value="{{ old('shipping_address.zip') }}" required>
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
                                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Procesando</option>
                                            <option value="shipped" {{ old('status') == 'shipped' ? 'selected' : '' }}>Enviado</option>
                                            <option value="delivered" {{ old('status') == 'delivered' ? 'selected' : '' }}>Entregado</option>
                                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="payment_status" class="form-label">Estado de Pago <span class="text-danger">*</span></label>
                                        <select class="form-select" id="payment_status" name="payment_status" required>
                                            <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Pagado</option>
                                            <option value="failed" {{ old('payment_status') == 'failed' ? 'selected' : '' }}>Fallido</option>
                                            <option value="refunded" {{ old('payment_status') == 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Método de Pago</label>
                                        <input type="text" class="form-control" id="payment_method" name="payment_method" 
                                               value="{{ old('payment_method') }}" placeholder="Ej: Tarjeta de crédito">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="subtotal" class="form-label">Subtotal <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="subtotal" name="subtotal" 
                                               value="{{ old('subtotal', 0) }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="tax_amount" class="form-label">Impuestos</label>
                                        <input type="number" class="form-control" id="tax_amount" name="tax_amount" 
                                               value="{{ old('tax_amount', 0) }}" step="0.01" min="0">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="shipping_amount" class="form-label">Envío</label>
                                        <input type="number" class="form-control" id="shipping_amount" name="shipping_amount" 
                                               value="{{ old('shipping_amount', 0) }}" step="0.01" min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="total_amount" class="form-label">Total <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="total_amount" name="total_amount" 
                                       value="{{ old('total_amount', 0) }}" step="0.01" min="0" required>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notas</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Crear Pedido
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let productIndex = 1;
        
        // Agregar producto
        document.getElementById('addProduct').addEventListener('click', function() {
            const container = document.getElementById('productsContainer');
            const newRow = container.firstElementChild.cloneNode(true);
            
            // Actualizar índices
            newRow.querySelectorAll('select, input').forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/\[0\]/, '[' + productIndex + ']');
                }
                if (input.classList.contains('product-select')) {
                    input.value = '';
                }
                if (input.classList.contains('quantity-input')) {
                    input.value = 1;
                }
                if (input.classList.contains('price-input')) {
                    input.value = '';
                }
            });
            
            // Mostrar botón eliminar
            newRow.querySelector('.remove-product').style.display = 'block';
            
            container.appendChild(newRow);
            productIndex++;
        });
        
        // Eliminar producto
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-product')) {
                const row = e.target.closest('.product-row');
                if (document.querySelectorAll('.product-row').length > 1) {
                    row.remove();
                }
            }
        });
        
        // Actualizar precio cuando se selecciona producto
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const price = selectedOption.dataset.price || 0;
                const row = e.target.closest('.product-row');
                row.querySelector('.price-input').value = price;
                calculateTotal();
            }
        });
        
        // Calcular total cuando cambian cantidades o precios
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity-input') || e.target.classList.contains('price-input')) {
                calculateTotal();
            }
        });
        
        // Calcular total cuando cambian impuestos o envío
        document.getElementById('tax_amount').addEventListener('input', calculateTotal);
        document.getElementById('shipping_amount').addEventListener('input', calculateTotal);
        
        function calculateTotal() {
            let subtotal = 0;
            document.querySelectorAll('.product-row').forEach(row => {
                const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                subtotal += quantity * price;
            });
            
            const tax = parseFloat(document.getElementById('tax_amount').value) || 0;
            const shipping = parseFloat(document.getElementById('shipping_amount').value) || 0;
            const total = subtotal + tax + shipping;
            
            document.getElementById('subtotal').value = subtotal.toFixed(2);
            document.getElementById('total_amount').value = total.toFixed(2);
        }
    });
    </script>
@endsection

