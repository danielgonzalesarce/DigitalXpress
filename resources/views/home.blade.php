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
                            <div class="mb-2">
                                @if($product->is_on_sale)
                    <span class="badge bg-success fs-6 px-3 py-2">Oferta Especial</span>
                                @else
                                    <span class="badge bg-primary fs-6 px-3 py-2">Producto Destacado</span>
                                @endif
                </div>
                            <h1 class="display-5 fw-bold mb-2">{{ $product->name }}</h1>
                            <p class="lead mb-2">{{ $product->category->name ?? 'Tecnología' }}</p>
                            <p class="mb-3 small">{{ Str::limit($product->short_description ?? $product->description, 100) }}</p>
                            <div class="d-flex align-items-center mb-3">
                                @if($product->is_on_sale)
                                    <span class="h3 text-success fw-bold me-3">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-decoration-line-through text-light">${{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="h3 text-success fw-bold me-3">${{ number_format($product->price, 2) }}</span>
                                @endif
                </div>
                            <div class="d-flex gap-3 mb-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-success btn-lg px-4">
                                    Ver Producto
                    </a>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-light btn-lg px-4">
                                    Ver Todos
                                </a>
                </div>
                @auth
                                <div class="mt-2">
                                    <div class="alert alert-light d-flex align-items-center py-2" role="alert">
                            <i class="fas fa-user-circle me-2 text-primary"></i>
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
                            <button class="btn btn-light btn-sm rounded-circle product-favorite-btn">
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
                            <button class="btn btn-light btn-sm rounded-circle product-favorite-btn">
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

<style>
    /* Contenedor wrapper para la sección hero con márgenes laterales */
    .hero-section-wrapper {
        padding: 0 1.5rem; /* Espacio blanco a los lados */
        margin: 0 auto;
        max-width: 100%;
    }

    /* Sección hero con borde y espacio blanco */
    .hero-section-wrapper .hero-section {
        border-radius: 20px; /* Bordes redondeados */
        margin: 2rem auto; /* Márgenes superior e inferior y centrado */
        max-width: 1400px; /* Ancho máximo del contenido */
        overflow: hidden;
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
        overflow: hidden;
    }

    /* Panel con borde para la imagen del producto */
    .carousel-image-panel {
        background: #000000;
        border-radius: 20px;
        padding: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: all 0.5s ease;
        border: 4px solid #1e3a8a;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        width: 100%;
        max-width: 500px; /* Ancho máximo fijo para el panel */
        height: 500px; /* Altura fija para el panel */
        margin: 0 auto;
    }

    .carousel-image-panel.active {
        border-color: #1e3a8a;
    }

    #productCarousel .carousel-item {
        height: 600px; /* Altura fija para cada slide */
        padding: 2rem 0;
    }

    #productCarousel .carousel-inner {
        height: 600px; /* Altura fija para el contenedor interno */
    }

    #productCarousel .carousel-image {
        width: 100%;
        height: 440px; /* Altura fija para las imágenes (500px panel - 60px padding) */
        object-fit: contain; /* Mantiene la proporción sin recortar */
        transition: transform 0.3s ease;
        display: block;
    }

    #productCarousel .carousel-image:hover {
        transform: scale(1.05);
    }

    #productCarousel .carousel-control-prev,
    #productCarousel .carousel-control-next {
        width: 50px;
        height: 50px;
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    #productCarousel .carousel-control-prev:hover,
    #productCarousel .carousel-control-next:hover {
        background-color: rgba(255, 255, 255, 0.4);
        opacity: 1;
    }

    #productCarousel .carousel-control-prev {
        left: 20px;
    }

    #productCarousel .carousel-control-next {
        right: 20px;
    }

    #productCarousel .carousel-indicators {
        bottom: 20px;
    }

    #productCarousel .carousel-indicators button {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.5);
        border: 2px solid rgba(255, 255, 255, 0.8);
        margin: 0 5px;
        transition: all 0.3s ease;
    }

    #productCarousel .carousel-indicators button.active {
        background-color: rgba(255, 255, 255, 1);
        transform: scale(1.2);
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
    }

    .product-favorite-btn:hover {
        background-color: #dc3545 !important;
        color: white !important;
        transform: scale(1.1);
    }

    .product-favorite-btn:hover i {
        color: white;
    }

    /* Asegurar que los badges estén sobre el overlay */
    .product-image-container .badge {
        z-index: 10;
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
    });
</script>
@endpush
@endsection
