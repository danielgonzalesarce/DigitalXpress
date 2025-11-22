@extends('layouts.admin')

@section('title', 'Gestión de Categorías')
@section('page-title', 'Categorías')
@section('page-subtitle', 'Administra las categorías de productos • DigitalXpress')

@section('content')
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="metric-value">{{ $totalCategories }}</div>
                <p class="metric-label">Total Categorías</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #f0fdf4; color: #10b981;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="metric-value">{{ $activeCategories }}</div>
                <p class="metric-label">Categorías Activas</p>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div class="metric-value">{{ $inactiveCategories }}</div>
                <p class="metric-label">Categorías Inactivas</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="orders-section mb-4">
        <form method="GET" action="{{ route('admin.categories') }}" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Buscar por nombre o descripción..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Todas las categorías</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Solo Activas</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Solo Inactivas</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid #10b981;">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
            <div class="flex-grow-1">
                <strong>¡Éxito!</strong>
                <p class="mb-0">{{ session('success') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid #ef4444;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle fa-2x me-3 text-danger"></i>
            <div class="flex-grow-1">
                <strong>Error</strong>
                <p class="mb-0">{{ session('error') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <!-- Categories Table -->
    <div class="orders-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Lista de Categorías</h3>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Crear Categoría
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th>Descripción</th>
                        <th>Orden</th>
                        <th>Estado</th>
                        <th>Productos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>
                            <strong>{{ $category->name }}</strong>
                        </td>
                        <td>
                            <code class="text-muted">{{ $category->slug }}</code>
                        </td>
                        <td>
                            <span class="text-muted">{{ Str::limit($category->description ?? 'Sin descripción', 50) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $category->sort_order }}</span>
                        </td>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success">Activa</span>
                            @else
                                <span class="badge bg-warning">Inactiva</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $category->products()->count() }} productos</span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.categories.edit', $category) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Eliminar"
                                            {{ $category->products()->count() > 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No se encontraron categorías</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Crear Primera Categoría
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
@endsection

