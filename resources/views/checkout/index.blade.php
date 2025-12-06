@extends('layouts.app')

@section('title', 'Finalizar Compra - DigitalXpress')

@section('content')
<div class="checkout-page">
    <div class="container py-5">
        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-primary mb-3">Finalizar Compra</h1>
            <p class="lead text-muted">Completa tu pago de forma segura con el método de tu preferencia</p>
        </div>

        <div class="row">
            <!-- Resumen del Pedido - Columna Izquierda -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-shopping-cart me-2"></i>Resumen del Pedido
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($cartItems->count() > 0)
                            <!-- Items del Carrito -->
                            <div class="order-items mb-4">
                                @foreach($cartItems as $item)
                                    <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                        <img src="{{ $item->product->image_url }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="rounded me-3" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold">{{ $item->product->name }}</h6>
                                            <small class="text-muted">{{ $item->product->category->name ?? 'Categoría' }}</small>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <span class="text-muted">Cantidad: {{ $item->quantity }}</span>
                                                <span class="fw-bold text-primary">${{ number_format($item->price, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Totales -->
                            <div class="totals-section">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span class="fw-bold">${{ number_format($total, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Envío:</span>
                                    <span class="text-success fw-bold">Gratis</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Impuestos:</span>
                                    <span>$0.00</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="h5 fw-bold">Total:</span>
                                    <span class="h5 fw-bold text-primary">${{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No hay productos en el carrito</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Formulario de Pago - Columna Derecha -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                            @csrf
                            
                            <!-- Información del Cliente -->
                            <div class="mb-4">
                                <h5 class="fw-bold mb-3">Información de Envío</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_name" class="form-label fw-bold">Nombre Completo *</label>
                                        <input type="text" 
                                               class="form-control @error('customer_name') is-invalid @enderror" 
                                               id="customer_name" 
                                               name="customer_name" 
                                               value="{{ Auth::check() ? Auth::user()->name : old('customer_name') }}"
                                               required>
                                        @error('customer_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_email" class="form-label fw-bold">Correo Electrónico *</label>
                                        <input type="email" 
                                               class="form-control @error('customer_email') is-invalid @enderror" 
                                               id="customer_email" 
                                               name="customer_email" 
                                               value="{{ Auth::check() ? Auth::user()->email : old('customer_email') }}"
                                               required>
                                        @error('customer_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_phone" class="form-label fw-bold">Teléfono *</label>
                                        <input type="tel" 
                                               class="form-control @error('customer_phone') is-invalid @enderror" 
                                               id="customer_phone" 
                                               name="customer_phone" 
                                               value="{{ old('customer_phone') }}"
                                               required>
                                        @error('customer_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-4">
                                        <label for="shipping_address" class="form-label fw-bold">Dirección de Envío *</label>
                                        <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                                  id="shipping_address" 
                                                  name="shipping_address" 
                                                  rows="3" 
                                                  required>{{ old('shipping_address') }}</textarea>
                                        @error('shipping_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Método de Pago -->
                            <div class="mb-4">
                                <h5 class="fw-bold mb-3">Método de Pago</h5>
                                
                                <!-- Tabs de Métodos de Pago -->
                                <ul class="nav nav-pills nav-fill mb-4" id="paymentTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" 
                                                id="card-tab" 
                                                data-bs-toggle="pill" 
                                                data-bs-target="#card-pane" 
                                                type="button" 
                                                role="tab">
                                            <i class="fas fa-credit-card me-2"></i>Tarjeta
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" 
                                                id="yape-tab" 
                                                data-bs-toggle="pill" 
                                                data-bs-target="#yape-pane" 
                                                type="button" 
                                                role="tab">
                                            <i class="fas fa-mobile-alt me-2"></i>Yape
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="paymentTabContent">
                                    <!-- Pago con Tarjeta -->
                                    <div class="tab-pane fade show active" id="card-pane" role="tabpanel">
                                        <div class="payment-card-form">
                                            <h6 class="fw-bold mb-3">Pago con Tarjeta</h6>
                                            
                                            <div class="mb-3">
                                                <label for="cardholder_name" class="form-label fw-bold">Nombre del titular *</label>
                                                <input type="text" 
                                                       class="form-control @error('cardholder_name') is-invalid @enderror" 
                                                       id="cardholder_name" 
                                                       name="cardholder_name" 
                                                       value="{{ old('cardholder_name') }}"
                                                       placeholder="Como aparece en la tarjeta">
                                                @error('cardholder_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="card_number" class="form-label fw-bold">Número de tarjeta *</label>
                                                <input type="text" 
                                                       class="form-control @error('card_number') is-invalid @enderror" 
                                                       id="card_number" 
                                                       name="card_number" 
                                                       value="{{ old('card_number') }}"
                                                       placeholder="1234 5678 9012 3456"
                                                       maxlength="19">
                                                @error('card_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="card_expiry_month" class="form-label fw-bold">Vencimiento *</label>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <select class="form-select @error('card_expiry_month') is-invalid @enderror" 
                                                                    id="card_expiry_month" 
                                                                    name="card_expiry_month">
                                                                <option value="">MM</option>
                                                                @for($i = 1; $i <= 12; $i++)
                                                                    <option value="{{ $i }}" {{ old('card_expiry_month') == $i ? 'selected' : '' }}>
                                                                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-6">
                                                            <select class="form-select @error('card_expiry_year') is-invalid @enderror" 
                                                                    id="card_expiry_year" 
                                                                    name="card_expiry_year">
                                                                <option value="">YY</option>
                                                                @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                                                    <option value="{{ $i }}" {{ old('card_expiry_year') == $i ? 'selected' : '' }}>
                                                                        {{ $i }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="card_cvv" class="form-label fw-bold">CVV *</label>
                                                    <input type="text" 
                                                           class="form-control @error('card_cvv') is-invalid @enderror" 
                                                           id="card_cvv" 
                                                           name="card_cvv" 
                                                           value="{{ old('card_cvv') }}"
                                                           placeholder="123"
                                                           maxlength="3">
                                                    @error('card_cvv')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Demo Badge -->
                                            <div class="alert alert-info d-flex align-items-center mb-4">
                                                <button type="button" class="btn btn-outline-primary btn-sm me-3">DEMO</button>
                                                <small class="mb-0">Usa cualquier número de tarjeta para simular el pago</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pago con Yape -->
                                    <div class="tab-pane fade" id="yape-pane" role="tabpanel">
                                        <div class="yape-form">
                                            <h6 class="fw-bold mb-3">Pago con Yape</h6>
                                            
                                            <div class="mb-3">
                                                <label for="yape_phone" class="form-label fw-bold">Número de Teléfono *</label>
                                                <input type="text" 
                                                       class="form-control @error('yape_phone') is-invalid @enderror" 
                                                       id="yape_phone" 
                                                       name="yape_phone" 
                                                       value="{{ old('yape_phone') }}"
                                                       placeholder="912345678">
                                                @error('yape_phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="alert alert-success d-flex align-items-center">
                                                <i class="fas fa-mobile-alt me-3"></i>
                                                <small class="mb-0">Pago instantáneo y seguro con Yape</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <a href="{{ route('cart.index') }}" class="btn btn-enhanced btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-enhanced btn-success btn-lg px-5">
                                    <i class="fas fa-credit-card me-2"></i>Pagar ${{ number_format($total, 2) }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================
   ESTILOS MEJORADOS DEL CHECKOUT
   ============================================ */

:root {
    --primary-color: #1e3a8a;
    --secondary-color: #3b82f6;
    --accent-color: #10b981;
    --text-dark: #1f2937;
    --text-light: #6b7280;
    --bg-light: #f9fafb;
}

.checkout-page {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

/* Header Mejorado */
.checkout-page .text-center {
    margin-bottom: 3rem;
}

.checkout-page .display-4 {
    font-size: 2.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, #1e3a8a, #3b82f6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1rem;
}

.checkout-page .lead {
    font-size: 1.15rem;
    color: #6b7280;
    font-weight: 500;
}

/* Cards Mejoradas */
.card {
    border: 2px solid #e5e7eb;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: white;
}

.card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    transform: translateY(-4px);
}

.card-header {
    border-radius: 0 !important;
    border: none;
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%) !important;
    padding: 1.5rem;
}

.card-header h5 {
    color: white;
    font-weight: 700;
    font-size: 1.25rem;
    margin: 0;
    display: flex;
    align-items: center;
}

/* Items del Pedido */
.order-items {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.order-items::-webkit-scrollbar {
    width: 6px;
}

.order-items::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.order-items::-webkit-scrollbar-thumb {
    background: #3b82f6;
    border-radius: 10px;
}

.order-items .border {
    border-radius: 12px !important;
    border: 2px solid #e5e7eb !important;
    transition: all 0.3s ease;
    background: white;
    padding: 1rem !important;
}

.order-items .border:hover {
    border-color: #3b82f6 !important;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.15);
    transform: translateX(4px);
}

.order-items img {
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.order-items h6 {
    color: #1f2937;
    font-weight: 700;
    font-size: 1rem;
}

/* Sección de Totales */
.totals-section {
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    padding: 1.75rem;
    border-radius: 15px;
    border: 2px solid #10b981;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1);
}

.totals-section .d-flex {
    padding: 0.5rem 0;
}

.totals-section hr {
    margin: 1rem 0;
    border-color: #10b981;
    opacity: 0.3;
}

.totals-section .h5 {
    font-size: 1.5rem;
    font-weight: 800;
    color: #1f2937;
}

.totals-section .text-primary {
    font-size: 1.75rem;
    font-weight: 800;
    background: linear-gradient(135deg, #10b981, #059669);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Formulario de Pago */
.card-body {
    padding: 2rem;
}

.form-label {
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-control, .form-select {
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    padding: 0.875rem 1.25rem;
    transition: all 0.3s ease;
    font-weight: 500;
    font-size: 1rem;
    background: white;
}

.form-control:focus, .form-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.15);
    background: #fafbfc;
}

.form-control::placeholder {
    color: #9ca3af;
}

/* Tabs de Métodos de Pago */
.nav-pills {
    background: #f9fafb;
    padding: 0.5rem;
    border-radius: 15px;
    gap: 0.5rem;
}

.nav-pills .nav-link {
    border-radius: 12px;
    border: 2px solid transparent;
    background: white;
    color: #6b7280;
    font-weight: 700;
    padding: 1rem 1.5rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-pills .nav-link i {
    font-size: 1.25rem;
    margin-right: 0.5rem;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #1e3a8a, #3b82f6);
    border-color: #1e3a8a;
    color: white;
    box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
    transform: translateY(-2px);
}

.nav-pills .nav-link:hover:not(.active) {
    border-color: #3b82f6;
    color: #3b82f6;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

/* Formularios de Pago */
.payment-card-form, .yape-form {
    background: linear-gradient(135deg, #f9fafb, #f3f4f6);
    padding: 2rem;
    border-radius: 15px;
    border: 2px solid #e5e7eb;
    margin-top: 1rem;
}

.payment-card-form h6, .yape-form h6 {
    color: #1f2937;
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e5e7eb;
}

/* Botones Mejorados */
.btn {
    border-radius: 12px;
    padding: 0.875rem 2rem;
    font-weight: 700;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
    color: white;
}

.btn-success:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    background: linear-gradient(135deg, #059669, #10b981);
}

.btn-success:active {
    transform: translateY(-1px);
}

.btn-outline-secondary {
    border: 2px solid #6b7280;
    color: #6b7280;
    background: white;
}

.btn-outline-secondary:hover {
    background: #6b7280;
    border-color: #6b7280;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
}

.btn-lg {
    padding: 1.125rem 2.5rem;
    font-size: 1.125rem;
}

/* Alerts Mejorados */
.alert {
    border-radius: 12px;
    border: none;
    padding: 1.25rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.alert-info {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1e3a8a;
    border-left: 4px solid #3b82f6;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #065f46;
    border-left: 4px solid #10b981;
}

.alert button {
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-weight: 700;
    font-size: 0.85rem;
}

/* Validación de Formularios */
.is-invalid {
    border-color: #dc2626 !important;
    box-shadow: 0 0 0 0.25rem rgba(220, 38, 38, 0.15) !important;
}

.invalid-feedback {
    color: #dc2626;
    font-weight: 600;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Responsive */
@media (max-width: 992px) {
    .checkout-page .display-4 {
        font-size: 2rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
}

@media (max-width: 768px) {
    .checkout-page {
        padding: 1rem 0;
    }
    
    .checkout-page .display-4 {
        font-size: 1.75rem;
    }
    
    .checkout-page .lead {
        font-size: 1rem;
    }
    
    .nav-pills .nav-link {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }
    
    .btn-lg {
        padding: 1rem 1.5rem;
        font-size: 1rem;
    }
    
    .payment-card-form, .yape-form {
        padding: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Formatear número de tarjeta de crédito
    const cardNumber = document.getElementById('card_number');
    if (cardNumber) {
        cardNumber.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            if (formattedValue !== value) {
                e.target.value = formattedValue;
            }
        });
    }

    // Actualizar método de pago según la pestaña activa
    const paymentTabs = document.querySelectorAll('#paymentTabs button');
    const paymentMethodInput = document.createElement('input');
    paymentMethodInput.type = 'hidden';
    paymentMethodInput.name = 'payment_method';
    paymentMethodInput.value = 'credit_card';
    document.getElementById('checkoutForm').appendChild(paymentMethodInput);

    paymentTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            if (this.id === 'card-tab') {
                paymentMethodInput.value = 'credit_card';
            } else if (this.id === 'yape-tab') {
                paymentMethodInput.value = 'yape';
            }
        });
    });

    // Validación en tiempo real del formulario
    const form = document.getElementById('checkoutForm');
    form.addEventListener('submit', function(e) {
        const activeTab = document.querySelector('#paymentTabs button.active');
        if (activeTab.id === 'card-tab') {
            // Validar campos de tarjeta
            const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
            const cardholderName = document.getElementById('cardholder_name').value;
            const expiryMonth = document.getElementById('card_expiry_month').value;
            const expiryYear = document.getElementById('card_expiry_year').value;
            const cvv = document.getElementById('card_cvv').value;

            if (!cardNumber || !cardholderName || !expiryMonth || !expiryYear || !cvv) {
                e.preventDefault();
                alert('Por favor completa todos los campos de la tarjeta');
                return;
            }
        } else if (activeTab.id === 'yape-tab') {
            // Validar campo de Yape
            const yapePhone = document.getElementById('yape_phone').value;
            if (!yapePhone) {
                e.preventDefault();
                alert('Por favor ingresa tu número de teléfono para Yape');
                return;
            }
        }
    });
});
</script>
@endsection