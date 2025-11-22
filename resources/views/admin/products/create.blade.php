@extends('layouts.admin')

@section('title', 'Crear Producto')
@section('page-title', 'Crear Producto')
@section('page-subtitle', 'Agregar un nuevo producto al catálogo • DigitalXpress')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="orders-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Nuevo Producto</h3>
                    <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Volver
                    </a>
                </div>

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">
                        <!-- Información Básica -->
                        <div class="col-lg-8">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Información Básica</h5>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Categoría <span class="text-danger">*</span></label>
                                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                                id="category_id" name="category_id" required>
                                            <option value="">Selecciona una categoría</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="short_description" class="form-label">Descripción Corta</label>
                                        <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                                  id="short_description" name="short_description" rows="2" maxlength="500">{{ old('short_description') }}</textarea>
                                        @error('short_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Máximo 500 caracteres</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Descripción Completa <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="image_files" class="form-label">Imágenes del Producto</label>
                                        <input type="file" class="form-control @error('image_files') is-invalid @enderror" 
                                               id="image_files" name="image_files[]" multiple accept="image/*" onchange="previewImages(this)">
                                        @error('image_files')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Puedes seleccionar múltiples imágenes. Formatos: JPEG, PNG, JPG, GIF, WEBP. Máximo 2MB por imagen.</small>
                                        
                                        <!-- Vista previa de nuevas imágenes seleccionadas -->
                                        <div id="newImagesPreview" class="mt-3" style="display: none;">
                                            <p class="mb-2"><strong>Imágenes seleccionadas:</strong></p>
                                            <div class="row g-2" id="newImagesContainer"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Precios y Stock -->
                        <div class="col-lg-4">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Precios y Stock</h5>

                                    <div class="mb-3">
                                        <label for="price" class="form-label">Precio <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" min="0" 
                                                   class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price') }}" required>
                                        </div>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="sale_price" class="form-label">Precio de Oferta</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" min="0" 
                                                   class="form-control @error('sale_price') is-invalid @enderror" 
                                                   id="sale_price" name="sale_price" value="{{ old('sale_price') }}">
                                        </div>
                                        @error('sale_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Opcional. Dejar vacío si no hay oferta</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                               id="sku" name="sku" value="{{ old('sku') }}" required>
                                        @error('sku')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Código único del producto</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="stock_quantity" class="form-label">Cantidad en Stock <span class="text-danger">*</span></label>
                                        <input type="number" min="0" 
                                               class="form-control @error('stock_quantity') is-invalid @enderror" 
                                               id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required>
                                        @error('stock_quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="in_stock" name="in_stock" 
                                                   {{ old('in_stock', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="in_stock">
                                                Disponible en Stock
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                                   {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                Producto Destacado
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                   {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Producto Activo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Crear Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .new-image-item .btn-danger {
        transition: all 0.3s ease;
    }
    .new-image-item .btn-danger:hover {
        transform: scale(1.1);
        box-shadow: 0 0 10px rgba(220, 53, 69, 0.5);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action*="store"]');
        const submitBtn = form ? form.querySelector('button[type="submit"]') : null;
        
        if (form && submitBtn) {
            form.addEventListener('submit', function(e) {
                // Deshabilitar el botón para evitar doble envío
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Creando...';
                
                // El formulario se enviará normalmente
            });
        }
    });

    // Función para previsualizar nuevas imágenes seleccionadas
    function previewImages(input) {
        const previewContainer = document.getElementById('newImagesContainer');
        const previewDiv = document.getElementById('newImagesPreview');
        
        if (!previewContainer || !previewDiv) return;
        
        previewContainer.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            previewDiv.style.display = 'block';
            
            Array.from(input.files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 new-image-item';
                        col.innerHTML = `
                            <div class="position-relative border rounded p-2" style="background-color: #f8f9fa;">
                                <img src="${e.target.result}" alt="Nueva imagen ${index + 1}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 shadow-sm" onclick="removeNewImage(this)" title="Eliminar imagen" style="z-index: 10;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                        previewContainer.appendChild(col);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            previewDiv.style.display = 'none';
        }
    }

    // Función para eliminar una nueva imagen seleccionada
    function removeNewImage(button) {
        const imageItem = button.closest('.new-image-item');
        const imageIndex = Array.from(imageItem.parentNode.children).indexOf(imageItem);
        
        // Eliminar el archivo del input file
        const fileInput = document.getElementById('image_files');
        if (fileInput && fileInput.files.length > imageIndex) {
            const dt = new DataTransfer();
            Array.from(fileInput.files).forEach((file, index) => {
                if (index !== imageIndex) {
                    dt.items.add(file);
                }
            });
            fileInput.files = dt.files;
        }
        
        // Eliminar el elemento visual
        imageItem.remove();
        
        // Si no quedan imágenes nuevas, ocultar el contenedor
        const previewContainer = document.getElementById('newImagesContainer');
        if (previewContainer && previewContainer.children.length === 0) {
            document.getElementById('newImagesPreview').style.display = 'none';
        }
    }
</script>
@endpush

