@extends('layouts.admin')

@section('title', 'Registro de Auditoría')
@section('page-title', 'Auditoría')
@section('page-subtitle', 'Registro de actividades de administradores • DigitalXpress')

@section('content')
    @if(Auth::check() && Auth::user()->email !== 'admin@digitalxpress.com')
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            No tienes permiso para acceder a esta sección.
        </div>
    @else
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fas fa-history"></i>
                </div>
                <div class="metric-value">{{ number_format($totalActivities) }}</div>
                <p class="metric-label">Total Actividades</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #f0fdf4; color: #10b981;">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="metric-value">{{ number_format($todayActivities) }}</div>
                <p class="metric-label">Hoy</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div class="metric-value">{{ number_format($thisWeekActivities) }}</div>
                <p class="metric-label">Esta Semana</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fee2e2; color: #dc2626;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="metric-value">{{ $activeUsers->count() }}</div>
                <p class="metric-label">Admins Activos</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="orders-section mb-4">
        <form method="GET" action="{{ route('admin.activity-logs') }}" class="row g-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Buscar por descripción..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="user_id" class="form-select">
                    <option value="">Todos los usuarios</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="action" class="form-select">
                    <option value="">Todas las acciones</option>
                    <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Crear</option>
                    <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Actualizar</option>
                    <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Eliminar</option>
                    <option value="view" {{ request('action') == 'view' ? 'selected' : '' }}>Ver</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="category" class="form-select">
                    <option value="">Todas las categorías</option>
                    @foreach($activitiesByCategory as $cat)
                        <option value="{{ $cat->category }}" {{ request('category') == $cat->category ? 'selected' : '' }}>
                            {{ ucfirst($cat->category) }} ({{ $cat->total }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <input type="date" name="date_from" class="form-control" 
                       placeholder="Desde" 
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-1">
                <input type="date" name="date_to" class="form-control" 
                       placeholder="Hasta" 
                       value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Activities Table -->
    <div class="orders-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Registro de Actividades</h3>
            <div>
                <a href="{{ route('admin.activity-logs') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-redo"></i> Limpiar Filtros
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Fecha/Hora</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th>Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td>
                            <div>
                                <strong>{{ $activity->created_at->format('d/m/Y') }}</strong><br>
                                <small class="text-muted">{{ $activity->created_at->format('H:i:s') }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-2" style="width: 35px; height: 35px; border-radius: 50%; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.85rem;">
                                    {{ strtoupper(substr($activity->user_name ?? 'S', 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $activity->user_name ?? 'Sistema' }}</strong><br>
                                    <small class="text-muted">{{ $activity->user_email ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $activity->severity_color }}">
                                <i class="{{ $activity->action_icon }}"></i>
                                {{ ucfirst($activity->action) }}
                            </span>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $activity->description }}</strong>
                                @if($activity->model_name)
                                    <br><small class="text-muted">{{ $activity->model_name }}</small>
                                @endif
                                @if($activity->changes && count($activity->changes) > 0)
                                    <br><small class="badge bg-info mt-1">
                                        <i class="fas fa-edit me-1"></i>{{ count($activity->changes) }} cambio(s)
                                    </small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ ucfirst($activity->category) }}
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#activityModal{{ $activity->id }}">
                                <i class="fas fa-eye"></i> Ver Detalles
                            </button>
                            
                            <!-- Modal para ver cambios y detalles -->
                            <div class="modal fade" id="activityModal{{ $activity->id }}" tabindex="-1" aria-labelledby="activityModalLabel{{ $activity->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title" id="activityModalLabel{{ $activity->id }}">
                                                <i class="fas fa-history me-2"></i>Detalles de la Actividad
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Información del Usuario -->
                                            <div class="card mb-3 border-0 shadow-sm">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-3">
                                                        <i class="fas fa-user me-2 text-primary"></i>Administrador
                                                    </h6>
                                                    <div class="d-flex align-items-center">
                                                        <div class="user-avatar me-3" style="width: 60px; height: 60px; border-radius: 50%; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1.5rem;">
                                                            {{ strtoupper(substr($activity->user_name ?? 'S', 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <h5 class="mb-1">{{ $activity->user_name ?? 'Sistema' }}</h5>
                                                            <p class="text-muted mb-0">
                                                                <i class="fas fa-envelope me-1"></i>{{ $activity->user_email ?? 'N/A' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Información de la Acción -->
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body">
                                                            <h6 class="card-title mb-3">
                                                                <i class="fas fa-info-circle me-2 text-info"></i>Información de la Acción
                                                            </h6>
                                                            <div class="mb-2">
                                                                <strong>Acción:</strong>
                                                                <span class="badge bg-{{ $activity->severity_color }} ms-2">
                                                                    <i class="{{ $activity->action_icon }}"></i>
                                                                    {{ ucfirst($activity->action) }}
                                                                </span>
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Descripción:</strong><br>
                                                                <span class="text-muted">{{ $activity->description }}</span>
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Categoría:</strong>
                                                                <span class="badge bg-secondary ms-2">{{ ucfirst($activity->category) }}</span>
                                                            </div>
                                                            @if($activity->model_name)
                                                            <div class="mb-2">
                                                                <strong>Elemento:</strong><br>
                                                                <span class="text-muted">{{ $activity->model_name }}</span>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body">
                                                            <h6 class="card-title mb-3">
                                                                <i class="fas fa-clock me-2 text-warning"></i>Fecha y Hora
                                                            </h6>
                                                            <div class="mb-2">
                                                                <strong>Fecha:</strong><br>
                                                                <span class="text-muted">{{ $activity->created_at->format('d/m/Y') }}</span>
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Hora:</strong><br>
                                                                <span class="text-muted">{{ $activity->created_at->format('H:i:s') }}</span>
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>Hace:</strong><br>
                                                                <span class="text-muted">{{ $activity->created_at->diffForHumans() }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Cambios Realizados -->
                                            @if($activity->changes && count($activity->changes) > 0)
                                            <div class="card mb-3 border-0 shadow-sm">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0">
                                                        <i class="fas fa-exchange-alt me-2 text-success"></i>Cambios Realizados
                                                        <span class="badge bg-success ms-2">{{ count($activity->changes) }} campo(s)</span>
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover table-bordered">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th style="width: 25%;">Campo</th>
                                                                    <th style="width: 37.5%;" class="bg-danger bg-opacity-10">Valor Anterior</th>
                                                                    <th style="width: 37.5%;" class="bg-success bg-opacity-10">Valor Nuevo</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($activity->changes as $field => $change)
                                                                <tr>
                                                                    <td>
                                                                        <strong>{{ ucfirst(str_replace('_', ' ', $field)) }}</strong>
                                                                    </td>
                                                                    <td class="bg-danger bg-opacity-5">
                                                                        @if(is_array($change['old']))
                                                                            <pre class="mb-0" style="font-size: 0.85rem; white-space: pre-wrap;">{{ json_encode($change['old'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                                        @elseif(is_bool($change['old']))
                                                                            <span class="badge bg-{{ $change['old'] ? 'success' : 'secondary' }}">
                                                                                {{ $change['old'] ? 'Sí' : 'No' }}
                                                                            </span>
                                                                        @elseif(is_null($change['old']))
                                                                            <span class="text-muted fst-italic">Vacío</span>
                                                                        @else
                                                                            <span class="text-danger fw-bold">{{ $change['old'] }}</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="bg-success bg-opacity-5">
                                                                        @if(is_array($change['new']))
                                                                            <pre class="mb-0" style="font-size: 0.85rem; white-space: pre-wrap;">{{ json_encode($change['new'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                                        @elseif(is_bool($change['new']))
                                                                            <span class="badge bg-{{ $change['new'] ? 'success' : 'secondary' }}">
                                                                                {{ $change['new'] ? 'Sí' : 'No' }}
                                                                            </span>
                                                                        @elseif(is_null($change['new']))
                                                                            <span class="text-muted fst-italic">Vacío</span>
                                                                        @else
                                                                            <span class="text-success fw-bold">{{ $change['new'] }}</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            @elseif($activity->action === 'create')
                                            <div class="alert alert-info mb-3">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Nuevo registro creado:</strong> Se creó un nuevo elemento en el sistema.
                                                @if($activity->new_values && count($activity->new_values) > 0)
                                                <div class="mt-3">
                                                    <strong>Datos iniciales:</strong>
                                                    <ul class="mb-0 mt-2">
                                                        @foreach($activity->new_values as $key => $value)
                                                            @if(!in_array($key, ['password', 'remember_token', 'api_token']))
                                                            <li>
                                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                                                                {{ is_array($value) ? json_encode($value) : ($value ?? 'N/A') }}
                                                            </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                            @elseif($activity->action === 'delete')
                                            <div class="alert alert-warning mb-3">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <strong>Registro eliminado:</strong> Este elemento fue eliminado del sistema.
                                                @if($activity->old_values && count($activity->old_values) > 0)
                                                <div class="mt-3">
                                                    <strong>Datos del elemento eliminado:</strong>
                                                    <ul class="mb-0 mt-2">
                                                        @foreach($activity->old_values as $key => $value)
                                                            @if(!in_array($key, ['password', 'remember_token', 'api_token']))
                                                            <li>
                                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                                                                {{ is_array($value) ? json_encode($value) : ($value ?? 'N/A') }}
                                                            </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                            @else
                                            <div class="alert alert-secondary mb-3">
                                                <i class="fas fa-info-circle me-2"></i>
                                                No hay cambios específicos registrados para esta acción.
                                            </div>
                                            @endif

                                            <!-- Información Técnica -->
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0">
                                                        <i class="fas fa-cog me-2 text-secondary"></i>Información Técnica
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <strong>IP Address:</strong><br>
                                                            <code class="text-muted">{{ $activity->ip_address ?? 'N/A' }}</code>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong>Ruta:</strong><br>
                                                            <code class="text-muted">{{ $activity->route ?? 'N/A' }}</code>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <strong>Método HTTP:</strong><br>
                                                            <span class="badge bg-info">{{ $activity->method ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                    @if($activity->user_agent)
                                                    <div class="row mt-3">
                                                        <div class="col-12">
                                                            <strong>User Agent:</strong><br>
                                                            <small class="text-muted">{{ $activity->user_agent }}</small>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-2"></i>Cerrar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No se encontraron actividades</h4>
                            <p class="text-muted">Intenta con otros criterios de búsqueda</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($activities->hasPages())
        <div class="mt-4">
            {{ $activities->links() }}
        </div>
        @endif
    </div>

    <!-- Top Users Section -->
    @if($activeUsers->count() > 0)
    <div class="orders-section mt-4">
        <h3 class="mb-4">Administradores Más Activos</h3>
        <div class="row g-3">
            @foreach($activeUsers as $user)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-3" style="width: 50px; height: 50px; border-radius: 50%; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                {{ strtoupper(substr($user->user_name, 0, 1)) }}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $user->user_name }}</h6>
                                <small class="text-muted">{{ $user->total }} actividades</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @endif
@endsection

