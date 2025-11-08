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
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

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
        <p class="text-muted">{{ $products->total() }} productos encontrados</p>
    </div>

    <!-- Products Container -->
    <div id="productsContainer" class="products-container">
        <!-- Grid View -->
        <div id="gridView" class="products-grid view-active">
            <div class="row g-4">
                @forelse($products as $product)
                <div class="col-lg-3 col-md-6">
                    <div class="card product-card h-100" style="cursor: pointer;" onclick="window.location.href='{{ route('products.show', $product) }}'">
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
                                <button class="btn btn-light btn-sm rounded-circle product-favorite-btn" onclick="event.stopPropagation();">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            @if($product->is_featured)
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-success">Destacado</span>
                            </div>
                            @endif
                            @if($product->stock_quantity < 10)
                            <div class="position-absolute bottom-0 start-0 m-2">
                                <span class="badge bg-warning">Stock bajo</span>
                            </div>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                            </div>
                            <h6 class="card-title fw-bold">{{ $product->name }}</h6>
                            <p class="card-text text-muted small">{{ Str::limit($product->short_description ?? $product->description, 80) }}</p>
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
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation();">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-warning" onclick="event.stopPropagation();">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onclick="event.stopPropagation();">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation(); showConfirmModal('¿Estás seguro de eliminar este producto?', '{{ route('admin.products.destroy', $product) }}', 'DELETE')">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
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
            <div class="card product-list-item mb-3" style="cursor: pointer;" onclick="window.location.href='{{ route('products.show', $product) }}'">
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
                                <button class="btn btn-light btn-sm rounded-circle product-favorite-btn" onclick="event.stopPropagation();">
                                    <i class="fas fa-heart"></i>
                                </button>
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
                                <div class="btn-group" role="group">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation();">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-warning" onclick="event.stopPropagation();">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onclick="event.stopPropagation();">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation(); showConfirmModal('¿Estás seguro de eliminar este producto?', '{{ route('admin.products.destroy', $product) }}', 'DELETE')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="mt-4">
        {{ $products->links() }}
    </div>
    @endif
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
</style>
@endpush

@push('scripts')
<script>
    // View Toggle
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const gridView = document.getElementById('gridView');
    const listView = document.getElementById('listView');

    // Load saved view preference
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

    // Auto-filter on change
    document.getElementById('priceFilter')?.addEventListener('change', function() {
        const url = new URL(window.location);
        if (this.value) {
            url.searchParams.set('price_range', this.value);
        } else {
            url.searchParams.delete('price_range');
        }
        window.location.href = url.toString();
    });

    document.getElementById('sortFilter')?.addEventListener('change', function() {
        const url = new URL(window.location);
        url.searchParams.set('sort', this.value);
        window.location.href = url.toString();
    });
</script>
@endpush

