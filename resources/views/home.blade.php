@extends('layouts.app')

@section('title', 'DigitalXpress - Tu tienda de tecnología')

@section('content')
<!-- Hero Section - Carrusel de Productos -->
@if($carouselProducts->count() > 0)
<section class="hero-section-wrapper">
    <div class="hero-section" id="heroSection">
    <div class="container">
        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <!-- Indicadores -->
            <div class="carousel-indicators">
                @foreach($carouselProducts as $index => $product)
                <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="{{ $index }}" 
                        class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" 
                        aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>

            <!-- Slides del carrusel -->
            <div class="carousel-inner">
                @foreach($carouselProducts as $index => $product)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="row align-items-center h-100">
                        <div class="col-lg-6 d-flex flex-column justify-content-center">
                            <div class="mb-3 hero-badge-container">
                                @if($product->is_on_sale)
                                    <span class="badge-hero badge-hero-success">
                                        <i class="fas fa-fire me-1"></i> Oferta Especial
                                    </span>
                                @else
                                    <span class="badge-hero badge-hero-primary">
                                        <i class="fas fa-star me-1"></i> Producto Destacado
                                    </span>
                                @endif
                            </div>
                            <h1 class="hero-title display-5 fw-bold mb-3">{{ $product->name }}</h1>
                            <p class="hero-category lead mb-2">
                                <i class="fas fa-tag me-1"></i> {{ $product->category->name ?? 'Tecnología' }}
                            </p>
                            <p class="hero-description mb-4 small">{{ Str::limit($product->short_description ?? $product->description, 100) }}</p>
                            <div class="d-flex align-items-center mb-4 hero-price-container">
                                @if($product->is_on_sale)
                                    <span class="hero-price-sale h2 text-success fw-bold me-3">
                                        ${{ number_format($product->sale_price, 2) }}
                                    </span>
                                    <span class="hero-price-original text-decoration-line-through text-light">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                @else
                                    <span class="hero-price h2 text-success fw-bold me-3">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                @endif
                            </div>
                            <div class="d-flex gap-3 mb-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-enhanced btn-success btn-lg px-4 hero-btn">
                                    <i class="fas fa-eye"></i> <span>Ver Producto</span>
                                </a>
                                <a href="{{ route('products.index') }}" class="btn btn-enhanced btn-outline-light btn-lg px-4 hero-btn">
                                    <i class="fas fa-th"></i> <span>Ver Todos</span>
                                </a>
                            </div>
                @auth
                                <div class="mt-3 welcome-badge">
                                    <div class="welcome-card d-flex align-items-center py-2 px-3">
                                        <div class="welcome-avatar me-3">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <div class="small">
                                            <strong>¡Bienvenido, {{ Auth::user()->name }}!</strong>
                                        </div>
                                    </div>
                                </div>
                            @endauth
                        </div>
                        <div class="col-lg-6 d-flex align-items-center justify-content-center">
                            <div class="carousel-image-panel" data-product-index="{{ $index }}">
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}" 
                                     class="carousel-image">
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Botones de navegación -->
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
            </div>
        </div>
    </div>
</section>
@endif

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
                    <div class="position-relative product-image-container">
                        <a href="{{ route('products.show', $product) }}" class="product-image-link">
                            <img src="{{ $product->image_url }}" 
                                 class="card-img-top product-image" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        </a>
                        <div class="product-overlay">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-view-details">
                                <i class="fas fa-eye me-2"></i> Ver más detalles
                            </a>
                        </div>
                        <div class="position-absolute top-0 end-0 m-2">
                            @auth
                            <button class="btn btn-light btn-sm rounded-circle product-favorite-btn" 
                                    data-product-id="{{ $product->id }}"
                                    onclick="toggleFavorite({{ $product->id }})">
                                <i class="fas fa-heart"></i>
                            </button>
                            @else
                            <button class="btn btn-light btn-sm rounded-circle product-favorite-btn" 
                                    onclick="alert('Debes iniciar sesión para agregar favoritos')">
                                <i class="fas fa-heart"></i>
                            </button>
                            @endauth
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
                    <div class="position-relative product-image-container">
                        <a href="{{ route('products.show', $product) }}" class="product-image-link">
                            <img src="{{ $product->image_url }}" 
                                 class="card-img-top product-image" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        </a>
                        <div class="product-overlay">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-primary btn-view-details">
                                <i class="fas fa-eye me-2"></i> Ver más detalles
                            </a>
                        </div>
                        <div class="position-absolute top-0 end-0 m-2">
                            @auth
                            <button class="btn btn-light btn-sm rounded-circle product-favorite-btn" 
                                    data-product-id="{{ $product->id }}"
                                    onclick="toggleFavorite({{ $product->id }})">
                                <i class="fas fa-heart"></i>
                            </button>
                            @else
                            <button class="btn btn-light btn-sm rounded-circle product-favorite-btn" 
                                    onclick="alert('Debes iniciar sesión para agregar favoritos')">
                                <i class="fas fa-heart"></i>
                            </button>
                            @endauth
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

