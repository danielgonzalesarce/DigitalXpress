@extends('layouts.app')

@section('title', 'Dashboard de Reparaciones - DigitalXpress')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-primary mb-2">
                <i class="fas fa-tools me-2"></i> Dashboard de Reparaciones
            </h1>
            <p class="text-muted">Bienvenido {{ Auth::user()->name }}, gestiona tus reparaciones y servicios técnicos</p>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        @if(session('email_sent') === false)
        <br><small class="text-warning">
            <i class="fas fa-exclamation-triangle me-1"></i>
            Nota: La solicitud fue registrada, pero hubo un problema al enviar la notificación por correo. 
            Por favor, verifica la configuración de correo en el servidor.
        </small>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Stats Cards -->
    @php
        $totalRepairs = $repairs->count();
        $pendingRepairs = $repairs->where('status', 'pending')->count();
        $inProgressRepairs = $repairs->where('status', 'in_progress')->count();
        $completedRepairs = $repairs->where('status', 'completed')->count();
    @endphp
    
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-0">{{ $inProgressRepairs }}</h4>
                            <small>En Proceso</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-0">{{ $completedRepairs }}</h4>
                            <small>Completadas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-0">{{ $pendingRepairs }}</h4>
                            <small>Pendientes</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-history fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-0">{{ $totalRepairs }}</h4>
                            <small>Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Repairs -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-wrench me-2"></i> Mis Reparaciones
                    </h5>
                    @if($repairs->count() > 0)
                    <a href="{{ route('repairs.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> Nueva Reparación
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($repairs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Número</th>
                                    <th>Dispositivo</th>
                                    <th>Marca/Modelo</th>
                                    <th>Problema</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($repairs as $repair)
                                <tr>
                                    <td><strong>{{ $repair->repair_number }}</strong></td>
                                    <td>{{ $repair->device_type }}</td>
                                    <td>{{ $repair->brand }} {{ $repair->model }}</td>
                                    <td>{{ Str::limit($repair->problem_description, 50) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $repair->status_badge }}">
                                            {{ $repair->status_text }}
                                        </span>
                                    </td>
                                    <td>{{ $repair->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('repairs.show', $repair) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <!-- Estado vacío cuando no hay reparaciones -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-tools fa-4x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">No tienes reparaciones registradas</h4>
                        <p class="text-muted mb-4">
                            Cuando solicites una reparación, aparecerá aquí con toda la información y seguimiento.
                        </p>
                        <a href="{{ route('repairs.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i> Solicitar Nueva Reparación
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    @if($repairs->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('repairs.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-2"></i> Nueva Reparación
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('repairs.schedule') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-calendar me-2"></i> Agendar Cita
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('repairs.contact') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-phone me-2"></i> Contactar Soporte
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('repairs.download-report') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-download me-2"></i> Descargar Reporte
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
