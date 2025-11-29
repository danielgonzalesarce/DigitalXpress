@extends('layouts.admin')

@section('title', 'Gestión de Reparaciones')
@section('page-title', 'Reparaciones')
@section('page-subtitle', 'Administra las reparaciones del sistema • DigitalXpress')

@section('content')
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fas fa-wrench"></i>
                </div>
                <div class="metric-value">{{ $totalRepairs }}</div>
                <p class="metric-label">Total Reparaciones</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="metric-value">{{ $pendingRepairs }}</div>
                <p class="metric-label">Pendientes</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #dbeafe; color: #3b82f6;">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="metric-value">{{ $inProgressRepairs }}</div>
                <p class="metric-label">En Progreso</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #f0fdf4; color: #10b981;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="metric-value">{{ $completedRepairs }}</div>
                <p class="metric-label">Completadas</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="orders-section mb-4">
        <form method="GET" action="{{ route('admin.repairs') }}" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Buscar por número, nombre, email, dispositivo..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
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

    <!-- Repairs Table -->
    <div class="orders-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Lista de Reparaciones</h3>
            <a href="{{ route('admin.repairs.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Crear Reparación
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Número</th>
                        <th>Cliente</th>
                        <th>Dispositivo</th>
                        <th>Estado</th>
                        <th>Costos</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($repairs as $repair)
                    <tr>
                        <td>
                            <strong>{{ $repair->repair_number }}</strong>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $repair->full_name }}</strong><br>
                                <small class="text-muted">{{ $repair->email }}</small><br>
                                <small class="text-muted">{{ $repair->phone }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $repair->device_type }}</strong><br>
                                <small class="text-muted">{{ $repair->brand }} {{ $repair->model }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $repair->status_badge }}">
                                {{ $repair->status_text }}
                            </span>
                            @if($repair->status === 'completed')
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-check-circle me-1"></i>
                                Reparación Terminada
                            </small>
                            @endif
                        </td>
                        <td>
                            @if($repair->estimated_cost)
                                <small>Estimado: ${{ number_format($repair->estimated_cost, 2) }}</small><br>
                            @endif
                            @if($repair->final_cost)
                                <strong>Final: ${{ number_format($repair->final_cost, 2) }}</strong>
                            @else
                                <small class="text-muted">Sin costo final</small>
                            @endif
                        </td>
                        <td>
                            <small>{{ $repair->created_at->format('d/m/Y') }}</small><br>
                            <small class="text-muted">{{ $repair->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                @if($repair->status === 'completed')
                                <button type="button" 
                                        class="btn btn-sm btn-secondary" 
                                        title="Reparación Terminada"
                                        disabled>
                                    <i class="fas fa-check-circle me-1"></i>
                                    Reparación Terminada
                                </button>
                                @else
                                <a href="{{ route('admin.repairs.edit', $repair) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                <form action="{{ route('admin.repairs.destroy', $repair) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('¿Estás seguro de eliminar esta reparación?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger" 
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-wrench fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No se encontraron reparaciones</p>
                            <a href="{{ route('admin.repairs.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Crear Primera Reparación
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($repairs->hasPages())
        <div class="mt-4">
            {{ $repairs->links() }}
        </div>
        @endif
    </div>
@endsection

