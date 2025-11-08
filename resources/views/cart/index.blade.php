@extends('layouts.app')

@section('title', 'Carrito de Compras - DigitalXpress')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <h2 class="fw-bold mb-4">🛒 Carrito de Compras</h2>
            @auth
                <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="fas fa-user-circle me-2"></i>
                    <div>
                        <strong>¡Hola {{ Auth::user()->name }}!</strong> 
                        <span class="d-block small">Aquí tienes todos los productos que has agregado a tu carrito.</span>
                    </div>
                </div>
            @endauth
            
            @if($cartItems->count() > 0)
                @foreach($cartItems as $item)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <img src="{{ $item->product->image_url }}" 
                                     class="img-fluid rounded" alt="{{ $item->product->name }}">
                            </div>
                            <div class="col-md-4">
                                <h6 class="fw-bold mb-1">{{ $item->product->name }}</h6>
                                <p class="text-muted small mb-0">{{ $item->product->category->name }}</p>
                            </div>
                            <div class="col-md-2">
                                <span class="fw-bold">${{ number_format($item->price, 2) }}</span>
                            </div>
                            <div class="col-md-2">
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                           min="1" max="{{ $item->product->stock_quantity }}" 
                                           class="form-control form-control-sm me-2" style="width: 80px;">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                                <small class="text-muted">Stock: {{ $item->product->stock_quantity }}</small>
                            </div>
                            <div class="col-md-2">
                                <span class="fw-bold">${{ number_format($item->total, 2) }}</span>
                            </div>
                            <div class="col-md-1">
                                <form action="{{ route('cart.remove', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="d-flex gap-2 mb-4">
                    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash me-1"></i> Vaciar Carrito
                        </button>
                    </form>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i> Continuar Comprando
                    </a>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    </div>
                    <h3 class="text-muted mb-3">🛒 Aún no tienes ningún producto agregado a tu carrito</h3>
                    <p class="text-muted mb-4 fs-5">¡Descubre nuestros increíbles productos y comienza tu compra!</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-store me-2"></i> Ir a la Tienda
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-home me-2"></i> Volver al Inicio
                        </a>
                    </div>
                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Agrega productos a tu carrito para verlos aquí
                        </small>
                    </div>
                </div>
            @endif
        </div>

        @if($cartItems->count() > 0)
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Envío:</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Impuestos:</span>
                        <span>$0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-success w-100 mb-3">
                        <i class="fas fa-credit-card me-1"></i> Proceder al Pago
                    </a>
                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            Compra 100% segura
                        </small>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Beneficios de tu compra</h6>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-truck text-primary me-2"></i>
                        <small>Envío gratis en compras mayores a $100</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-shield-alt text-primary me-2"></i>
                        <small>Garantía de 1 año</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-headset text-primary me-2"></i>
                        <small>Soporte 24/7</small>
                    </div>
                </div>
            </div>
        </div>
        @endif
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
@endsection
