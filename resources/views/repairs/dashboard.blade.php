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

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="mb-0">2</h4>
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
                            <h4 class="mb-0">5</h4>
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
                            <h4 class="mb-0">1</h4>
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
                            <h4 class="mb-0">8</h4>
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
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-wrench me-2"></i> Reparaciones Activas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Dispositivo</th>
                                    <th>Problema</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#REP-001</td>
                                    <td>iPhone 13 Pro</td>
                                    <td>Pantalla rota</td>
                                    <td><span class="badge bg-warning">En Proceso</span></td>
                                    <td>15 Oct 2025</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#REP-002</td>
                                    <td>MacBook Air M2</td>
                                    <td>Problema de batería</td>
                                    <td><span class="badge bg-info">Diagnóstico</span></td>
                                    <td>16 Oct 2025</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Ver Detalles
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
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
</div>
@endsection
