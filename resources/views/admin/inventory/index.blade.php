@extends('layouts.admin')

@section('title', 'Gestión de Inventario')
@section('page-title', 'Inventario')
@section('page-subtitle', 'Gestiona el stock de tus productos • DigitalXpress')

@section('content')
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fas fa-cube"></i>
                </div>
                <div class="metric-value">{{ $totalProducts }}</div>
                <p class="metric-label">Total Productos</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #f0fdf4; color: #10b981;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="metric-value">{{ $inStockProducts }}</div>
                <p class="metric-label">Con Stock</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="metric-value">{{ $lowStockProducts }}</div>
                <p class="metric-label">Stock Bajo</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fee2e2; color: #ef4444;">
                    <i class="fas fa-box"></i>
                </div>
                <div class="metric-value">{{ $outOfStockProducts }}</div>
                <p class="metric-label">Sin Stock</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="orders-section mb-4">
        <form method="GET" action="{{ route('admin.inventory') }}" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Buscar por nombre o SKU..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="stock_status" class="form-select">
                    <option value="">Todos los productos</option>
                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>Con Stock</option>
                    <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Stock Bajo</option>
                    <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Sin Stock</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="orders-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Lista de Productos</h3>
            <a href="{{ route('admin.inventory.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Registrar Movimiento
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>SKU</th>
                        <th>Stock</th>
                        <th>Precio</th>
                        <th>Valor en Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                <div>
                                    <strong>{{ $product->name }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $product->category->name }}</span>
                        </td>
                        <td>
                            <code class="text-muted">{{ $product->sku }}</code>
                        </td>
                        <td>
                            <form action="{{ route('admin.inventory.update', $product) }}" 
                                  method="POST" 
                                  method="POST" 
                                  class="stock-form"
                                  data-product-id="{{ $product->id }}"
                                  data-product-name="{{ $product->name }}">
                                @csrf
                                @method('PUT')
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="number" 
                                               name="stock_quantity" 
                                               value="{{ $product->stock_quantity }}" 
                                               min="0"
                                               class="form-control form-control-sm stock-input" 
                                               style="width: 120px;"
                                               data-original-value="{{ $product->stock_quantity }}"
                                               id="stock-input-{{ $product->id }}">
                                        <span class="text-muted small">unidades</span>
                                    </div>
                                    <button type="submit" 
                                            class="btn btn-sm btn-primary update-stock-btn" 
                                            title="Actualizar Inventario"
                                            style="width: 120px;">
                                        <i class="fas fa-sync-alt me-1"></i> Actualizar Inventario
                                    </button>
                                </div>
                            </form>
                            <div class="stock-badge mt-2">
                                @if($product->stock_quantity > 0 && $product->stock_quantity < 10)
                                    <span class="badge bg-warning">{{ $product->stock_quantity }} unidades</span>
                                @elseif($product->stock_quantity > 0)
                                    <span class="badge bg-success">{{ $product->stock_quantity }} unidades</span>
                                @else
                                    <span class="badge bg-danger">Sin Stock</span>
                                @endif
                            </div>
                        </td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>
                            <strong>${{ number_format($product->price * $product->stock_quantity, 2) }}</strong>
                        </td>
                        <td>
                            <form action="{{ route('admin.inventory.destroy', $product) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('¿Estás seguro de resetear el stock de \"{{ $product->name }}\" a 0?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-outline-danger" 
                                        title="Resetear Stock a 0">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-cube fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No se encontraron productos</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="mt-4">
            {{ $products->links() }}
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enviar formulario con AJAX cuando se hace clic en "Actualizar Inventario"
            document.querySelectorAll('.stock-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const productName = this.dataset.productName;
                    const stockInput = this.querySelector('.stock-input');
                    const updateBtn = this.querySelector('.update-stock-btn');
                    const originalValue = parseInt(stockInput.dataset.originalValue);
                    const newValue = parseInt(stockInput.value) || 0;
                    
                    // Validar que el valor sea válido
                    if (newValue < 0) {
                        showNotification('La cantidad en stock no puede ser negativa', 'error');
                        stockInput.value = originalValue;
                        return;
                    }
                    
                    // Validar que haya cambios
                    if (newValue === originalValue) {
                        showNotification('No hay cambios para guardar', 'info');
                        return;
                    }
                    
                    // Deshabilitar botón y mostrar estado de carga
                    updateBtn.disabled = true;
                    const originalBtnText = updateBtn.innerHTML;
                    updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Actualizando...';
                    stockInput.disabled = true;
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Error al actualizar el stock');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Actualizar valor original
                            stockInput.dataset.originalValue = data.stock_quantity;
                            stockInput.value = data.stock_quantity;
                            
                            // Actualizar badge
                            const badgeContainer = this.closest('td').querySelector('.stock-badge');
                            let badgeClass = 'bg-danger';
                            let badgeText = 'Sin Stock';
                            
                            if (data.stock_quantity > 0 && data.stock_quantity < 10) {
                                badgeClass = 'bg-warning';
                                badgeText = data.stock_quantity + ' unidades';
                            } else if (data.stock_quantity > 0) {
                                badgeClass = 'bg-success';
                                badgeText = data.stock_quantity + ' unidades';
                            }
                            
                            badgeContainer.innerHTML = '<span class="badge ' + badgeClass + '">' + badgeText + '</span>';
                            
                            // Mostrar mensaje de éxito
                            showNotification('✓ Stock de "' + productName + '" actualizado exitosamente a ' + data.stock_quantity + ' unidades', 'success');
                            
                            // Resaltar el input con éxito
                            stockInput.classList.add('border-success');
                            setTimeout(() => {
                                stockInput.classList.remove('border-success');
                            }, 2000);
                            
                            // Recargar página después de un momento para actualizar estadísticas
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showNotification(data.message || 'Error al actualizar el stock', 'error');
                            stockInput.value = originalValue;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error al actualizar el stock: ' + error.message, 'error');
                        stockInput.value = originalValue;
                    })
                    .finally(() => {
                        updateBtn.disabled = false;
                        updateBtn.innerHTML = originalBtnText;
                        stockInput.disabled = false;
                    });
                });
            });
        });

        function showNotification(message, type) {
            // Remover notificaciones anteriores
            document.querySelectorAll('.stock-notification').forEach(n => n.remove());
            
            const notification = document.createElement('div');
            notification.className = 'alert alert-' + (type === 'success' ? 'success' : type === 'info' ? 'info' : 'danger') + ' alert-dismissible fade show position-fixed stock-notification';
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 350px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';
            notification.innerHTML = `
                <strong>${type === 'success' ? '✓' : type === 'info' ? 'ℹ' : '✗'}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    </script>
    @endpush
@endsection
