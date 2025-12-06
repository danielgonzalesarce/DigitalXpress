@extends('layouts.admin')

@section('title', 'Gestión de Productos')
@section('page-title', 'Productos')
@section('page-subtitle', 'Gestiona tu catálogo de productos • DigitalXpress')

@section('content')
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold mb-2">Gestión de Productos</h2>
            <p class="text-muted mb-0">Administra tu catálogo de productos</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Crear Producto
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid #10b981;">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
            <div class="flex-grow-1">
                <strong>¡Éxito!</strong>
                <p class="mb-2">{{ session('success') }}</p>
                @if(session('changes') && count(session('changes')) > 0)
                <div class="mt-2">
                    <small class="text-muted"><strong>Cambios realizados:</strong></small>
                    <ul class="mb-0 mt-1" style="font-size: 0.9rem;">
                        @foreach(session('changes') as $change)
                        <li>{{ $change }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid #ef4444;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle fa-2x me-3 text-danger"></i>
            <div class="flex-grow-1">
                <strong>Error</strong>
                <p class="mb-0">{{ session('error') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-md-12 mb-3">
            <div class="d-flex gap-2">
                <div class="flex-grow-1">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               id="searchInput"
                               name="search" 
                               class="form-control" 
                               placeholder="Buscar por nombre o SKU..." 
                               value="{{ request('search') }}"
                               autocomplete="off">
                        <span class="input-group-text bg-white d-none" id="searchLoading">
                            <i class="fas fa-spinner fa-spin text-primary"></i>
                        </span>
                        @if(request('search'))
                        <a href="{{ route('admin.products') }}" class="input-group-text bg-white text-decoration-none" id="clearSearch">
                            <i class="fas fa-times text-danger"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
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
                    <i class="fas fa-sort me-2 text-muted"></i>
                    <select id="sortFilter" name="sort" class="form-select filter-select" style="width: auto; min-width: 180px;">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Precio</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Calificación</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más recientes</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-flex gap-2 justify-content-end">
                <button id="gridViewBtn" class="btn btn-primary view-toggle-btn active" data-view="grid" aria-label="Vista de cuadrícula">
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
        <p class="text-muted">{{ $products->count() }} productos encontrados</p>
    </div>

    <!-- Products Container -->
    <div id="productsContainer" class="products-container">
        <!-- Grid View -->
        <div id="gridView" class="products-grid view-active">
            <div class="row g-4">
                @forelse($products as $product)
                <div class="col-lg-3 col-md-6">
                    <div class="card product-card h-100 @if(session('updated_product_id') == $product->id) border-success border-2 shadow-sm @endif" 
                         @if(session('updated_product_id') == $product->id) style="animation: highlightProduct 2s ease-in-out;" @endif>
                        <div class="position-relative product-image-container">
                            <img src="{{ $product->image_url }}" 
                                 class="card-img-top product-image" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                            @if($product->is_featured)
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-success">Destacado</span>
                            </div>
                            @endif
                            @if($product->stock_quantity < 10)
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-warning">Stock bajo</span>
                            </div>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title fw-bold mb-3">{{ $product->name }}</h6>
                            <div class="mt-auto">
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="showConfirmModal('¿Estás seguro de eliminar el producto \"{{ $product->name }}\"? Esta acción no se puede deshacer.', '{{ route('admin.products.destroy', $product) }}', 'DELETE')">
                                        <i class="fas fa-trash me-1"></i> Eliminar
                                    </button>
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
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- List View -->
        <div id="listView" class="products-list">
            @forelse($products as $product)
            <div class="card product-list-item mb-3 @if(session('updated_product_id') == $product->id) border-success border-2 shadow-sm @endif"
                 @if(session('updated_product_id') == $product->id) style="animation: highlightProduct 2s ease-in-out;" @endif>
                <div class="row g-0">
                    <div class="col-md-3">
                        <div class="position-relative h-100 product-image-container">
                            <img src="{{ $product->image_url }}" 
                                 class="img-fluid rounded-start product-image" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; min-height: 200px;">
                            @if($product->is_featured)
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-success">Destacado</span>
                            </div>
                            @endif
                            @if($product->stock_quantity < 10)
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-warning">Stock bajo</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card-body h-100 d-flex flex-column">
                            <div class="mb-3">
                                <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                            </div>
                            <div class="mt-auto">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit me-1"></i> Editar
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="showConfirmModal('¿Estás seguro de eliminar el producto \"{{ $product->name }}\"? Esta acción no se puede deshacer.', '{{ route('admin.products.destroy', $product) }}', 'DELETE')">
                                        <i class="fas fa-trash me-1"></i> Eliminar
                                    </button>
                                </div>
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
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination removed - showing all products -->
@endsection

@push('styles')
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .product-image-container {
        overflow: hidden;
        border-radius: 8px 8px 0 0;
    }

    .product-image {
        transition: transform 0.3s ease, filter 0.3s ease;
    }

    .product-image-container:hover .product-image {
        transform: scale(1.1);
        filter: blur(2px);
    }

    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-image-container:hover .product-overlay {
        opacity: 1;
    }

    .products-list {
        display: none;
    }

    .products-list.view-active {
        display: block;
    }

    .products-grid {
        display: none;
    }

    .products-grid.view-active {
        display: block;
    }

    .view-toggle-btn.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .product-list-item {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .product-list-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Animación para resaltar producto actualizado */
    @keyframes highlightProduct {
        0% {
            background-color: rgba(16, 185, 129, 0.2);
            transform: scale(1);
        }
        50% {
            background-color: rgba(16, 185, 129, 0.4);
            transform: scale(1.02);
        }
        100% {
            background-color: transparent;
            transform: scale(1);
        }
    }
    
    .product-card.border-success,
    .product-list-item.border-success {
        transition: all 0.3s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    // Alternar vista
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');

    // Cargar preferencia de vista guardada
    const savedView = localStorage.getItem('adminProductsView') || 'grid';
    if (savedView === 'list') {
        gridView.classList.remove('view-active');
        listView.classList.add('view-active');
        gridViewBtn.classList.remove('active');
        listViewBtn.classList.add('active');
    }

    gridViewBtn.addEventListener('click', function() {
        gridView.classList.add('view-active');
        listView.classList.remove('view-active');
        gridViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
        localStorage.setItem('adminProductsView', 'grid');
    });

    listViewBtn.addEventListener('click', function() {
        gridView.classList.remove('view-active');
        listView.classList.add('view-active');
        gridViewBtn.classList.remove('active');
        listViewBtn.classList.add('active');
        localStorage.setItem('adminProductsView', 'list');
    });

    // Función para aplicar filtros automáticamente
    function applyFilters() {
        const url = new URL(window.location);
        const searchValue = document.getElementById('searchInput')?.value.trim();
        const priceFilter = document.getElementById('priceFilter')?.value;
        const sortFilter = document.getElementById('sortFilter')?.value;
        const searchLoading = document.getElementById('searchLoading');

        // Mostrar indicador de carga
        if (searchLoading) {
            searchLoading.classList.remove('d-none');
        }

        // Actualizar parámetros de búsqueda
        if (searchValue) {
            url.searchParams.set('search', searchValue);
        } else {
            url.searchParams.delete('search');
        }

        // Actualizar filtro de precio
        if (priceFilter) {
            url.searchParams.set('price_range', priceFilter);
        } else {
            url.searchParams.delete('price_range');
        }

        // Actualizar ordenamiento
        if (sortFilter) {
            url.searchParams.set('sort', sortFilter);
        } else {
            url.searchParams.delete('sort');
        }

        // Redirigir con los nuevos parámetros
        window.location.href = url.toString();
    }

    // Búsqueda automática con debounce (espera 500ms después de que el usuario deje de escribir)
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                applyFilters();
            }, 500); // Espera 500ms antes de aplicar el filtro
        });

        // También aplicar al presionar Enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimeout);
                applyFilters();
            }
        });
    }

    // Botón para limpiar búsqueda
    const clearSearchBtn = document.getElementById('clearSearch');
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('searchInput').value = '';
            applyFilters();
        });
    }

    // Filtrar automáticamente al cambiar precio
    document.getElementById('priceFilter')?.addEventListener('change', function() {
        applyFilters();
    });

    // Filtrar automáticamente al cambiar ordenamiento
    document.getElementById('sortFilter')?.addEventListener('change', function() {
        applyFilters();
    });
</script>
@endpush

