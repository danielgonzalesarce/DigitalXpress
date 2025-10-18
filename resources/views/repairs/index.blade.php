@extends('layouts.app')

@section('title', 'Servicio Técnico - DigitalXpress')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="text-center mb-5">
        <div class="mb-4">
            <i class="fas fa-wrench fa-4x text-primary"></i>
        </div>
        <h1 class="display-4 fw-bold text-dark mb-3">Servicio Técnico</h1>
        <p class="lead text-muted">
            Repara tu dispositivo con nosotros. Nuestro equipo de expertos está listo para ayudarte a solucionar cualquier problema técnico de manera rápida y profesional.
        </p>
    </div>

    @auth
        <!-- Authenticated User - Show Dashboard Access -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <!-- Success Header -->
                    <div class="card-header bg-gradient-success text-white text-center py-4" style="background: linear-gradient(135deg, #28a745, #20c997);">
                        <div class="mb-3">
                            <i class="fas fa-check-circle fa-3x"></i>
                        </div>
                        <h3 class="fw-bold mb-0">¡Acceso Autorizado!</h3>
                    </div>
                    
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-user-check text-success fa-3x"></i>
                            </div>
                        </div>
                        
                        <h4 class="fw-bold text-success mb-3">¡Bienvenido {{ Auth::user()->name }}!</h4>
                        <p class="fs-5 text-muted mb-4">
                            Ya tienes acceso completo al servicio técnico. Puedes gestionar tus reparaciones, 
                            hacer seguimiento del progreso y acceder a tu historial completo.
                        </p>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('repairs.dashboard') }}" class="btn btn-success btn-lg px-4">
                                <i class="fas fa-tools me-2"></i> Ir al Dashboard
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg px-4">
                                <i class="fas fa-store me-2"></i> Ver Tienda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Guest User - Show Login Required -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <!-- Gradient Header -->
                    <div class="card-header bg-gradient-primary text-white text-center py-4" style="background: linear-gradient(135deg, #007bff, #28a745);">
                        <div class="mb-3">
                            <i class="fas fa-lock fa-3x"></i>
                        </div>
                        <h3 class="fw-bold mb-0">Acceso Restringido</h3>
                    </div>
                    
                    <div class="card-body text-center py-5">
                        <p class="fs-5 text-muted mb-4">
                            Para acceder al servicio técnico y gestionar tus reparaciones, necesitas 
                            <span class="text-primary fw-bold">iniciar sesión o crear una cuenta.</span>
                        </p>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-primary btn-lg px-4" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-arrow-right me-2"></i> Iniciar Sesión
                            </button>
                            <button class="btn btn-outline-secondary btn-lg px-4" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-user-plus me-2"></i> Registrarse
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    <!-- Benefits Section -->
    <div class="row">
        <div class="col-12">
            <h3 class="text-center fw-bold mb-4">Beneficios de crear una cuenta:</h3>
        </div>
    </div>

    <div class="row g-4">
        <!-- Benefit 1 -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-check text-primary fa-2x"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3">Seguimiento en tiempo real</h5>
                    <p class="text-muted">Monitorea el progreso de tus reparaciones en tiempo real y recibe actualizaciones instantáneas.</p>
                </div>
            </div>
        </div>

        <!-- Benefit 2 -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-check text-primary fa-2x"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3">Historial completo</h5>
                    <p class="text-muted">Accede a tu historial completo de servicios técnicos y reparaciones realizadas.</p>
                </div>
            </div>
        </div>

        <!-- Benefit 3 -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-check text-primary fa-2x"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-3">Notificaciones automáticas</h5>
                    <p class="text-muted">Recibe notificaciones de actualizaciones de estado y cuando tu dispositivo esté listo.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                <strong>¿Necesitas ayuda inmediata?</strong> 
                Contacta con nuestro equipo técnico al 
                <a href="tel:+1234567890" class="text-decoration-none fw-bold">+1 (234) 567-890</a>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-header bg-danger text-white">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong class="me-auto">Error</strong>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    </div>
@endif
@endsection
