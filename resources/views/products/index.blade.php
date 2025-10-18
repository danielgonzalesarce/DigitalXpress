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
            <div class="d-flex gap-3 align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-filter me-2"></i>
                    <select class="form-select" style="width: auto;">
                        <option>Todos los precios</option>
                        <option>Menos de $100</option>
                        <option>$100 - $500</option>
                        <option>$500 - $1000</option>
                        <option>Más de $1000</option>
                    </select>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-sort me-2"></i>
                    <select class="form-select" style="width: auto;">
                        <option value="name">Nombre</option>
                        <option value="price">Precio</option>
                        <option value="rating">Calificación</option>
                        <option value="newest">Más recientes</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-flex gap-2 justify-content-end">
                <button class="btn btn-outline-secondary active">
                    <i class="fas fa-th"></i>
                </button>
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Results Count -->
    <div class="mb-4">
        <p class="text-muted">{{ $products->total() }} productos encontrados</p>
    </div>

    <!-- Products Grid -->
    <div class="row g-4">
        @forelse($products as $product)
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

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="d-flex justify-content-center mt-5">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
