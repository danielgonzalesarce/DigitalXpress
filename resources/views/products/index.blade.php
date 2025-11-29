@extends('layouts.app')

@section('title', 'Productos - DigitalXpress')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold">Nuestros Productos</h1>
            <p class="text-muted">Descubre la mejor tecnología a precios increíbles</p>
            @auth
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="fas fa-shopping-cart me-2"></i>
                    <div>
                        <strong>¡Hola {{ Auth::user()->name }}! Agrega productos a tu carrito</strong> 
                        <span class="d-block small">Selecciona los productos que te gusten y agrégalos a tu carrito para comprarlos.</span>
                    </div>
                </div>
            @else
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="fas fa-user me-2"></i>
                    <div>
                        <strong>¡Inicia sesión para comprar!</strong> 
                        <span class="d-block small">Necesitas estar registrado para agregar productos a tu carrito.</span>
                    </div>
                </div>
            @endauth
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                Explorar Todos los Productos
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <div class="d-flex align-items-center">
                    <i class="fas fa-filter me-2 text-muted"></i>
                    <select id="priceFilter" name="price_range" class="form-select filter-select" style="width: auto; min-width: 180px;">
                        <option value="">Todos los precios</option>
                        <option value="under_100" {{ request('price_range') == 'under_100' ? 'selected' : '' }}>Menos de $100</option>
                        <option value="100_500" {{ request('price_range') == '100_500' ? 'selected' : '' }}>$100 - $500</option>
                        <option value="500_1000" {{ request('price_range') == '500_1000' ? 'selected' : '' }}>$500 - $1000</option>
                        <option value="over_1000" {{ request('price_range') == 'over_1000' ? 'selected' : '' }}>Más de $1000</option>
                    </select>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-tags me-2 text-muted"></i>
                    <select id="categoryFilter" name="category" class="form-select filter-select" style="width: auto; min-width: 180px;">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-flex gap-2 justify-content-end">
                <button id="gridViewBtn" class="btn btn-outline-secondary view-toggle-btn active" data-view="grid" aria-label="Vista de cuadrícula">
                    <i class="fas fa-th"></i>
                </button>
                <button id="listViewBtn" class="btn btn-outline-secondary view-toggle-btn" data-view="list" aria-label="Vista de lista">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Results Count -->
    <div class="mb-4">
        <p class="text-muted">{{ $products->total() }} productos encontrados</p>
    </div>

    <!-- Products Container -->
    <div id="productsContainer" class="products-container">
        <!-- Grid View -->
        <div id="gridView" class="products-grid view-active">
            <div class="row g-4">
                @forelse($products as $product)
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
                            @if($product->is_featured)
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-success">Destacado</span>
                            </div>
                            @endif
                            @if($product->stock_quantity < 10)
                            <div class="position-absolute bottom-0 start-0 m-2">
                                <span class="badge bg-danger">Pocas unidades</span>
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
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No se encontraron productos</h4>
                        <p class="text-muted">Intenta con otros criterios de búsqueda</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            Ver Todos los Productos
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- List View -->
        <div id="listView" class="products-list">
            @forelse($products as $product)
            <div class="card product-list-item mb-3">
                <div class="row g-0">
                    <div class="col-md-3">
                        <div class="position-relative h-100 product-image-container">
                            <a href="{{ route('products.show', $product) }}" class="product-image-link h-100 d-block">
                                <img src="{{ $product->image_url }}" 
                                     class="img-fluid rounded-start product-image" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; min-height: 200px;">
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
                            @if($product->is_featured)
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-success">Destacado</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card-body h-100 d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
                                    <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                                    <p class="card-text text-muted mb-3">{{ $product->short_description ?? Str::limit($product->description, 150) }}</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="text-warning me-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $product->rating ? '' : '-o' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted me-3">({{ $product->review_count }} reseñas)</small>
                                <small class="text-muted">Stock: {{ $product->stock_quantity }}</small>
                            </div>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <div>
                                    @if($product->is_on_sale)
                                        <span class="h4 text-success fw-bold me-2">${{ number_format($product->sale_price, 2) }}</span>
                                        <small class="text-decoration-line-through text-muted">${{ number_format($product->price, 2) }}</small>
                                    @else
                                        <span class="h4 text-primary fw-bold">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-primary">
                                    <i class="fas fa-shopping-cart me-1"></i> Agregar al Carrito
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No se encontraron productos</h4>
                    <p class="text-muted">Intenta con otros criterios de búsqueda</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        Ver Todos los Productos
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="mt-5">
        {{ $products->links() }}
    </div>
    @endif
</div>

