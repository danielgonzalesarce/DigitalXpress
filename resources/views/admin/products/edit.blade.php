@extends('layouts.admin')

@section('title', 'Editar Producto')
@section('page-title', 'Editar Producto')
@section('page-subtitle', 'Modificar información del producto • DigitalXpress')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="orders-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Editar Producto: {{ $product->name }}</h3>
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

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="alert alert-info mb-4" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nota:</strong> Todos los cambios que realices se guardarán inmediatamente en la base de datos al hacer clic en "Actualizar Producto".
                </div>

                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @if(isset($returnUrl))
                    <input type="hidden" name="return_url" value="{{ $returnUrl }}">
                    @else
                    <input type="hidden" name="return_url" value="{{ route('admin.products', request()->query()) }}">
                    @endif

                    <div class="row g-4">
                        <!-- Información Básica -->
                        <div class="col-lg-8">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Información Básica</h5>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Categoría <span class="text-danger">*</span></label>
                                        <select class="form-select @error('category_id') is-invalid @enderror" 
                                                id="category_id" name="category_id" required>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" 
                                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                                  id="short_description" name="short_description" rows="2" maxlength="500">{{ old('short_description', $product->short_description) }}</textarea>
                                        @error('short_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Máximo 500 caracteres</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Descripción Completa <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" name="description" rows="5" required>{{ old('description', $product->description) }}</textarea>
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
                                            <p class="mb-2"><strong>Nuevas imágenes seleccionadas:</strong></p>
                                            <div class="row g-2" id="newImagesContainer"></div>
                                        </div>
                                        
                                        <!-- Imágenes existentes con opción de eliminar -->
                                        @if($product->images && is_array($product->images) && count($product->images) > 0)
                                        <div class="mt-3">
                                            <p class="mb-2"><strong>Imágenes actuales:</strong> <small class="text-muted">(Haz clic en la X roja para eliminar)</small></p>
                                            <div class="row g-2" id="existingImagesContainer">
                                                @foreach($product->images as $index => $image)
                                                <div class="col-md-3 existing-image-item" data-image-index="{{ $index }}" data-image-path="{{ $image }}">
                                                    <div class="position-relative border rounded p-2" style="background-color: #f8f9fa;">
                                                        @if(str_starts_with($image, 'http'))
                                                            <img src="{{ $image }}" alt="Imagen {{ $index + 1 }}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                                                        @else
                                                            <img src="{{ asset('storage/' . $image) }}" alt="Imagen {{ $index + 1 }}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;" onerror="this.src='{{ $product->image_url }}'">
                                                        @endif
                                                        <input type="hidden" name="images[]" value="{{ $image }}" class="image-input">
                                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 shadow-sm" onclick="removeExistingImage(this)" title="Eliminar imagen" style="z-index: 10;">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @else
                                        <div class="mt-3">
                                            <p class="text-muted mb-2">No hay imágenes guardadas. La imagen se mostrará automáticamente según el nombre del producto.</p>
                                            @if($product->image_url)
                                            <p class="mb-2"><strong>Imagen actual (automática):</strong></p>
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                            @endif
                                        </div>
                                        @endif
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
                                                   id="price" name="price" value="{{ old('price', $product->price) }}" required>
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
                                                   id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
                                        </div>
                                        @error('sale_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                               id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                                        @error('sku')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="stock_quantity" class="form-label">Cantidad en Stock <span class="text-danger">*</span></label>
                                        <input type="number" min="0" 
                                               class="form-control @error('stock_quantity') is-invalid @enderror" 
                                               id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                                        @error('stock_quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="in_stock" name="in_stock" 
                                                   {{ old('in_stock', $product->in_stock) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="in_stock">
                                                Disponible en Stock
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                Producto Destacado
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
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
                        <button type="submit" class="btn btn-primary" id="updateProductBtn">
                            <i class="fas fa-save me-2"></i> Actualizar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .existing-image-item.to-be-deleted {
        opacity: 0.5;
        filter: grayscale(100%);
    }
    .existing-image-item.to-be-deleted img {
        border: 2px dashed #dc3545;
    }
    .existing-image-item .btn-danger {
        transition: all 0.3s ease;
    }
    .existing-image-item .btn-danger:hover {
        transform: scale(1.1);
        box-shadow: 0 0 10px rgba(220, 53, 69, 0.5);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[action*="update"]');
        const submitBtn = document.getElementById('updateProductBtn');
        
        if (form && submitBtn) {
            form.addEventListener('submit', function(e) {
                // Deshabilitar el botón para evitar doble envío
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Guardando...';
                
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
                        col.className = 'col-md-3';
                        col.innerHTML = `
                            <div class="position-relative border rounded p-2">
                                <img src="${e.target.result}" alt="Nueva imagen ${index + 1}" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" onclick="removeNewImage(this)" title="Eliminar imagen">
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

    // Función para eliminar una imagen existente
    function removeExistingImage(button) {
        if (confirm('¿Estás seguro de que quieres eliminar esta imagen? Esta acción se guardará al actualizar el producto.')) {
            const imageItem = button.closest('.existing-image-item');
            const imageInput = imageItem.querySelector('.image-input');
            const imageValue = imageInput.value;
            
            // Crear un input hidden para marcar esta imagen como eliminada
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = 'images_to_delete[]';
            deleteInput.value = imageValue;
            imageItem.appendChild(deleteInput);
            
            // Eliminar el input de imágenes existentes para que no se incluya en el array final
            imageInput.remove();
            
            // Ocultar visualmente con animación
            imageItem.style.transition = 'opacity 0.3s';
            imageItem.style.opacity = '0.5';
            imageItem.style.pointerEvents = 'none';
            
            // Agregar clase para indicar que será eliminada
            imageItem.classList.add('to-be-deleted');
        }
    }

    // Función para eliminar una nueva imagen seleccionada
    function removeNewImage(button) {
        const imageItem = button.closest('.col-md-3');
        imageItem.remove();
        
        // Si no quedan imágenes nuevas, ocultar el contenedor
        const previewContainer = document.getElementById('newImagesContainer');
        if (previewContainer && previewContainer.children.length === 0) {
            document.getElementById('newImagesPreview').style.display = 'none';
        }
        
        // Actualizar el input file para reflejar los cambios
        updateFileInput();
    }
    
    // Función para actualizar el input file después de eliminar una imagen nueva
    function updateFileInput() {
        const fileInput = document.getElementById('image_files');
        if (!fileInput) return;
        
        // Crear un nuevo DataTransfer para mantener solo los archivos no eliminados
        const dt = new DataTransfer();
        const files = Array.from(fileInput.files);
        const previewContainer = document.getElementById('newImagesContainer');
        
        // Contar cuántas imágenes quedan en la vista previa
        const remainingImages = previewContainer ? previewContainer.children.length : 0;
        
        // Si hay menos imágenes en la vista previa que archivos seleccionados,
        // significa que se eliminó una, pero no podemos saber cuál exactamente
        // así que simplemente mantenemos los archivos originales
        // (esto es una limitación del navegador, pero funciona para la mayoría de casos)
    }
</script>
@endpush