<style>
    /* Contenedor wrapper para la sección hero con márgenes laterales */
    .hero-section-wrapper {
        padding: 0 1.5rem; /* Espacio blanco a los lados */
        margin: 0 auto;
        max-width: 100%;
        overflow: visible; /* Permitir que las flechas se vean fuera del contenedor */
    }

    /* Sección hero con borde y espacio blanco */
    .hero-section-wrapper .hero-section {
        border-radius: 20px; /* Bordes redondeados */
        margin: 2rem auto; /* Márgenes superior e inferior y centrado */
        max-width: 1400px; /* Ancho máximo del contenido */
        overflow: visible; /* Permitir que las flechas se vean */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Sombra sutil */
        padding: 0 !important; /* Sobrescribir el padding del layout */
    }

    /* Padding interno para el contenido del carrusel */
    .hero-section-wrapper .hero-section .container {
        padding: 2rem 1.5rem;
    }

    /* Responsive: ajustar padding en pantallas pequeñas */
    @media (max-width: 768px) {
        .hero-section-wrapper {
            padding: 0 1rem; /* Menos espacio en móviles */
        }
        
        .hero-section-wrapper .hero-section {
            margin: 1rem auto; /* Menos margen en móviles */
            border-radius: 15px; /* Bordes menos redondeados en móviles */
        }

        .hero-section-wrapper .hero-section .container {
            padding: 1.5rem 1rem; /* Menos padding interno en móviles */
        }
    }

    /* Estilos para el carrusel de productos */
    #productCarousel {
        position: relative;
        height: 600px; /* Altura fija para el carrusel */
        overflow: visible; /* Permitir que las flechas se vean */
        padding: 0 60px; /* Espacio para las flechas a los lados */
    }

    /* Panel con borde para la imagen del producto - Mejorado con más efectos */
    .carousel-image-panel {
        background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
        border-radius: 25px;
        padding: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        border: 5px solid #1e3a8a;
        box-shadow: 
            0 20px 60px rgba(0, 0, 0, 0.3),
            0 0 0 1px rgba(255, 255, 255, 0.1) inset,
            0 0 40px rgba(30, 58, 138, 0.3);
        width: 100%;
        max-width: 500px;
        height: 500px;
        margin: 0 auto;
        overflow: hidden;
        animation: panelFloat 3s ease-in-out infinite;
    }

    @keyframes panelFloat {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(-10px) rotate(1deg);
        }
    }

    .carousel-image-panel::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: rotateGlow 8s linear infinite;
        pointer-events: none;
    }

    @keyframes rotateGlow {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .carousel-image-panel.active {
        border-color: #3b82f6;
        box-shadow: 
            0 25px 70px rgba(0, 0, 0, 0.4),
            0 0 0 1px rgba(255, 255, 255, 0.15) inset,
            0 0 60px rgba(59, 130, 246, 0.5);
        transform: scale(1.02);
    }

    #productCarousel .carousel-item {
        height: 600px; /* Altura fija para cada slide */
        padding: 2rem 0;
        padding-bottom: 4rem; /* Espacio adicional en la parte inferior para los indicadores */
    }

    #productCarousel .carousel-inner {
        height: 600px; /* Altura fija para el contenedor interno */
        overflow: visible; /* Permitir que el contenido se vea completamente */
    }

    #productCarousel .carousel-image {
        width: 100%;
        height: 430px;
        object-fit: contain;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        display: block;
        filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.5));
        animation: imageFloat 4s ease-in-out infinite;
        position: relative;
        z-index: 2;
    }

    @keyframes imageFloat {
        0%, 100% {
            transform: translateY(0px) scale(1);
        }
        50% {
            transform: translateY(-8px) scale(1.02);
        }
    }

    .carousel-image-panel:hover .carousel-image {
        transform: scale(1.08) translateY(-5px);
        filter: drop-shadow(0 15px 40px rgba(0, 0, 0, 0.6)) brightness(1.1);
    }

    #productCarousel .carousel-control-prev,
    #productCarousel .carousel-control-next {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.2) 100%);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.85;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
        z-index: 15;
    }

    #productCarousel .carousel-control-prev:hover,
    #productCarousel .carousel-control-next:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.5) 0%, rgba(255, 255, 255, 0.4) 100%);
        opacity: 1;
        transform: translateY(-50%) scale(1.15);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.35);
        border-color: rgba(255, 255, 255, 0.6);
    }

    #productCarousel .carousel-control-prev-icon,
    #productCarousel .carousel-control-next-icon {
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.4));
        transition: transform 0.3s ease;
        width: 20px;
        height: 20px;
    }

    #productCarousel .carousel-control-prev:hover .carousel-control-prev-icon {
        transform: translateX(-2px);
    }

    #productCarousel .carousel-control-next:hover .carousel-control-next-icon {
        transform: translateX(2px);
    }

    /* Posicionar flechas en los bordes del contenedor del carrusel */
    #productCarousel .carousel-control-prev {
        left: 0;
    }

    #productCarousel .carousel-control-next {
        right: 0;
    }

    /* En pantallas medianas, ajustar padding y posición */
    @media (max-width: 1200px) {
        #productCarousel {
            padding: 0 50px;
        }

        #productCarousel .carousel-control-prev {
            left: 0;
        }

        #productCarousel .carousel-control-next {
            right: 0;
        }
    }

    /* Asegurar que el contenido tenga suficiente espacio y no se solape con las flechas */
    #productCarousel .carousel-item .col-lg-6:first-child {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        position: relative;
        z-index: 10;
    }

    #productCarousel .carousel-item .col-lg-6:last-child {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        position: relative;
        z-index: 10;
    }

    /* En pantallas pequeñas, ajustar más el padding y tamaño de flechas */
    @media (max-width: 768px) {
        #productCarousel {
            padding: 0 45px;
        }

        #productCarousel .carousel-control-prev {
            left: 0;
            width: 40px;
            height: 40px;
        }

        #productCarousel .carousel-control-next {
            right: 0;
            width: 40px;
            height: 40px;
        }

        #productCarousel .carousel-control-prev-icon,
        #productCarousel .carousel-control-next-icon {
            width: 16px;
            height: 16px;
        }

        #productCarousel .carousel-item .col-lg-6:first-child {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        #productCarousel .carousel-item .col-lg-6:last-child {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }

    #productCarousel .carousel-indicators {
        bottom: 30px;
        z-index: 10; /* Asegurar que los indicadores estén por encima del welcome-card */
        position: relative;
    }

    #productCarousel .carousel-indicators button {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.4);
        border: 2px solid rgba(255, 255, 255, 0.6);
        margin: 0 6px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    #productCarousel .carousel-indicators button::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.8) 0%, transparent 70%);
        transform: translate(-50%, -50%);
        transition: width 0.4s ease, height 0.4s ease;
    }

    #productCarousel .carousel-indicators button:hover {
        background-color: rgba(255, 255, 255, 0.7);
        transform: scale(1.3);
        border-color: rgba(255, 255, 255, 0.9);
    }

    #productCarousel .carousel-indicators button.active {
        background-color: rgba(255, 255, 255, 1);
        border-color: rgba(255, 255, 255, 1);
        transform: scale(1.4);
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.6);
    }

    #productCarousel .carousel-indicators button.active::before {
        width: 100%;
        height: 100%;
    }

    /* Estilos para efecto hover en imágenes de productos */
    .product-image-container {
        overflow: hidden;
        border-radius: 8px 8px 0 0;
    }

    .product-image-link {
        display: block;
        position: relative;
        text-decoration: none;
        overflow: hidden;
    }

    .product-image {
        width: 100%;
        transition: transform 0.4s ease, filter 0.4s ease;
        display: block;
    }

    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 2;
        pointer-events: none;
    }

    .product-overlay .btn-view-details {
        pointer-events: auto;
    }

    .product-image-container:hover .product-image {
        transform: scale(1.1);
        filter: blur(4px);
    }

    .product-image-container:hover .product-overlay {
        opacity: 1;
    }

    .btn-view-details {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        z-index: 3;
        border: none;
    }

    .btn-view-details:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
    }

    .product-favorite-btn {
        z-index: 10;
        transition: all 0.3s ease;
        border: none;
    }

    .product-favorite-btn.favorite-active {
        background-color: #dc3545 !important;
        color: white !important;
        border-color: #dc3545 !important;
    }

    .product-favorite-btn.favorite-active i {
        color: white !important;
    }

    .product-favorite-btn:hover {
        background-color: #dc3545 !important;
        color: white !important;
        transform: scale(1.1);
        border-color: #dc3545 !important;
    }

    .product-favorite-btn:hover i {
        color: white !important;
    }

    /* Asegurar que los badges estén sobre el overlay */
    .product-image-container .badge {
        z-index: 10;
    }

    /* Estilos mejorados para el hero section */
    .hero-title {
        animation: fadeInUp 0.8s ease-out;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        line-height: 1.2;
    }

    .hero-category {
        animation: fadeInUp 1s ease-out;
        opacity: 0.95;
        font-weight: 500;
    }

    .hero-description {
        animation: fadeInUp 1.2s ease-out;
        opacity: 0.9;
        line-height: 1.6;
    }

    .hero-price-container {
        animation: fadeInUp 1.4s ease-out;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.5) 100%);
        border-radius: 15px;
        backdrop-filter: blur(15px);
        border: 2px solid rgba(74, 222, 128, 0.4);
        box-shadow: 
            0 4px 20px rgba(0, 0, 0, 0.5),
            0 0 20px rgba(74, 222, 128, 0.3),
            inset 0 0 20px rgba(74, 222, 128, 0.1);
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hero-price-container::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(74, 222, 128, 0.2), transparent);
        animation: priceShimmer 3s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes priceShimmer {
        0% {
            transform: translate(-50%, -50%) rotate(0deg);
        }
        100% {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    .hero-price-container:hover {
        transform: scale(1.05);
        border-color: rgba(74, 222, 128, 0.8);
        box-shadow: 
            0 6px 30px rgba(0, 0, 0, 0.6),
            0 0 30px rgba(74, 222, 128, 0.5),
            inset 0 0 30px rgba(74, 222, 128, 0.2);
    }

    .hero-price {
        color: #4ade80 !important; /* Verde brillante */
        text-shadow: 
            0 0 15px rgba(74, 222, 128, 1),
            0 0 30px rgba(74, 222, 128, 0.8),
            0 2px 10px rgba(0, 0, 0, 0.9),
            0 4px 15px rgba(0, 0, 0, 0.7);
        animation: priceGlow 2s ease-in-out infinite;
        font-weight: 900 !important;
        letter-spacing: 1px;
        filter: drop-shadow(0 0 10px rgba(74, 222, 128, 0.8));
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }

    @keyframes priceGlow {
        0%, 100% {
            text-shadow: 
                0 0 15px rgba(74, 222, 128, 1),
                0 0 30px rgba(74, 222, 128, 0.8),
                0 2px 10px rgba(0, 0, 0, 0.9),
                0 4px 15px rgba(0, 0, 0, 0.7);
            filter: drop-shadow(0 0 10px rgba(74, 222, 128, 0.8));
        }
        50% {
            text-shadow: 
                0 0 20px rgba(74, 222, 128, 1),
                0 0 40px rgba(74, 222, 128, 1),
                0 2px 10px rgba(0, 0, 0, 0.9),
                0 4px 15px rgba(0, 0, 0, 0.7);
            filter: drop-shadow(0 0 15px rgba(74, 222, 128, 1));
        }
    }

    .hero-price-container:hover .hero-price {
        transform: scale(1.05);
        animation: pricePulse 0.6s ease-in-out;
    }

    @keyframes pricePulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }

    .hero-price-sale {
        color: #4ade80 !important; /* Verde brillante */
        text-shadow: 
            0 0 15px rgba(74, 222, 128, 1),
            0 0 30px rgba(74, 222, 128, 0.8),
            0 2px 10px rgba(0, 0, 0, 0.9),
            0 4px 15px rgba(0, 0, 0, 0.7);
        animation: priceGlow 2s ease-in-out infinite;
        font-weight: 900 !important;
        letter-spacing: 1px;
        filter: drop-shadow(0 0 10px rgba(74, 222, 128, 0.8));
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .hero-price-container:hover .hero-price-sale {
        transform: scale(1.05);
        animation: pricePulse 0.6s ease-in-out;
    }

    .hero-price-original {
        color: rgba(255, 255, 255, 0.7) !important;
        text-shadow: 
            0 2px 6px rgba(0, 0, 0, 0.9),
            0 0 8px rgba(0, 0, 0, 0.6);
        font-size: 1rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .hero-price-container:hover .hero-price-original {
        opacity: 1;
        transform: scale(1.05);
    }


    .hero-badge-container {
        animation: fadeInDown 0.6s ease-out;
    }

    .badge-hero {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        animation: badgePulse 2s ease-in-out infinite;
        backdrop-filter: blur(10px);
    }

    .badge-hero-primary {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.9) 0%, rgba(37, 99, 235, 0.9) 100%);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .badge-hero-success {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.9) 0%, rgba(22, 163, 74, 0.9) 100%);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    @keyframes badgePulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
    }

    .hero-btn {
        animation: fadeInUp 1.6s ease-out;
        position: relative;
        overflow: hidden;
    }

    .hero-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }

    .hero-btn:hover::after {
        width: 300px;
        height: 300px;
    }

    .welcome-badge {
        animation: fadeInUp 1.8s ease-out;
    }

    .welcome-card {
        background: linear-gradient(135deg, rgba(30, 58, 138, 0.95) 0%, rgba(37, 99, 235, 0.95) 100%);
        border-radius: 15px;
        box-shadow: 
            0 8px 25px rgba(0, 0, 0, 0.3),
            0 0 0 2px rgba(255, 255, 255, 0.2) inset,
            0 0 30px rgba(59, 130, 246, 0.4);
        backdrop-filter: blur(15px);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        position: relative;
        overflow: hidden;
        z-index: 5; /* Asegurar que esté detrás de los indicadores */
        margin-bottom: 50px; /* Espacio para los indicadores del carrusel */
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: welcomeShimmer 3s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes welcomeShimmer {
        0%, 100% {
            transform: translate(-50%, -50%) rotate(0deg);
            opacity: 0.3;
        }
        50% {
            transform: translate(-50%, -50%) rotate(180deg);
            opacity: 0.6;
        }
    }

    .welcome-card:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 
            0 12px 35px rgba(0, 0, 0, 0.4),
            0 0 0 2px rgba(255, 255, 255, 0.3) inset,
            0 0 40px rgba(59, 130, 246, 0.6);
        border-color: rgba(255, 255, 255, 0.5);
    }

    .welcome-card strong {
        color: #ffffff;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .welcome-card .small {
        color: rgba(255, 255, 255, 0.95);
        text-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    }

    .welcome-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3b82f6;
        font-size: 24px;
        box-shadow: 
            0 6px 20px rgba(0, 0, 0, 0.3),
            0 0 0 3px rgba(255, 255, 255, 0.5) inset;
        animation: avatarPulse 2s ease-in-out infinite;
        border: 3px solid rgba(255, 255, 255, 0.6);
        position: relative;
        z-index: 1;
    }

    @keyframes avatarPulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(59, 130, 246, 0.5);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Efectos de entrada para cada slide */
    .carousel-item {
        animation: slideIn 0.8s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Efecto de brillo en el fondo del hero */
    .hero-section::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        pointer-events: none;
        animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
        0%, 100% {
            opacity: 0.5;
        }
        50% {
            opacity: 0.8;
        }
    }

    /* Mejorar contraste de texto en el hero */
    .hero-section .col-lg-6:first-child {
        position: relative;
        z-index: 2;
    }

    .hero-section .col-lg-6:first-child h1,
    .hero-section .col-lg-6:first-child p,
    .hero-section .col-lg-6:first-child .lead {
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }
</style>

    /* Animaciones de entrada básicas */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-40px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(40px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes rotateIn {
        from {
            opacity: 0;
            transform: rotate(-10deg) scale(0.9);
        }
        to {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }
    }

    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            transform: scale(1);
        }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(100px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Clases de animación - Los elementos son visibles por defecto */
    .fade-in-up {
        opacity: 1;
        transform: translateY(0);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }

    .fade-in-up:not(.animated) {
        opacity: 0;
        transform: translateY(40px);
    }

    .fade-in-down {
        opacity: 1;
        transform: translateY(0);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }

    .fade-in-down:not(.animated) {
        opacity: 0;
        transform: translateY(-40px);
    }

    .fade-in-left {
        opacity: 1;
        transform: translateX(0);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }

    .fade-in-left:not(.animated) {
        opacity: 0;
        transform: translateX(-40px);
    }

    .fade-in-right {
        opacity: 1;
        transform: translateX(0);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }

    .fade-in-right:not(.animated) {
        opacity: 0;
        transform: translateX(40px);
    }

    .scale-in {
        opacity: 1;
        transform: scale(1);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }

    .scale-in:not(.animated) {
        opacity: 0;
        transform: scale(0.8);
    }

    .rotate-in {
        opacity: 1;
        transform: rotate(0deg) scale(1);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }

    .rotate-in:not(.animated) {
        opacity: 0;
        transform: rotate(-10deg) scale(0.9);
    }

    .bounce-in {
        opacity: 1;
        transform: scale(1);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }

    .bounce-in:not(.animated) {
        opacity: 0;
        transform: scale(0.3);
    }

    .slide-in-up {
        opacity: 1;
        transform: translateY(0);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }

    .slide-in-up:not(.animated) {
        opacity: 0;
        transform: translateY(100px);
    }

    /* Animación on scroll - Visible por defecto */
    .animate-on-scroll {
        opacity: 1;
        transform: translateY(0);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }

    .animate-on-scroll:not(.animated) {
        opacity: 0;
        transform: translateY(30px);
    }

    .animate-on-scroll.animated {
        opacity: 1;
        transform: translateY(0);
    }

    /* Estilos mejorados para Feature Cards */
    .feature-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        text-align: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
        transition: left 0.6s ease;
    }

    .feature-card:hover::before {
        left: 100%;
    }

    .feature-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 12px 30px rgba(59, 130, 246, 0.2);
        border-color: rgba(59, 130, 246, 0.3);
    }

    .feature-card .text-primary {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .feature-card:hover .text-primary {
        transform: scale(1.2) rotate(5deg);
        color: #2563eb !important;
    }

    .feature-card:hover .text-primary i {
        animation: iconBounce 0.6s ease-in-out;
    }

    @keyframes iconBounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    .feature-card h5 {
        transition: color 0.3s ease;
    }

    .feature-card:hover h5 {
        color: #2563eb;
    }

    /* Animaciones mejoradas para Product Cards */
    .product-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0, 0, 0, 0.08);
        overflow: hidden;
        position: relative;
    }

    .product-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(37, 99, 235, 0.05) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
        pointer-events: none;
    }

    .product-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        border-color: rgba(59, 130, 246, 0.3);
    }

    .product-card:hover::after {
        opacity: 1;
    }

    .product-card .card-body {
        transition: all 0.4s ease;
    }

    .product-card:hover .card-body {
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 1));
    }

    .product-card .card-title {
        transition: color 0.3s ease;
    }

    .product-card:hover .card-title {
        color: #2563eb;
    }

    .product-card .btn-primary {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .product-card .btn-primary::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }

    .product-card .btn-primary:hover::before {
        width: 300px;
        height: 300px;
    }

    .product-card .btn-primary:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    }

    /* Animaciones para imágenes de productos */
    .product-image {
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .product-card:hover .product-image {
        transform: scale(1.15) rotate(2deg);
    }

    /* Animaciones para badges */
    .badge {
        transition: all 0.3s ease;
        animation: badgeFloat 3s ease-in-out infinite;
    }

    @keyframes badgeFloat {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-3px);
        }
    }

    .product-card:hover .badge {
        transform: scale(1.1);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    /* Animaciones para precios */
    .product-card .h5 {
        transition: all 0.3s ease;
    }

    .product-card:hover .h5 {
        transform: scale(1.1);
        color: #2563eb !important;
    }

    /* Animaciones para títulos de sección */
    section h2 {
        position: relative;
        display: inline-block;
        transition: all 0.3s ease;
    }

    section h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #2563eb);
        transition: width 0.5s ease;
        border-radius: 2px;
    }

    .fade-in-down.animated section h2::after,
    section:hover h2::after {
        width: 80px;
    }

    /* Animaciones para textos */
    section p.text-muted {
        transition: all 0.3s ease;
    }

    section:hover p.text-muted {
        color: #6b7280 !important;
    }

    /* Efecto de parallax suave en scroll */
    @media (prefers-reduced-motion: no-preference) {
        .hero-section-wrapper {
            transition: transform 0.3s ease-out;
        }
    }

    /* Animaciones para botones del carrusel mejoradas */
    .hero-btn {
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hero-btn::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }

    .hero-btn:hover::after {
        width: 400px;
        height: 400px;
    }

    .hero-btn:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    /* Animación para el welcome card */
    .welcome-card {
        animation: welcomeSlideIn 0.8s ease-out 1.5s forwards;
        opacity: 0;
        transform: translateX(-20px);
    }

    @keyframes welcomeSlideIn {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .welcome-avatar {
        animation: avatarSpin 3s ease-in-out infinite;
    }

    @keyframes avatarSpin {
        0%, 100% {
            transform: rotate(0deg);
        }
        25% {
            transform: rotate(-5deg);
        }
        75% {
            transform: rotate(5deg);
        }
    }

    /* Animaciones para las estrellas de rating */
    .text-warning i {
        transition: all 0.3s ease;
        display: inline-block;
    }

    .product-card:hover .text-warning i {
        animation: starTwinkle 0.6s ease-in-out;
        transform: scale(1.2);
    }

    @keyframes starTwinkle {
        0%, 100% {
            transform: scale(1) rotate(0deg);
        }
        50% {
            transform: scale(1.3) rotate(180deg);
        }
    }

    /* Efecto de brillo en hover para cards */
    .product-card,
    .feature-card {
        position: relative;
    }

    .product-card::before,
    .feature-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s ease;
        pointer-events: none;
    }

    .product-card:hover::before,
    .feature-card:hover::before {
        opacity: 1;
        animation: shine 1.5s ease-in-out;
    }

    @keyframes shine {
        0% {
            transform: translate(-50%, -50%) rotate(0deg);
        }
        100% {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    }

    /* Animación de carga para imágenes */
    .product-image,
    .carousel-image {
        animation: imageLoad 0.6s ease-out;
    }

    @keyframes imageLoad {
        from {
            opacity: 0;
            filter: blur(10px);
        }
        to {
            opacity: 1;
            filter: blur(0);
        }
    }

    /* Efecto de ondas en hover para botones */
    .btn-enhanced::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease, opacity 0.6s ease;
        opacity: 0;
    }

    .btn-enhanced:hover::after {
        width: 300px;
        height: 300px;
        opacity: 1;
    }

    /* Animación de pulso para elementos importantes */
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.05);
            opacity: 0.9;
        }
    }

    .product-card .badge.bg-danger {
        animation: pulse 2s ease-in-out infinite;
    }

    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }

    /* Reducir animaciones para usuarios que prefieren menos movimiento */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Array de colores para los fondos y bordes (uno por cada producto)
        const colorSchemes = [
            { background: 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%)', border: '#1e3a8a' },  // Azul
            { background: 'linear-gradient(135deg, #10b981 0%, #34d399 100%)', border: '#10b981' },  // Verde
            { background: 'linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%)', border: '#f59e0b' },  // Ámbar
            { background: 'linear-gradient(135deg, #ef4444 0%, #f87171 100%)', border: '#ef4444' },  // Rojo
            { background: 'linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%)', border: '#8b5cf6' },  // Púrpura
            { background: 'linear-gradient(135deg, #06b6d4 0%, #22d3ee 100%)', border: '#06b6d4' },  // Cian
            { background: 'linear-gradient(135deg, #ec4899 0%, #f472b6 100%)', border: '#ec4899' },  // Rosa
            { background: 'linear-gradient(135deg, #14b8a6 0%, #2dd4bf 100%)', border: '#14b8a6' },  // Turquesa
            { background: 'linear-gradient(135deg, #f97316 0%, #fb923c 100%)', border: '#f97316' },  // Naranja
            { background: 'linear-gradient(135deg, #6366f1 0%, #818cf8 100%)', border: '#6366f1' }   // Índigo
        ];

        // Inicializar el carrusel de Bootstrap
        const carouselElement = document.querySelector('#productCarousel');
        const heroSection = document.querySelector('#heroSection');
        
        if (carouselElement) {
            const carousel = new bootstrap.Carousel(carouselElement, {
                interval: 5000, // 5 segundos
                wrap: true,
                keyboard: true,
                pause: 'hover'
            });

            // Función para actualizar los colores del fondo y borde
            function updateColors(activeIndex) {
                const colorIndex = activeIndex % colorSchemes.length;
                const colors = colorSchemes[colorIndex];
                
                // Actualizar el fondo de la sección hero
                if (heroSection) {
                    heroSection.style.background = colors.background;
                    heroSection.style.transition = 'background 0.8s ease';
                }
                
                // Actualizar el borde del panel de imagen
                const panels = document.querySelectorAll('.carousel-image-panel');
                panels.forEach(function(panel, index) {
                    if (index === activeIndex) {
                        panel.style.borderColor = colors.border;
                        panel.classList.add('active');
                    } else {
                        panel.classList.remove('active');
                    }
                });
            }

            // Actualizar los colores cuando cambia el slide
            carouselElement.addEventListener('slid.bs.carousel', function(event) {
                const activeIndex = event.to;
                updateColors(activeIndex);
            });

            // Inicializar los colores del primer slide
            updateColors(0);

            // Asegurar que los botones funcionen correctamente
            const prevButton = carouselElement.querySelector('.carousel-control-prev');
            const nextButton = carouselElement.querySelector('.carousel-control-next');
            const indicators = carouselElement.querySelectorAll('.carousel-indicators button');

            if (prevButton) {
                prevButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    carousel.prev();
                });
            }

            if (nextButton) {
                nextButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    carousel.next();
                });
            }

            // Asegurar que los indicadores funcionen
            indicators.forEach(function(indicator, index) {
                indicator.addEventListener('click', function(e) {
                    e.preventDefault();
                    carousel.to(index);
                });
            });

            // Pausar el carrusel cuando el usuario interactúa con los botones
            const carouselButtons = carouselElement.querySelectorAll('.btn');
            carouselButtons.forEach(function(button) {
                button.addEventListener('mouseenter', function() {
                    carousel.pause();
                });
                button.addEventListener('mouseleave', function() {
                    carousel.cycle();
                });
            });
        }

        // Funcionalidad de favoritos
        window.toggleFavorite = async function(productId) {
            const btn = event.target.closest('.product-favorite-btn');
            const isFavorite = btn.classList.contains('favorite-active');
            
            try {
                const method = isFavorite ? 'DELETE' : 'POST';
                const response = await fetch(`/favoritos/${productId}`, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                // Verificar si la respuesta es JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    // Si no es JSON, probablemente es una redirección de autenticación
                    if (response.status === 401 || response.status === 403) {
                        showNotification('Debes iniciar sesión para agregar favoritos', 'warning');
                        return;
                    }
                    // Si es HTML (redirección), mostrar mensaje
                    if (contentType && contentType.includes('text/html')) {
                        showNotification('Debes iniciar sesión para agregar favoritos', 'warning');
                        return;
                    }
                    throw new Error('Respuesta inesperada del servidor');
                }

                const data = await response.json();
                
                if (data.success) {
                    if (isFavorite) {
                        btn.classList.remove('favorite-active');
                        btn.classList.remove('btn-danger');
                        btn.classList.add('btn-light');
                    } else {
                        btn.classList.add('favorite-active');
                        btn.classList.remove('btn-light');
                        btn.classList.add('btn-danger');
                    }
                    if (data.message) {
                        showNotification(data.message, isFavorite ? 'info' : 'success');
                    }
                } else if (data.message) {
                    showNotification(data.message, 'warning');
                }
            } catch (error) {
                console.error('Error:', error);
                // Solo mostrar error si no es un error de autenticación
                if (!error.message.includes('JSON')) {
                    showNotification('Debes iniciar sesión para agregar favoritos', 'warning');
                }
            }
        };

        // Cargar estado de favoritos al cargar la página
        @auth
        document.addEventListener('DOMContentLoaded', function() {
            const favoriteButtons = document.querySelectorAll('.product-favorite-btn[data-product-id]');
            
            favoriteButtons.forEach(btn => {
                const productId = btn.getAttribute('data-product-id');
                
                // Verificar si está en favoritos
                fetch(`/favoritos/verificar/${productId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        }
                        return { is_favorite: false };
                    })
                    .then(data => {
                        if (data.is_favorite) {
                            btn.classList.add('favorite-active');
                            btn.classList.remove('btn-light');
                            btn.classList.add('btn-danger');
                        }
                    })
                    .catch(error => {
                        // Silenciar errores de verificación para no molestar al usuario
                        console.debug('No se pudo verificar favorito:', error);
                    });
            });
        });
        @endauth
    });
</script>
@endpush
@endsection
