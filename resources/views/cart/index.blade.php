@extends('layouts.app')

@section('title', 'Carrito de Compras - DigitalXpress')

@section('content')
<div class="cart-page">
    <div class="container py-5">
        <!-- Header Mejorado -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="cart-header">
                    <h1 class="cart-title">
                        <i class="fas fa-shopping-cart me-3"></i>
                        Carrito de Compras
                    </h1>
                    <p class="cart-subtitle">Revisa y gestiona tus productos antes de finalizar tu compra</p>
                </div>
            </div>
        </div>

        @auth
            <div class="alert alert-info cart-welcome-alert mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon me-3">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div>
                        <strong class="d-block">¡Hola {{ Auth::user()->name }}!</strong> 
                        <small class="d-block mt-1">Aquí tienes todos los productos que has agregado a tu carrito.</small>
                    </div>
                </div>
            </div>
        @endauth
        
        <div class="row">
            <div class="col-lg-8">
                @if($cartItems->count() > 0)
                    <div class="cart-items-container">
                        @foreach($cartItems as $item)
                        <div class="cart-item-card">
                            <div class="cart-item-image">
                                <img src="{{ $item->product->image_url }}" 
                                     class="img-fluid" 
                                     alt="{{ $item->product->name }}">
                                @if($item->product->is_on_sale)
                                    <span class="cart-item-badge">Oferta</span>
                                @endif
                            </div>
                            <div class="cart-item-details">
                                <h5 class="cart-item-name">{{ $item->product->name }}</h5>
                                <p class="cart-item-category">
                                    <i class="fas fa-tag me-1"></i>{{ $item->product->category->name }}
                                </p>
                                <div class="cart-item-stock">
                                    <i class="fas fa-box me-1"></i>
                                    <span>Stock disponible: <strong>{{ $item->product->stock_quantity }}</strong></span>
                                </div>
                            </div>
                            <div class="cart-item-price">
                                <div class="price-unit">
                                    <span class="price-label">Precio unitario</span>
                                    <span class="price-value">${{ number_format($item->price, 2) }}</span>
                                </div>
                            </div>
                            <div class="cart-item-quantity">
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="quantity-form">
                                    @csrf
                                    @method('PUT')
                                    <label class="quantity-label">Cantidad</label>
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn quantity-decrease" onclick="decreaseQuantity(this)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" 
                                               name="quantity" 
                                               value="{{ $item->quantity }}" 
                                               min="1" 
                                               max="{{ $item->product->stock_quantity }}" 
                                               class="quantity-input"
                                               readonly>
                                        <button type="button" class="quantity-btn quantity-increase" onclick="increaseQuantity(this, {{ $item->product->stock_quantity }})">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <button type="submit" class="btn-update-quantity">
                                        <i class="fas fa-sync-alt me-1"></i>Actualizar
                                    </button>
                                </form>
                            </div>
                            <div class="cart-item-total">
                                <span class="total-label">Total</span>
                                <span class="total-value">${{ number_format($item->total, 2) }}</span>
                            </div>
                            <div class="cart-item-actions">
                                <form action="{{ route('cart.remove', $item) }}" method="POST" class="remove-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-remove-item" onclick="return confirm('¿Estás seguro de eliminar este producto del carrito?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="cart-actions">
                        <form action="{{ route('cart.clear') }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de vaciar todo el carrito?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-enhanced btn-outline-danger">
                                <i class="fas fa-trash me-2"></i>Vaciar Carrito
                            </button>
                        </form>
                        <a href="{{ route('products.index') }}" class="btn btn-enhanced btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Continuar Comprando
                        </a>
                    </div>
                @else
                    <div class="cart-empty">
                        <div class="empty-cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h3 class="empty-cart-title">Tu carrito está vacío</h3>
                        <p class="empty-cart-text">Aún no tienes ningún producto agregado a tu carrito</p>
                        <p class="empty-cart-subtext">¡Descubre nuestros increíbles productos y comienza tu compra!</p>
                        <div class="empty-cart-actions">
                            <a href="{{ route('products.index') }}" class="btn btn-enhanced btn-primary btn-lg">
                                <i class="fas fa-store me-2"></i>Ir a la Tienda
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-enhanced btn-outline-primary btn-lg">
                                <i class="fas fa-home me-2"></i>Volver al Inicio
                            </a>
                        </div>
                        <div class="empty-cart-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <span>Agrega productos a tu carrito para verlos aquí</span>
                        </div>
                    </div>
                @endif
            </div>

            @if($cartItems->count() > 0)
            <div class="col-lg-4">
                <div class="cart-summary-card">
                    <div class="summary-header">
                        <h5 class="summary-title">
                            <i class="fas fa-receipt me-2"></i>Resumen del Pedido
                        </h5>
                    </div>
                    <div class="summary-body">
                        <div class="summary-row">
                            <span class="summary-label">Subtotal:</span>
                            <span class="summary-value">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Envío:</span>
                            <span class="summary-value summary-free">
                                <i class="fas fa-check-circle me-1"></i>Gratis
                            </span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Impuestos:</span>
                            <span class="summary-value">$0.00</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-total">
                            <span class="total-label">Total:</span>
                            <span class="total-amount">${{ number_format($total, 2) }}</span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-enhanced btn-success btn-checkout w-100">
                            <i class="fas fa-credit-card me-2"></i>Proceder al Pago
                        </a>
                        <div class="security-badge">
                            <i class="fas fa-shield-alt me-2"></i>
                            <span>Compra 100% segura</span>
                        </div>
                    </div>
                </div>

                <div class="benefits-card">
                    <div class="benefits-header">
                        <h6 class="benefits-title">
                            <i class="fas fa-gift me-2"></i>Beneficios de tu compra
                        </h6>
                    </div>
                    <div class="benefits-body">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="benefit-text">
                                <strong>Envío gratis</strong>
                                <small>En compras mayores a $100</small>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="benefit-text">
                                <strong>Garantía de 1 año</strong>
                                <small>En todos nuestros productos</small>
                            </div>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="benefit-text">
                                <strong>Soporte 24/7</strong>
                                <small>Estamos aquí para ayudarte</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">¡Éxito!</strong>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-header bg-danger text-white">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong class="me-auto">Error</strong>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    </div>
