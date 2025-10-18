@extends('layouts.app')

@section('title', $product->name . ' - DigitalXpress')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-6">
            <div class="position-relative">
                <img src="https://images.unsplash.com/photo-{{ rand(1500000000000, 1600000000000) }}?w=600&h=400&fit=crop" 
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

            @if($product->attributes)
            <div class="mb-4">
                <h5>Especificaciones</h5>
                <div class="row">
                    @foreach($product->attributes as $key => $value)
                    <div class="col-6 mb-2">
                        <strong>{{ ucfirst($key) }}:</strong> {{ $value }}
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

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
    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h3 class="fw-bold mb-4">Productos Relacionados</h3>
        <div class="row g-4">
            @foreach($relatedProducts as $relatedProduct)
            <div class="col-lg-3 col-md-6">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-{{ rand(1500000000000, 1600000000000) }}?w=300&h=200&fit=crop" 
                             class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-2">
                            <button class="btn btn-light btn-sm rounded-circle">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <div class="mb-2">
                            <span class="badge bg-secondary">{{ $relatedProduct->category->name }}</span>
                        </div>
                        <h6 class="card-title fw-bold">{{ $relatedProduct->name }}</h6>
                        <p class="card-text text-muted small">{{ Str::limit($relatedProduct->short_description, 80) }}</p>
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
@endsection
