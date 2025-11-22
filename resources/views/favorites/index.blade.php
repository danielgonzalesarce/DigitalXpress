@extends('layouts.app')

@section('title', 'Mis Favoritos - DigitalXpress')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold">
                <i class="fas fa-heart text-danger me-2"></i>Mis Favoritos
            </h1>
            <p class="text-muted">Productos que has guardado para más tarde</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="fas fa-store me-2"></i>Explorar Productos
            </a>
        </div>
    </div>

    <!-- Favorites Count -->
    <div class="mb-4">
        <p class="text-muted">
            <i class="fas fa-heart text-danger me-1"></i>
            <span class="favorites-count">{{ $favorites->total() }}</span> {{ $favorites->total() == 1 ? 'producto guardado' : 'productos guardados' }}
        </p>
    </div>

    <!-- Favorites Container -->
    @if($favorites->count() > 0)
    <!-- Bulk Actions Bar -->
    <div class="mb-3 p-3 bg-light rounded d-flex justify-content-between align-items-center" id="bulkActionsBar" style="display: none !important;">
        <div class="d-flex align-items-center gap-3">
            <span class="fw-bold" id="selectedCount">0 productos seleccionados</span>
            <button class="btn btn-sm btn-outline-secondary" onclick="selectAllFavorites()">
                <i class="fas fa-check-square me-1"></i>Seleccionar todos
            </button>
            <button class="btn btn-sm btn-outline-secondary" onclick="deselectAllFavorites()">
                <i class="fas fa-square me-1"></i>Deseleccionar todos
            </button>
        </div>
        <button class="btn btn-danger btn-sm" onclick="deleteSelectedFavorites()">
            <i class="fas fa-trash me-1"></i>Eliminar seleccionados
        </button>
    </div>

    <!-- Selection Mode Toggle -->
    <div class="mb-3">
        <button class="btn btn-outline-primary btn-sm" onclick="toggleSelectionMode()" id="selectionModeBtn">
            <i class="fas fa-check-square me-1"></i>Modo selección
        </button>
    </div>
    <div class="row g-4">
        @foreach($favorites as $favorite)
        @php
            $product = $favorite->product;
        @endphp
        <div class="col-lg-3 col-md-6 favorite-product-item" data-product-id="{{ $product->id }}">
            <div class="card product-card h-100">
                <!-- Checkbox de selección -->
                <div class="position-absolute top-0 start-0 m-2 favorite-checkbox-container" style="display: none; z-index: 15;">
                    <input type="checkbox" class="form-check-input favorite-checkbox" 
                           id="favorite-{{ $product->id }}" 
                           value="{{ $product->id }}"
                           style="width: 20px; height: 20px; cursor: pointer;"
                           onchange="updateSelectedCount()">
                </div>
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
                    <div class="position-absolute top-0 end-0 m-2 d-flex gap-1">
                        <button class="btn btn-danger btn-sm rounded-circle product-favorite-btn favorite-active" 
                                data-product-id="{{ $product->id }}"
                                onclick="toggleFavorite({{ $product->id }})"
                                title="Quitar de favoritos">
                            <i class="fas fa-heart"></i>
                        </button>
                        <button class="btn btn-danger btn-sm rounded-circle remove-favorite-btn" 
                                data-product-id="{{ $product->id }}"
                                onclick="removeFromFavorites({{ $product->id }})"
                                title="Eliminar de favoritos">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    @if($product->is_featured)
                    <div class="position-absolute top-0 start-0 m-2">
                        <span class="badge bg-success">Destacado</span>
                    </div>
                    @endif
                    @if($product->is_on_sale)
                    <div class="position-absolute bottom-0 start-0 m-2">
                        <span class="badge bg-danger">Oferta</span>
                    </div>
                    @endif
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">
                        <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">
                            {{ $product->name }}
                        </a>
                    </h5>
                    <p class="card-text text-muted small flex-grow-1">
                        {{ Str::limit($product->short_description ?? $product->description, 80) }}
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <div>
                            @if($product->is_on_sale)
                                <span class="text-danger fw-bold fs-5">${{ number_format($product->sale_price, 2) }}</span>
                                <span class="text-muted text-decoration-line-through ms-2">${{ number_format($product->price, 2) }}</span>
                            @else
                                <span class="fw-bold fs-5">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        @if($product->in_stock)
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-cart-plus me-1"></i>Agregar
                                </button>
                            </form>
                        @else
                            <span class="badge bg-secondary">Agotado</span>
                        @endif
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-tag me-1"></i>{{ $product->category->name }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $favorites->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-heart-broken fa-5x text-muted"></i>
        </div>
        <h3 class="fw-bold mb-3">No tienes favoritos aún</h3>
        <p class="text-muted mb-4">Comienza a guardar tus productos favoritos haciendo clic en el corazón</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-store me-2"></i>Explorar Productos
        </a>
    </div>
    @endif