@endif

<style>
/* ============================================
   ESTILOS MEJORADOS DEL CARRITO
   ============================================ */

.cart-page {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

/* Header del Carrito */
.cart-header {
    text-align: center;
    margin-bottom: 2rem;
    padding: 2rem;
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.cart-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1e3a8a;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cart-title i {
    color: #3b82f6;
}

.cart-subtitle {
    color: #6b7280;
    font-size: 1.1rem;
    margin: 0;
}

.cart-welcome-alert {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    border: none;
    border-left: 4px solid #3b82f6;
    border-radius: 12px;
    padding: 1.25rem;
    box-shadow: 0 2px 10px rgba(59, 130, 246, 0.1);
}

.alert-icon {
    font-size: 2rem;
    color: #3b82f6;
}

/* Contenedor de Items del Carrito */
.cart-items-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Tarjeta de Item del Carrito */
.cart-item-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    display: grid;
    grid-template-columns: 120px 1fr auto auto auto auto;
    gap: 1.5rem;
    align-items: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid transparent;
}

.cart-item-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    border-color: #3b82f6;
}

.cart-item-image {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 12px;
    overflow: hidden;
    background: #f9fafb;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cart-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cart-item-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.cart-item-details {
    flex: 1;
}

.cart-item-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.cart-item-category {
    color: #6b7280;
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
}

.cart-item-stock {
    color: #059669;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    font-weight: 500;
}

.cart-item-price {
    text-align: center;
}

.price-unit {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.price-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.price-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e3a8a;
}

.cart-item-quantity {
    text-align: center;
}

.quantity-form {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: center;
}

.quantity-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    padding: 0.25rem;
    background: white;
}

.quantity-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: #f3f4f6;
    color: #1e3a8a;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.85rem;
}

.quantity-btn:hover {
    background: #3b82f6;
    color: white;
    transform: scale(1.1);
}

.quantity-input {
    width: 50px;
    text-align: center;
    border: none;
    font-weight: 700;
    font-size: 1rem;
    color: #1f2937;
    background: transparent;
}