<style>
    /* Estilos para los botones de vista */
    .view-toggle-btn {
        min-width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .view-toggle-btn:hover {
        border-color: var(--primary-color, #0d6efd);
        color: var(--primary-color, #0d6efd);
        background-color: #f8f9fa;
    }

    .view-toggle-btn.active {
        background-color: var(--primary-color, #0d6efd);
        border-color: var(--primary-color, #0d6efd);
        color: #ffffff;
    }

    /* Contenedor de productos */
    .products-container {
        position: relative;
        min-height: 400px;
    }

    /* Vista de cuadrícula */
    .products-grid {
        display: block;
        opacity: 1;
        transition: opacity 0.3s ease;
    }

    .products-grid.view-hidden {
        display: none;
    }

    /* Vista de lista */
    .products-list {
        display: none;
    }

    .products-list.view-active {
        display: block;
    }

    /* Estilos para items de lista */
    .product-list-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .product-list-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .product-list-item .card-body {
        padding: 1.5rem;
    }

    @media (max-width: 768px) {
        .products-list .col-md-3 {
            max-height: 200px;
        }

        .products-list .col-md-9 {
            padding: 1rem;
        }
    }

    /* Estilos para filtros */
    .filter-select {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .filter-select:hover {
        border-color: var(--primary-color, #0d6efd);
    }

    .filter-select:focus {
        border-color: var(--primary-color, #0d6efd);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        outline: 0;
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
        pointer-events: auto;
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');

    // Cargar preferencia guardada
    const savedView = localStorage.getItem('productView') || 'grid';
    
    // Función para cambiar vista
    function switchView(view) {
        if (view === 'grid') {
            gridView.classList.add('view-active');
            gridView.classList.remove('view-hidden');
            listView.classList.remove('view-active');
            listView.style.display = 'none';
            
            gridViewBtn.classList.add('active');
            listViewBtn.classList.remove('active');
        } else {
            listView.classList.add('view-active');
            listView.style.display = 'block';
            gridView.classList.remove('view-active');
            gridView.classList.add('view-hidden');
            
            listViewBtn.classList.add('active');
            gridViewBtn.classList.remove('active');
        }
        
        // Guardar preferencia
        localStorage.setItem('productView', view);
    }

    // Escuchadores de eventos
    gridViewBtn.addEventListener('click', function() {
        switchView('grid');
    });

    listViewBtn.addEventListener('click', function() {
        switchView('list');
    });

    // Aplicar vista guardada al cargar
    switchView(savedView);

    // Funcionalidad de filtros automáticos
    const priceFilter = document.getElementById('priceFilter');
    const categoryFilter = document.getElementById('categoryFilter');

    function applyFilters() {
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);

        // Actualizar parámetro de precio
        const priceValue = priceFilter.value;
        if (priceValue) {
            params.set('price_range', priceValue);
        } else {
            params.delete('price_range');
        }

        // Actualizar parámetro de categoría
        const categoryValue = categoryFilter.value;
        if (categoryValue) {
            params.set('category', categoryValue);
        } else {
            params.delete('category');
        }

        // Resetear a página 1 cuando se cambian los filtros
        params.delete('page');

        // Redirigir con los nuevos parámetros
        window.location.href = url.pathname + '?' + params.toString();
    }

    // Escuchadores de eventos para filtros
    priceFilter.addEventListener('change', function() {
        applyFilters();
    });

    categoryFilter.addEventListener('change', function() {
        applyFilters();
    });

    // Funcionalidad de favoritos
    async function toggleFavorite(productId) {
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
                // Si es HTML (redirección), recargar la página
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
                
                // Actualizar contador de favoritos inmediatamente
                if (data.favorites_count !== undefined) {
                    const favoritesBadge = document.querySelector('.nav-link[href*="favoritos"] .badge');
                    if (data.favorites_count > 0) {
                        if (favoritesBadge) {
                            favoritesBadge.textContent = data.favorites_count;
                        } else {
                            const favoritesLink = document.querySelector('.nav-link[href*="favoritos"]');
                            if (favoritesLink) {
                                const badge = document.createElement('span');
                                badge.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                                badge.style.fontSize = '0.7rem';
                                badge.textContent = data.favorites_count;
                                favoritesLink.appendChild(badge);
                            }
                        }
                    } else {
                        if (favoritesBadge) {
                            favoritesBadge.remove();
                        }
                    }
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
    }

    // Cargar estado de favoritos al cargar la página
    @auth
    const favoriteButtons = document.querySelectorAll('.product-favorite-btn[data-product-id]');
    
    if (favoriteButtons.length > 0) {
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
    }
    @endauth
});
</script>
@endsection
