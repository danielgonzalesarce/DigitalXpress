@extends('layouts.app')

@section('title', 'En Desarrollo - DigitalXpress')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="text-center">
                <!-- Icono de construcción -->
                <div class="mb-4">
                    <i class="fas fa-tools fa-5x text-warning"></i>
                </div>
                
                <!-- Título -->
                <h1 class="display-4 fw-bold mb-3">En Desarrollo</h1>
                
                <!-- Mensaje -->
                <p class="lead text-muted mb-4">
                    Esta funcionalidad está actualmente en desarrollo.
                </p>
                
                <p class="text-muted mb-5">
                    Estamos trabajando para mejorar tu experiencia. Pronto estará disponible.
                </p>
                
                <!-- Botón para volver -->
                <div class="d-flex gap-3 justify-content-center">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i>
                        Volver al Inicio
                    </a>
                    @auth
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-user me-2"></i>
                            Mi Perfil
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