.btn-update-quantity {
    margin-top: 0.5rem;
    padding: 0.4rem 0.8rem;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-update-quantity:hover {
    background: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.cart-item-total {
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.total-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.total-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #10b981;
    background: linear-gradient(135deg, #10b981, #059669);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.cart-item-actions {
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-remove-item {
    width: 40px;
    height: 40px;
    border: none;
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #dc2626;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 1rem;
}

.btn-remove-item:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

/* Acciones del Carrito */
.cart-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #e5e7eb;
}

/* Carrito Vacío */
.cart-empty {
    background: white;
    border-radius: 20px;
    padding: 4rem 2rem;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.empty-cart-icon {
    font-size: 6rem;
    color: #d1d5db;
    margin-bottom: 1.5rem;
}

.empty-cart-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.75rem;
}

.empty-cart-text {
    font-size: 1.1rem;
    color: #6b7280;
    margin-bottom: 0.5rem;
}

.empty-cart-subtext {
    font-size: 0.95rem;
    color: #9ca3af;
    margin-bottom: 2rem;
}

.empty-cart-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 2rem;
}

.empty-cart-info {
    color: #6b7280;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Resumen del Pedido */
.cart-summary-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-bottom: 1.5rem;
    border: 2px solid #e5e7eb;
}

.summary-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    padding: 1.5rem;
    color: white;
}

.summary-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
}

.summary-body {
    padding: 1.5rem;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.summary-row:last-of-type {
    border-bottom: none;
}

.summary-label {
    color: #6b7280;
    font-weight: 500;
}

.summary-value {
    font-weight: 700;
    color: #1f2937;
    font-size: 1.05rem;
}

.summary-free {
    color: #10b981;
    display: flex;
    align-items: center;
}

.summary-divider {
    height: 2px;
    background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
    margin: 1rem 0;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    margin: 0 -1.5rem;
    padding: 1.5rem;
    border-top: 2px solid #10b981;
    border-bottom: 2px solid #10b981;
}

.total-label {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
}

.total-amount {
    font-size: 1.75rem;
    font-weight: 800;
    color: #10b981;
    background: linear-gradient(135deg, #10b981, #059669);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.btn-checkout {
    margin-top: 1.5rem;
    padding: 1rem;
    font-size: 1.1rem;
    font-weight: 700;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    border: none;
}

.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.security-badge {
    text-align: center;
    margin-top: 1rem;
    padding: 0.75rem;
    background: #f0fdf4;
    border-radius: 10px;
    color: #059669;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Tarjeta de Beneficios */
.benefits-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    border: 2px solid #e5e7eb;
}

.benefits-header {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    padding: 1.25rem 1.5rem;
    border-bottom: 2px solid #fbbf24;
}

.benefits-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #92400e;
    margin: 0;
    display: flex;
    align-items: center;
}

.benefits-body {
    padding: 1.5rem;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.benefit-item:last-child {
    border-bottom: none;
}

.benefit-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3b82f6;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.benefit-text {
    flex: 1;
}

.benefit-text strong {
    display: block;
    color: #1f2937;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.benefit-text small {
    color: #6b7280;
    font-size: 0.85rem;
}

/* Responsive */
@media (max-width: 992px) {
    .cart-item-card {
        grid-template-columns: 100px 1fr;
        gap: 1rem;
    }
    
    .cart-item-image {
        width: 100px;
        height: 100px;
    }
    
    .cart-item-price,
    .cart-item-quantity,
    .cart-item-total,
    .cart-item-actions {
        grid-column: span 2;
        margin-top: 0.5rem;
    }
    
    .cart-item-price,
    .cart-item-total {
        text-align: left;
    }
    
    .cart-item-quantity {
        text-align: left;
    }
    
    .quantity-form {
        align-items: flex-start;
    }
}

@media (max-width: 768px) {
    .cart-title {
        font-size: 2rem;
    }
    
    .cart-actions {
        flex-direction: column;
    }
    
    .empty-cart-actions {
        flex-direction: column;
    }
}
</style>

<script>
function increaseQuantity(btn, maxStock) {
    const form = btn.closest('.quantity-form');
    const input = form.querySelector('.quantity-input');
    const currentValue = parseInt(input.value);
    if (currentValue < maxStock) {
        input.value = currentValue + 1;
    } else {
        alert('No hay suficiente stock disponible');
    }
}

function decreaseQuantity(btn) {
    const form = btn.closest('.quantity-form');
    const input = form.querySelector('.quantity-input');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}
</script>
@endsection
