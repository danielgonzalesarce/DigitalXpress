@extends('layouts.admin')

@section('title', 'Editar Inventario')
@section('page-title', 'Editar Inventario')
@section('page-subtitle', 'Modificar stock del producto • DigitalXpress')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="orders-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Editar Inventario: {{ $product->name }}</h3>
                    <a href="{{ route('admin.inventory') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Volver
                    </a>
                </div>

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('admin.inventory.update', $product) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Información del Producto</h5>

                            <div class="mb-3">
                                <label class="form-label">Producto</label>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                         class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                                    <div>
                                        <strong>{{ $product->name }}</strong><br>
                                        <small class="text-muted">SKU: {{ $product->sku }}</small><br>
                                        <small class="text-muted">Categoría: {{ $product->category->name }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="stock_quantity" class="form-label">Cantidad en Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                       id="stock_quantity" name="stock_quantity" 
                                       value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                                       min="0" required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" 
                                           id="in_stock" name="in_stock" 
                                           {{ old('in_stock', $product->in_stock) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="in_stock">
                                        Producto en Stock
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Si está desactivado, el producto se marcará como "Sin Stock" independientemente de la cantidad.
                                </small>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Información:</strong> El stock se actualizará directamente. 
                                Para registrar movimientos de inventario, usa la opción "Registrar Movimiento".
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.inventory') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Actualizar Inventario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

