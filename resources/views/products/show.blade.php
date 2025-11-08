@extends('layouts.app')

@section('title', $product->name . ' - DigitalXpress')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-6">
            <div class="position-relative">
                <img src="{{ $product->image_url }}" 
                     class="img-fluid rounded-3 shadow" alt="{{ $product->name }}">
                @if($product->is_featured)
                <div class="position-absolute top-0 start-0 m-3">
                    <span class="badge bg-success fs-6">Destacado</span>
                </div>
                @endif
                @if($product->is_on_sale)
                <div class="position-absolute top-0 end-0 m-3">
                    <span class="badge bg-danger fs-6">Oferta</span>
                </div>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mb-3">
                <span class="badge bg-secondary fs-6">{{ $product->category->name }}</span>
            </div>
            <h1 class="fw-bold mb-3">{{ $product->name }}</h1>
            
            <div class="d-flex align-items-center mb-3">
                <div class="text-warning me-3">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star{{ $i <= $product->rating ? '' : '-o' }}"></i>
                    @endfor
                </div>
                <span class="text-muted">({{ $product->review_count }} reseñas)</span>
            </div>

            <div class="mb-4">
                @if($product->is_on_sale)
                    <div class="d-flex align-items-center">
                        <span class="h2 text-success fw-bold me-3">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="text-decoration-line-through text-muted">${{ number_format($product->price, 2) }}</span>
                    </div>
                @else
                    <span class="h2 text-primary fw-bold">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <div class="mb-4">
                <p class="text-muted">{{ $product->description }}</p>
            </div>

            <div class="mb-4">
                <div class="row">
                    <div class="col-6">
                        <strong>Stock disponible:</strong>
                        <span class="text-{{ $product->stock_quantity > 10 ? 'success' : ($product->stock_quantity > 0 ? 'warning' : 'danger') }}">
                            {{ $product->stock_quantity }} unidades
                        </span>
                    </div>
                    <div class="col-6">
                        <strong>SKU:</strong> {{ $product->sku }}
                    </div>
                </div>
            </div>

            <!-- Especificaciones Técnicas -->
            <div class="mb-4">
                <h4 class="fw-bold mb-3">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    Especificaciones Técnicas
                </h4>
                @if($product->attributes && count($product->attributes) > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($product->attributes as $key => $value)
                            <div class="col-md-6">
                                <div class="specification-item p-3 border rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="spec-icon me-3">
                                            <i class="fas fa-check-circle text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <strong class="text-dark d-block mb-1">{{ ucfirst(str_replace('_', ' ', $key)) }}</strong>
                                            <span class="text-muted">{{ $value }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Las especificaciones técnicas de este producto estarán disponibles próximamente.
                </div>
                @endif
            </div>

            <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label for="quantity" class="form-label">Cantidad:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" 
                               value="1" min="1" max="{{ $product->stock_quantity }}">
                    </div>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary btn-lg w-100" 
                                {{ $product->stock_quantity == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart me-2"></i>
                            {{ $product->stock_quantity > 0 ? 'Agregar al Carrito' : 'Sin Stock' }}
                        </button>
                    </div>
                </div>
            </form>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-heart me-1"></i> Favoritos
                </button>
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-share me-1"></i> Compartir
                </button>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts && $relatedProducts->count() > 0)
    <div class="mt-5 pt-4 border-top">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">
                <i class="fas fa-th-large text-primary me-2"></i>
                Productos Relacionados
            </h3>
            <span class="badge bg-primary">{{ $relatedProducts->count() }} producto{{ $relatedProducts->count() > 1 ? 's' : '' }} de {{ $product->category->name }}</span>
        </div>
        <p class="text-muted mb-4">Otros productos de la misma categoría que te pueden interesar</p>
        <div class="row g-4">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-6">
                <div class="card product-card h-100">
                    <div class="position-relative product-image-container">
                        <a href="{{ route('products.show', $relatedProduct) }}" class="product-image-link">
                            <img src="{{ $relatedProduct->image_url }}" 
                                 class="card-img-top product-image" alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                            <div class="product-overlay">
                                <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-primary btn-view-details">
                                    <i class="fas fa-eye me-2"></i> Ver más detalles
                                </a>
                            </div>
                        </a>
                        <div class="position-absolute top-0 end-0 m-2">
                            <button class="btn btn-light btn-sm rounded-circle product-favorite-btn">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                        @if($relatedProduct->is_featured)
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge bg-success">Destacado</span>
                        </div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge bg-secondary">{{ $relatedProduct->category->name }}</span>
                        </div>
                        <h6 class="card-title fw-bold">{{ $relatedProduct->name }}</h6>
                        <p class="card-text text-muted small">{{ Str::limit($relatedProduct->short_description ?? $relatedProduct->description, 80) }}</p>
                        <div class="d-flex align-items-center mb-2">
                            <div class="text-warning me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $relatedProduct->rating ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted">({{ $relatedProduct->review_count }})</small>
                        </div>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    @if($relatedProduct->is_on_sale)
                                        <span class="h6 text-success fw-bold">${{ number_format($relatedProduct->sale_price, 2) }}</span>
                                        <small class="text-decoration-line-through text-muted">${{ number_format($relatedProduct->price, 2) }}</small>
                                    @else
                                        <span class="h6 text-primary fw-bold">${{ number_format($relatedProduct->price, 2) }}</span>
                                    @endif
                                </div>
                                <small class="text-muted">Stock: {{ $relatedProduct->stock_quantity }}</small>
                            </div>
                            <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-primary w-100">
                                <i class="fas fa-shopping-cart me-1"></i> Ver Producto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
    /* Estilos para especificaciones técnicas */
    .specification-item {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef !important;
        transition: all 0.3s ease;
        height: 100%;
    }

    .specification-item:hover {
        background-color: #ffffff;
        border-color: var(--primary-color, #0d6efd) !important;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.1);
        transform: translateY(-2px);
    }

    .spec-icon {
        font-size: 1.25rem;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 50%;
        flex-shrink: 0;
    }

    .specification-item strong {
        font-size: 0.9rem;
        color: #212529;
    }

    .specification-item span {
        font-size: 0.85rem;
    }

    @media (max-width: 768px) {
        .specification-item {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endsection
