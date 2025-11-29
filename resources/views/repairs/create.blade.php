@extends('layouts.app')

@section('title', 'Nueva Reparación - DigitalXpress')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="text-center mb-5">
        <div class="mb-4">
            <i class="fas fa-tools fa-4x text-primary"></i>
        </div>
        <h1 class="display-4 fw-bold text-dark mb-3">Solicitud de Reparación</h1>
        <p class="lead text-muted">
            Repara tu dispositivo con nosotros. Nuestro equipo de expertos está listo para ayudarte a solucionar cualquier problema técnico de manera rápida y profesional.
        </p>
    </div>

    <div class="row">
        <!-- Formulario de Reparación -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <form action="{{ route('repairs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Nombre Completo -->
                            <div class="col-md-6 mb-4">
                                <label for="full_name" class="form-label fw-bold">
                                    Nombre Completo <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('full_name') is-invalid @enderror" 
                                       id="full_name" 
                                       name="full_name" 
                                       value="{{ old('full_name', Auth::user()->name) }}" 
                                       placeholder="Ej: Juan Pérez"
                                       required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Correo Electrónico -->
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label fw-bold">
                                    Correo Electrónico <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', Auth::user()->email) }}" 
                                       placeholder="correo@ejemplo.com"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="phone" class="form-label fw-bold">
                                    Teléfono <span class="text-danger">*</span>
                                </label>
                                <input type="tel" 
                                       class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}" 
                                       placeholder="+51 987654321"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tipo de Dispositivo -->
                            <div class="col-md-6 mb-4">
                                <label for="device_type" class="form-label fw-bold">
                                    Tipo de Dispositivo <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg @error('device_type') is-invalid @enderror" 
                                        id="device_type" 
                                        name="device_type" 
                                        required>
                                    <option value="">Seleccione un tipo</option>
                                    <option value="Smartphone" {{ old('device_type') == 'Smartphone' ? 'selected' : '' }}>Smartphone</option>
                                    <option value="Laptop" {{ old('device_type') == 'Laptop' ? 'selected' : '' }}>Laptop</option>
                                    <option value="Tablet" {{ old('device_type') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                    <option value="Desktop" {{ old('device_type') == 'Desktop' ? 'selected' : '' }}>Desktop</option>
                                    <option value="Smartwatch" {{ old('device_type') == 'Smartwatch' ? 'selected' : '' }}>Smartwatch</option>
                                    <option value="Auriculares" {{ old('device_type') == 'Auriculares' ? 'selected' : '' }}>Auriculares</option>
                                    <option value="Otro" {{ old('device_type') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                                @error('device_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Marca y Modelo -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="brand" class="form-label fw-bold">
                                    Marca <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('brand') is-invalid @enderror" 
                                       id="brand" 
                                       name="brand" 
                                       value="{{ old('brand') }}" 
                                       placeholder="Ej: Apple"
                                       required>
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="model" class="form-label fw-bold">
                                    Modelo <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('model') is-invalid @enderror" 
                                       id="model" 
                                       name="model" 
                                       value="{{ old('model') }}" 
                                       placeholder="Ej: iPhone 13"
                                       required>
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Descripción del Problema -->
                        <div class="mb-4">
                            <label for="problem_description" class="form-label fw-bold">
                                Descripción del Problema <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('problem_description') is-invalid @enderror" 
                                      id="problem_description" 
                                      name="problem_description" 
                                      rows="4" 
                                      placeholder="Describe detalladamente el problema de tu dispositivo..."
                                      required>{{ old('problem_description') }}</textarea>
                            <div class="form-text">
                                <span id="char-count">Mínimo 20 caracteres (0/20)</span>
                            </div>
                            @error('problem_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subir Imagen del Dispositivo -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Subir Imagen del Dispositivo (Opcional)</label>
                            <div class="upload-area border-2 border-dashed border-secondary rounded p-4 text-center" 
                                 style="cursor: pointer;" 
                                 onclick="document.getElementById('device_image').click()">
                                <input type="file" 
                                       class="d-none" 
                                       id="device_image" 
                                       name="device_image" 
                                       accept="image/*">
                                <div class="upload-content">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Click para subir imagen</p>
                                    <small class="text-muted">Formatos: JPG, PNG, GIF (Máx. 2MB)</small>
                                </div>
                            </div>
                            @error('device_image')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botón Enviar -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-enhanced btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i> <span>Enviar Solicitud</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mis Reparaciones -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Mis Reparaciones</h5>
                    
                    @if($repairs->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($repairs as $repair)
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $repair->device_type }} - {{ $repair->brand }}</h6>
                                            <p class="mb-1 text-muted">{{ $repair->model }}</p>
                                            <small class="text-muted">{{ $repair->created_at->format('d/m/Y') }}</small>
                                        </div>
                                        <span class="badge bg-{{ $repair->status_badge }}">
                                            {{ $repair->status_text }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-wrench fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aún no tienes reparaciones registradas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="fas fa-check-circle me-2"></i>
                <strong class="me-auto">Éxito</strong>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Contador de caracteres
    const textarea = document.getElementById('problem_description');
    const charCount = document.getElementById('char-count');
    const minLength = 20;
    
    textarea.addEventListener('input', function() {
        const length = this.value.length;
        const remaining = minLength - length;
        
        if (remaining > 0) {
            charCount.textContent = `Mínimo 20 caracteres (${length}/${minLength})`;
            charCount.className = 'form-text text-warning';
        } else {
            charCount.textContent = `${length} caracteres`;
            charCount.className = 'form-text text-success';
        }
    });
    
    // Preview de imagen
    const fileInput = document.getElementById('device_image');
    const uploadArea = document.querySelector('.upload-area');
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                uploadArea.innerHTML = `
                    <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px;">
                    <p class="text-muted mt-2 mb-0">${file.name}</p>
                    <small class="text-muted">Click para cambiar imagen</small>
                `;
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Validación en tiempo real
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('input', function() {
        const requiredFields = form.querySelectorAll('input[required], select[required], textarea[required]');
        let allValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                allValid = false;
            }
        });
        
        const description = document.getElementById('problem_description').value;
        if (description.length < 20) {
            allValid = false;
        }
        
        submitBtn.disabled = !allValid;
    });
    
    // Inicializar estado del botón
    form.dispatchEvent(new Event('input'));
});
</script>
@endpush
