@extends('layouts.app')

@section('title', 'DigitalXpress - Tu tienda de tecnología')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="mb-3">
                    <span class="badge bg-success fs-6 px-3 py-2">Oferta Especial</span>
                </div>
                <h1 class="display-4 fw-bold mb-3">MacBook Pro M3 Max</h1>
                <p class="lead mb-3">Potencia sin límites para profesionales</p>
                <p class="mb-4">El laptop más potente de Apple con chip M3 Max, pantalla Liquid Retina XDR y hasta 22 horas de batería.</p>
                <div class="d-flex align-items-center mb-4">
                    <span class="h2 text-success fw-bold me-3">$2,499</span>
                    <span class="text-decoration-line-through text-light">$2,899</span>
                </div>
                <div class="d-flex gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-success btn-lg px-4">
                        Ver Productos
                    </a>
                    <button class="btn btn-outline-light btn-lg px-4">
                        Más Información
                    </button>
                </div>
                @auth
                    <div class="mt-4">
                        <div class="alert alert-light d-flex align-items-center" role="alert">
                            <i class="fas fa-user-circle me-2 text-primary"></i>
                            <div>
                                <strong>¡Bienvenido de vuelta, {{ Auth::user()->name }}!</strong> 
                                <span class="d-block small">Explora nuestros productos y agrega los que te gusten a tu carrito.</span>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=600&h=400&fit=crop" 
                         alt="MacBook Pro" class="img-fluid rounded-3 shadow">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Feature Cards -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="feature-card">
                    <div class="text-primary mb-3">
                        <i class="fas fa-bolt fa-2x"></i>
                    </div>
                    <h5 class="fw-bold">Entrega Rápida</h5>
                    <p class="text-muted">Envío en 24-48 horas</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card">
                    <div class="text-primary mb-3">
                        <i class="fas fa-shield-alt fa-2x"></i>
                    </div>
                    <h5 class="fw-bold">Garantía Total</h5>
                    <p class="text-muted">1 año de garantía oficial</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card">
                    <div class="text-primary mb-3">
                        <i class="fas fa-truck fa-2x"></i>
                    </div>
                    <h5 class="fw-bold">Envío Gratis</h5>
                    <p class="text-muted">En compras mayores a $100</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card">
                    <div class="text-primary mb-3">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                    <h5 class="fw-bold">Soporte 24/7</h5>
                    <p class="text-muted">Atención personalizada</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Productos Destacados</h2>
            <p class="text-muted">Los mejores productos seleccionados para ti</p>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-lg-3 col-md-6">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-{{ rand(1500000000000, 1600000000000) }}?w=300&h=200&fit=crop" 
                             class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2">
                            <button class="btn btn-light btn-sm rounded-circle">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        @if($product->is_on_sale)
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-danger">Oferta</span>
                        </div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge bg-secondary">{{ $product->category->name }}</span>
                        </div>
                        <h6 class="card-title fw-bold">{{ $product->name }}</h6>
                        <p class="card-text text-muted small">{{ Str::limit($product->short_description, 80) }}</p>
                        <div class="d-flex align-items-center mb-2">
                            <div class="text-warning me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $product->rating ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted">({{ $product->review_count }})</small>
                        </div>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    @if($product->is_on_sale)
                                        <span class="h5 text-success fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                                        <small class="text-decoration-line-through text-muted">${{ number_format($product->price, 2) }}</small>
                                    @else
                                        <span class="h5 text-primary fw-bold">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <small class="text-muted">Stock: {{ $product->stock_quantity }}</small>
                            </div>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-primary w-100">
                                <i class="fas fa-shopping-cart me-1"></i> Agregar al Carrito
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Latest Products -->
@if($latestProducts->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Últimos Productos</h2>
            <p class="text-muted">Los productos más recientes en nuestro catálogo</p>
        </div>
        <div class="row g-4">
            @foreach($latestProducts as $product)
            <div class="col-lg-3 col-md-6">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-{{ rand(1500000000000, 1600000000000) }}?w=300&h=200&fit=crop" 
                             class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2">
                            <button class="btn btn-light btn-sm rounded-circle">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        @if($product->is_on_sale)
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-danger">Oferta</span>
                        </div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge bg-secondary">{{ $product->category->name }}</span>
                        </div>
                        <h6 class="card-title fw-bold">{{ $product->name }}</h6>
                        <p class="card-text text-muted small">{{ Str::limit($product->short_description, 80) }}</p>
                        <div class="d-flex align-items-center mb-2">
                            <div class="text-warning me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $product->rating ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted">({{ $product->review_count }})</small>
                        </div>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    @if($product->is_on_sale)
                                        <span class="h5 text-success fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                                        <small class="text-decoration-line-through text-muted">${{ number_format($product->price, 2) }}</small>
                                    @else
                                        <span class="h5 text-primary fw-bold">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <small class="text-muted">Stock: {{ $product->stock_quantity }}</small>
                            </div>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-primary w-100">
                                <i class="fas fa-shopping-cart me-1"></i> Agregar al Carrito
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Call to Action -->
<section class="py-5">
    <div class="container">
        <div class="text-center">
            <h2 class="fw-bold mb-3">¿Listo para la mejor tecnología?</h2>
            <p class="text-muted mb-4">Descubre nuestra selección de productos tecnológicos de las mejores marcas mundiales. Calidad garantizada, precios competitivos y el mejor servicio al cliente.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-5">
                Explorar Todos los Productos
            </a>
        </div>
    </div>
</section>
@endsection