</div>

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
        position: relative;
    }

    .product-image {
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
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
    }

    .product-card:hover .product-overlay {
        opacity: 1;
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

    .product-image-container .badge {
        z-index: 10;
    }

    .remove-favorite-btn {
        z-index: 10;
        transition: all 0.3s ease;
        border: none;
        background-color: #dc3545 !important;
        color: white !important;
    }

    .remove-favorite-btn:hover {
        background-color: #bb2d3b !important;
        transform: scale(1.1);
        color: white !important;
    }

    .remove-favorite-btn i {
        color: white !important;
    }

    .favorite-checkbox-container {
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        padding: 5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .favorite-checkbox {
        cursor: pointer;
    }

    .favorite-product-item.selected {
        border: 2px solid #dc3545;
        border-radius: 8px;
    }

    #bulkActionsBar {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
// Variables globales
let selectionMode = false;

// Toggle del modo de selección
function toggleSelectionMode() {
    selectionMode = !selectionMode;
    const checkboxes = document.querySelectorAll('.favorite-checkbox-container');
    const btn = document.getElementById('selectionModeBtn');
    const bulkBar = document.getElementById('bulkActionsBar');
    
    if (selectionMode) {
        checkboxes.forEach(cb => cb.style.display = 'block');
        btn.innerHTML = '<i class="fas fa-times me-1"></i>Cancelar selección';
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-outline-danger');
        bulkBar.style.display = 'flex';
        updateSelectedCount();
    } else {
        checkboxes.forEach(cb => cb.style.display = 'none');
        btn.innerHTML = '<i class="fas fa-check-square me-1"></i>Modo selección';
        btn.classList.remove('btn-outline-danger');
        btn.classList.add('btn-outline-primary');
        bulkBar.style.display = 'none';
        // Deseleccionar todos
        document.querySelectorAll('.favorite-checkbox').forEach(cb => {
            cb.checked = false;
            cb.closest('.favorite-product-item').classList.remove('selected');
        });
    }
}

// Seleccionar todos
function selectAllFavorites() {
    document.querySelectorAll('.favorite-checkbox').forEach(checkbox => {
        checkbox.checked = true;
        checkbox.closest('.favorite-product-item').classList.add('selected');
    });
    updateSelectedCount();
}

// Deseleccionar todos
function deselectAllFavorites() {
    document.querySelectorAll('.favorite-checkbox').forEach(checkbox => {
        checkbox.checked = false;
        checkbox.closest('.favorite-product-item').classList.remove('selected');
    });
    updateSelectedCount();
}

// Actualizar contador de seleccionados
function updateSelectedCount() {
    const selected = document.querySelectorAll('.favorite-checkbox:checked');
    const count = selected.length;
    const countElement = document.getElementById('selectedCount');
    
    if (countElement) {
        countElement.textContent = count + ' producto' + (count !== 1 ? 's' : '') + ' seleccionado' + (count !== 1 ? 's' : '');
    }
    
    // Actualizar clases de selección en los items
    document.querySelectorAll('.favorite-product-item').forEach(item => {
        const checkbox = item.querySelector('.favorite-checkbox');
        if (checkbox && checkbox.checked) {
            item.classList.add('selected');
        } else {
            item.classList.remove('selected');
        }
    });
}

// Eliminar productos seleccionados
async function deleteSelectedFavorites() {
    const selected = document.querySelectorAll('.favorite-checkbox:checked');
    
    if (selected.length === 0) {
        showNotification('No hay productos seleccionados', 'warning');
        return;
    }
    
    if (!confirm(`¿Estás seguro de que quieres eliminar ${selected.length} producto${selected.length > 1 ? 's' : ''} de tus favoritos?`)) {
        return;
    }
    
    const productIds = Array.from(selected).map(cb => parseInt(cb.value));
    let successCount = 0;
    let failCount = 0;
    
    // Deshabilitar botones mientras se elimina
    const deleteBtn = event.target;
    deleteBtn.disabled = true;
    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Eliminando...';
    
    // Eliminar productos uno por uno
    for (const productId of productIds) {
        try {
            const response = await fetch(`/favoritos/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                const data = await response.json();
                if (data.success) {
                    successCount++;
                    // Eliminar el producto de la vista
                    const productItem = document.querySelector(`.favorite-product-item[data-product-id="${productId}"]`);
                    if (productItem) {
                        productItem.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        productItem.style.opacity = '0';
                        productItem.style.transform = 'scale(0.9)';
                        setTimeout(() => productItem.remove(), 300);
                    }
                } else {
                    failCount++;
                }
            } else {
                failCount++;
            }
        } catch (error) {
            console.error('Error eliminando producto:', error);
            failCount++;
        }
    }
    
    // Restaurar botón
    deleteBtn.disabled = false;
    deleteBtn.innerHTML = '<i class="fas fa-trash me-1"></i>Eliminar seleccionados';
    
    // Mostrar resultado
    if (successCount > 0) {
        showNotification(`${successCount} producto${successCount > 1 ? 's' : ''} eliminado${successCount > 1 ? 's' : ''} de favoritos`, 'success');
    }
    if (failCount > 0) {
        showNotification(`No se pudieron eliminar ${failCount} producto${failCount > 1 ? 's' : ''}`, 'warning');
    }
    
    // Actualizar contador
    const countElement = document.querySelector('.favorites-count');
    if (countElement) {
        const currentCount = parseInt(countElement.textContent) || 0;
        const newCount = Math.max(0, currentCount - successCount);
        countElement.textContent = newCount;
    }
    
    // Verificar si quedan productos
    setTimeout(() => {
        const remainingProducts = document.querySelectorAll('.favorite-product-item');
        if (remainingProducts.length === 0) {
            window.location.reload();
        } else {
            // Salir del modo selección si no hay más seleccionados
            if (document.querySelectorAll('.favorite-checkbox:checked').length === 0) {
                toggleSelectionMode();
            } else {
                updateSelectedCount();
            }
        }
    }, 500);
}

// Función para eliminar de favoritos usando el botón de tacho
async function removeFromFavorites(productId) {
    if (!confirm('¿Estás seguro de que quieres eliminar este producto de tus favoritos?')) {
        return;
    }

    try {
        const response = await fetch(`/favoritos/${productId}`, {
            method: 'DELETE',
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
            if (response.status === 401 || response.status === 403) {
                showNotification('Debes iniciar sesión para eliminar favoritos', 'warning');
                return;
            }
            if (contentType && contentType.includes('text/html')) {
                showNotification('Debes iniciar sesión para eliminar favoritos', 'warning');
                return;
            }
            throw new Error('Respuesta inesperada del servidor');
        }

        const data = await response.json();
        
        if (data.success) {
            // Eliminar el producto de la vista
            const productItem = document.querySelector(`.favorite-product-item[data-product-id="${productId}"]`);
            if (productItem) {
                // Animación de desvanecimiento
                productItem.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                productItem.style.opacity = '0';
                productItem.style.transform = 'scale(0.9)';
                
                setTimeout(() => {
                    productItem.remove();
                    
                    // Verificar si no quedan más productos
                    const remainingProducts = document.querySelectorAll('.favorite-product-item');
                    if (remainingProducts.length === 0) {
                        // Recargar la página para mostrar el mensaje de "no hay favoritos"
                        window.location.reload();
                    } else {
                        // Actualizar el contador si existe
                        const countElement = document.querySelector('.favorites-count');
                        if (countElement) {
                            const currentCount = parseInt(countElement.textContent) || 0;
                            const newCount = Math.max(0, currentCount - 1);
                            countElement.textContent = newCount;
                        }
                    }
                }, 300);
            }
            
            if (data.message) {
                showNotification(data.message, 'info');
            }
        } else if (data.message) {
            showNotification(data.message, 'warning');
        }
    } catch (error) {
        console.error('Error:', error);
        if (!error.message.includes('JSON')) {
            showNotification('Hubo un error al eliminar el producto de favoritos', 'danger');
        }
    }
}

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
            // Si es HTML (redirección), mostrar mensaje
            if (contentType && contentType.includes('text/html')) {
                showNotification('Debes iniciar sesión para agregar favoritos', 'warning');
                return;
            }
            throw new Error('Respuesta inesperada del servidor');
        }

        const data = await response.json();
        
        if (data.success) {
            if (isFavorite) {
                // Si estamos en la página de favoritos, eliminar el producto de la vista
                if (window.location.pathname === '/favoritos') {
                    const productItem = btn.closest('.favorite-product-item');
                    if (productItem) {
                        // Animación de desvanecimiento
                        productItem.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        productItem.style.opacity = '0';
                        productItem.style.transform = 'scale(0.9)';
                        
                        setTimeout(() => {
                            productItem.remove();
                            
                            // Verificar si no quedan más productos
                            const remainingProducts = document.querySelectorAll('.favorite-product-item');
                            if (remainingProducts.length === 0) {
                                // Recargar la página para mostrar el mensaje de "no hay favoritos"
                                window.location.reload();
                            } else {
                                // Actualizar el contador si existe
                                const countElement = document.querySelector('.favorites-count');
                                if (countElement) {
                                    const currentCount = parseInt(countElement.textContent) || 0;
                                    const newCount = Math.max(0, currentCount - 1);
                                    countElement.textContent = newCount;
                                }
                            }
                        }, 300);
                    }
                } else {
                    btn.classList.remove('favorite-active');
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-light');
                }
            } else {
                btn.classList.add('favorite-active');
                btn.classList.remove('btn-light');
                btn.classList.add('btn-danger');
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
document.addEventListener('DOMContentLoaded', function() {
    const favoriteButtons = document.querySelectorAll('.product-favorite-btn[data-product-id]');
    
    // En la página de favoritos, todos los productos ya están en favoritos
    // Solo verificamos para asegurar consistencia
    favoriteButtons.forEach(btn => {
        const productId = btn.getAttribute('data-product-id');
        
        // Asegurar que todos los botones en la página de favoritos estén en rojo
        btn.classList.add('favorite-active');
        btn.classList.remove('btn-light');
        btn.classList.add('btn-danger');
        
        // Verificar también desde el servidor para consistencia
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
                return { is_favorite: true }; // Asumir que está en favoritos si no podemos verificar
            })
            .then(data => {
                if (data.is_favorite) {
                    btn.classList.add('favorite-active');
                    btn.classList.remove('btn-light');
                    btn.classList.add('btn-danger');
                } else {
                    // Si por alguna razón no está en favoritos, remover el estado
                    btn.classList.remove('favorite-active');
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-light');
                }
            })
            .catch(error => {
                // Silenciar errores de verificación en la página de favoritos
                console.debug('No se pudo verificar favorito:', error);
            });
    });
});
</script>
@endsection

