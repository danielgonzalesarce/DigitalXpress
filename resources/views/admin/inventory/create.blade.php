@extends('layouts.admin')

@section('title', 'Registrar Movimiento de Inventario')
@section('page-title', 'Registrar Movimiento')
@section('page-subtitle', 'Agregar o modificar stock de productos • DigitalXpress')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="orders-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Nuevo Movimiento de Inventario</h3>
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

                <form action="{{ route('admin.inventory.store') }}" method="POST">
                    @csrf

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Información del Movimiento</h5>

                            <div class="mb-3">
                                <label for="product_id" class="form-label">Producto <span class="text-danger">*</span></label>
                                <select class="form-select @error('product_id') is-invalid @enderror" 
                                        id="product_id" name="product_id" required>
                                    <option value="">Selecciona un producto</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" 
                                                data-stock="{{ $product->stock_quantity }}"
                                                {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (Stock actual: {{ $product->stock_quantity }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="movement_type" class="form-label">Tipo de Movimiento <span class="text-danger">*</span></label>
                                <select class="form-select @error('movement_type') is-invalid @enderror" 
                                        id="movement_type" name="movement_type" required>
                                    <option value="add" {{ old('movement_type') == 'add' ? 'selected' : '' }}>Agregar Stock</option>
                                    <option value="subtract" {{ old('movement_type') == 'subtract' ? 'selected' : '' }}>Restar Stock</option>
                                    <option value="set" {{ old('movement_type') == 'set' ? 'selected' : '' }}>Establecer Stock</option>
                                </select>
                                @error('movement_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    <strong>Agregar:</strong> Suma la cantidad al stock actual<br>
                                    <strong>Restar:</strong> Resta la cantidad del stock actual<br>
                                    <strong>Establecer:</strong> Define el stock exacto
                                </small>
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Cantidad <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" name="quantity" value="{{ old('quantity') }}" 
                                       min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted" id="quantityHelp">
                                    Ingresa la cantidad a agregar, restar o establecer
                                </small>
                            </div>

                            <div class="mb-3">
                                <label for="reason" class="form-label">Razón del Movimiento <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('reason') is-invalid @enderror" 
                                       id="reason" name="reason" value="{{ old('reason') }}" 
                                       placeholder="Ej: Compra de proveedor, Devolución, Ajuste de inventario" required>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notas</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info" id="stockPreview" style="display: none;">
                                <strong>Vista Previa:</strong>
                                <div id="previewContent"></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.inventory') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Registrar Movimiento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product_id');
        const movementType = document.getElementById('movement_type');
        const quantityInput = document.getElementById('quantity');
        const previewDiv = document.getElementById('stockPreview');
        const previewContent = document.getElementById('previewContent');
        
        function updatePreview() {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const currentStock = parseInt(selectedOption?.dataset.stock) || 0;
            const quantity = parseInt(quantityInput.value) || 0;
            const type = movementType.value;
            
            if (productSelect.value && quantity > 0) {
                let newStock = currentStock;
                let operation = '';
                
                switch(type) {
                    case 'add':
                        newStock = currentStock + quantity;
                        operation = `${currentStock} + ${quantity}`;
                        break;
                    case 'subtract':
                        newStock = Math.max(0, currentStock - quantity);
                        operation = `${currentStock} - ${quantity}`;
                        break;
                    case 'set':
                        newStock = quantity;
                        operation = `Establecer a ${quantity}`;
                        break;
                }
                
                previewContent.innerHTML = `
                    Stock actual: <strong>${currentStock}</strong><br>
                    Operación: <strong>${operation}</strong><br>
                    Nuevo stock: <strong class="text-primary">${newStock}</strong>
                `;
                previewDiv.style.display = 'block';
            } else {
                previewDiv.style.display = 'none';
            }
        }
        
        productSelect.addEventListener('change', updatePreview);
        movementType.addEventListener('change', updatePreview);
        quantityInput.addEventListener('input', updatePreview);
    });
    </script>
@endsection

