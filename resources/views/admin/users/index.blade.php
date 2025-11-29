@extends('layouts.admin')

@section('title', 'Gestión de Usuarios')
@section('page-title', 'Usuarios')
@section('page-subtitle', 'Administra los usuarios del sistema • DigitalXpress')

@section('content')
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="metric-value">{{ $totalUsers }}</div>
                <p class="metric-label">Total Usuarios</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #f0fdf4; color: #10b981;">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="metric-value">{{ $adminUsers }}</div>
                <p class="metric-label">Administradores</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fas fa-user"></i>
                </div>
                <div class="metric-value">{{ $customerUsers }}</div>
                <p class="metric-label">Clientes</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="metric-card">
                <div class="metric-icon" style="background: #fee2e2; color: #dc2626;">
                    <i class="fab fa-google"></i>
                </div>
                <div class="metric-value">{{ $googleUsers }}</div>
                <p class="metric-label">Con Google</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="orders-section mb-4">
        <form method="GET" action="{{ route('admin.users') }}" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Buscar por nombre o email..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="type" class="form-select">
                    <option value="">Todos los usuarios</option>
                    <option value="admin" {{ request('type') == 'admin' ? 'selected' : '' }}>Solo Administradores</option>
                    <option value="customer" {{ request('type') == 'customer' ? 'selected' : '' }}>Solo Clientes</option>
                    <option value="google" {{ request('type') == 'google' ? 'selected' : '' }}>Solo con Google</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="orders-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Lista de Usuarios</h3>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Crear Usuario
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Método de Registro</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" 
                                         class="me-2" 
                                         style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <div class="user-avatar me-2" style="width: 40px; height: 40px; border-radius: 50%; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $role = $user->role ?? (str_ends_with(strtolower($user->email), '@digitalxpress.com') ? 'admin' : 'customer');
                                $roleLabels = [
                                    'admin' => ['label' => 'Administrador', 'class' => 'bg-success'],
                                    'customer' => ['label' => 'Cliente', 'class' => 'bg-secondary'],
                                    'technician' => ['label' => 'Técnico', 'class' => 'bg-info'],
                                    'vip' => ['label' => 'VIP', 'class' => 'bg-warning'],
                                ];
                                $roleInfo = $roleLabels[$role] ?? $roleLabels['customer'];
                            @endphp
                            <span class="badge {{ $roleInfo['class'] }}">{{ $roleInfo['label'] }}</span>
                        </td>
                        <td>
                            @if($user->google_id)
                                <span class="badge bg-danger">
                                    <i class="fab fa-google me-1"></i> Google
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-envelope me-1"></i> Email
                                </span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" data-confirm-message="¿Estás seguro de eliminar este usuario?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="showConfirmModal('¿Estás seguro de eliminar este usuario?', '{{ route('admin.users.destroy', $user) }}', 'DELETE')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No se encontraron usuarios</h4>
                            <p class="text-muted">Intenta con otros criterios de búsqueda</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="mt-4">
            {{ $users->links() }}
        </div>
        @endif
    </div>

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
@endsection

