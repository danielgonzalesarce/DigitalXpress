@extends('layouts.app')

@section('title', 'Detalles de Reparación - DigitalXpress')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold text-primary mb-2">
                        <i class="fas fa-wrench me-2"></i> Detalles de Reparación
                    </h1>
                    <p class="text-muted mb-0">Número de reparación: <strong>{{ $repair->repair_number }}</strong></p>
                </div>
                <div>
                    <a href="{{ route('repairs.dashboard') }}" class="btn btn-enhanced btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> <span>Volver</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Información Principal -->
        <div class="col-lg-8">
            <!-- Estado de la Reparación -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i> Estado de la Reparación
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-{{ $repair->status_badge }} fs-6 px-3 py-2 me-3">
                            {{ $repair->status_text }}
                        </span>
                        <span class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            Creada el {{ $repair->created_at->format('d/m/Y') }} a las {{ $repair->created_at->format('H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Información del Dispositivo -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-mobile-alt me-2"></i> Información del Dispositivo
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tipo de Dispositivo</label>
                            <p class="mb-0 fw-bold">{{ $repair->device_type }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Marca</label>
                            <p class="mb-0 fw-bold">{{ $repair->brand }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Modelo</label>
                            <p class="mb-0 fw-bold">{{ $repair->model }}</p>
                        </div>
                        @if($repair->device_image)
                        <div class="col-12 mb-3">
                            <label class="text-muted small">Imagen del Dispositivo</label>
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $repair->device_image) }}" 
                                     alt="Imagen del dispositivo" 
                                     class="img-fluid rounded" 
                                     style="max-height: 300px;">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Descripción del Problema -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i> Descripción del Problema
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $repair->problem_description }}</p>
                </div>
            </div>

            <!-- Notas del Técnico -->
            @if($repair->notes)
            <div class="card shadow-sm mb-4 border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-sticky-note me-2"></i> Notas del Técnico
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $repair->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Información de Contacto -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i> Información de Contacto
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Nombre Completo</label>
                        <p class="mb-0 fw-bold">{{ $repair->full_name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Correo Electrónico</label>
                        <p class="mb-0">
                            <a href="mailto:{{ $repair->email }}" class="text-decoration-none">
                                {{ $repair->email }}
                            </a>
                        </p>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small">Teléfono</label>
                        <p class="mb-0">
                            <a href="tel:{{ $repair->phone }}" class="text-decoration-none">
                                {{ $repair->phone }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Información de Costos -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-dollar-sign me-2"></i> Información de Costos
                    </h5>
                </div>
                <div class="card-body">
                    @if($repair->estimated_cost)
                    <div class="mb-3">
                        <label class="text-muted small">Costo Estimado</label>
                        <p class="mb-0 fw-bold text-primary fs-5">
                            S/ {{ number_format($repair->estimated_cost, 2) }}
                        </p>
                    </div>
                    @endif
                    @if($repair->final_cost)
                    <div class="mb-0">
                        <label class="text-muted small">Costo Final</label>
                        <p class="mb-0 fw-bold text-success fs-5">
                            S/ {{ number_format($repair->final_cost, 2) }}
                        </p>
                    </div>
                    @endif
                    @if(!$repair->estimated_cost && !$repair->final_cost)
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Los costos se actualizarán cuando el técnico revise tu dispositivo.
                    </p>
                    @endif
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i> Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('repairs.contact') }}" class="btn btn-enhanced btn-outline-primary">
                            <i class="fas fa-comments"></i> <span>Contactar Soporte</span>
                        </a>
                        <a href="{{ route('repairs.schedule') }}" class="btn btn-enhanced btn-outline-success">
                            <i class="fas fa-calendar"></i> <span>Agendar Cita</span>
                        </a>
                        @if($repair->status === 'completed')
                        <a href="{{ route('repairs.download-report') }}" class="btn btn-enhanced btn-outline-info">
                            <i class="fas fa-download"></i> <span>Descargar Reporte</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Adicional -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i> Historial de la Reparación
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">Reparación Creada</h6>
                                <p class="text-muted mb-0 small">
                                    {{ $repair->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                        @if($repair->updated_at != $repair->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="fw-bold">Última Actualización</h6>
                                <p class="text-muted mb-0 small">
                                    {{ $repair->updated_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -37px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px currentColor;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -32px;
    top: 17px;
    width: 2px;
    height: calc(100% - 5px);
    background: #dee2e6;
}

.timeline-content h6 {
    margin-bottom: 5px;
}
</style>
@endsection

