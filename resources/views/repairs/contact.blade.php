@extends('layouts.app')

@section('title', 'Contactar Soporte - DigitalXpress')

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('repairs.dashboard') }}" 
                   class="btn-back-dashboard me-4">
                    <i class="fas fa-arrow-left"></i> 
                    <span>Volver al Dashboard</span>
                </a>
                <div>
                    <h1 class="fw-bold text-primary mb-0">
                        <i class="fas fa-headset me-2"></i> Contactar Soporte
                    </h1>
                    <p class="text-muted mb-0">Nuestro equipo está listo para ayudarte</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilos mejorados para el botón Volver al Dashboard */
        .btn-back-dashboard {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            color: #495057;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .btn-back-dashboard::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 123, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .btn-back-dashboard:hover::before {
            left: 100%;
        }

        .btn-back-dashboard i {
            transition: transform 0.3s ease;
            color: #007bff;
        }

        .btn-back-dashboard:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-color: #007bff;
            color: #007bff;
            transform: translateX(-5px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
        }

        .btn-back-dashboard:hover i {
            transform: translateX(-3px);
        }

        .btn-back-dashboard:active {
            transform: translateX(-3px) scale(0.98);
        }

        .btn-back-dashboard span {
            position: relative;
            z-index: 1;
        }
    </style>

    <div class="row">
        <!-- Formulario de Contacto -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-envelope me-2"></i> Envíanos un Mensaje
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="#" method="POST" id="contactForm">
                        @csrf
                        
                        <div class="row">
                            <!-- Nombre -->
                            <div class="col-md-6 mb-4">
                                <label for="name" class="form-label fw-bold">
                                    Nombre Completo <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ Auth::user()->name }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label fw-bold">
                                    Correo Electrónico <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ Auth::user()->email }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-bold">
                                Teléfono de Contacto
                            </label>
                            <input type="tel" 
                                   class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   placeholder="+51 987654321">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Asunto -->
                        <div class="mb-4">
                            <label for="subject" class="form-label fw-bold">
                                Asunto <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg @error('subject') is-invalid @enderror" 
                                    id="subject" 
                                    name="subject" 
                                    required>
                                <option value="">Seleccione el asunto</option>
                                <option value="consulta_general">Consulta General</option>
                                <option value="problema_tecnico">Problema Técnico</option>
                                <option value="estado_reparacion">Estado de Reparación</option>
                                <option value="cita_urgencia">Cita de Urgencia</option>
                                <option value="reclamo">Reclamo</option>
                                <option value="sugerencia">Sugerencia</option>
                                <option value="otro">Otro</option>
                            </select>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mensaje -->
                        <div class="mb-4">
                            <label for="message" class="form-label fw-bold">
                                Mensaje <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" 
                                      name="message" 
                                      rows="5" 
                                      placeholder="Describe tu consulta o problema de manera detallada..."
                                      required></textarea>
                            <div class="form-text">
                                <span id="char-count">Mínimo 20 caracteres (0/20)</span>
                            </div>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Prioridad -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Prioridad del Mensaje</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="priority" id="priority_low" value="low" checked>
                                        <label class="form-check-label" for="priority_low">
                                            <span class="badge bg-success">Baja</span> - Consulta general
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="priority" id="priority_medium" value="medium">
                                        <label class="form-check-label" for="priority_medium">
                                            <span class="badge bg-warning">Media</span> - Problema técnico
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="priority" id="priority_high" value="high">
                                        <label class="form-check-label" for="priority_high">
                                            <span class="badge bg-danger">Alta</span> - Urgente
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botón Enviar -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane me-2"></i> Enviar Mensaje
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Información de Contacto -->
        <div class="col-lg-4">
            <!-- Canales de Contacto -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-phone me-2"></i> Canales de Contacto
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone text-success me-3"></i>
                            <div>
                                <h6 class="mb-0">Teléfono</h6>
                                <a href="tel:+51936068781" class="text-decoration-none">+51 936068781</a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope text-primary me-3"></i>
                            <div>
                                <h6 class="mb-0">Email</h6>
                                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=soportedigitalxpress@gmail.com&su=Consulta%20Reparaciones" target="_blank" class="text-decoration-none">soportedigitalxpress@gmail.com</a>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fab fa-whatsapp text-success me-3"></i>
                            <div>
                                <h6 class="mb-0">WhatsApp</h6>
                                <a href="https://wa.me/51936068781" class="text-decoration-none">+51 936068781</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horarios de Atención -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i> Horarios de Atención
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Lunes - Viernes:</strong><br>
                        <span class="text-muted">09:00 AM - 06:00 PM</span>
                    </div>
                    <div class="mb-2">
                        <strong>Sábados:</strong><br>
                        <span class="text-muted">09:00 AM - 02:00 PM</span>
                    </div>
                    <div class="mb-2">
                        <strong>Domingos:</strong><br>
                        <span class="text-muted">Cerrado</span>
                    </div>
                </div>
            </div>

            <!-- Tiempo de Respuesta -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-stopwatch me-2"></i> Tiempo de Respuesta
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-danger">Alta Prioridad:</span><br>
                        <small class="text-muted">1-2 horas</small>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-warning">Media Prioridad:</span><br>
                        <small class="text-muted">4-6 horas</small>
                    </div>
                    <div class="mb-2">
                        <span class="badge bg-success">Baja Prioridad:</span><br>
                        <small class="text-muted">24 horas</small>
                    </div>
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

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Contador de caracteres
    const textarea = document.getElementById('message');
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
    
    // Simular envío de formulario
    const form = document.getElementById('contactForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const message = formData.get('message');
        const subject = formData.get('subject');
        const priority = formData.get('priority');
        
        if (!message || !subject) {
            alert('Por favor completa todos los campos obligatorios.');
            return;
        }
        
        if (message.length < 20) {
            alert('El mensaje debe tener al menos 20 caracteres.');
            return;
        }
        
        // Simular confirmación
        const confirmed = confirm(`¿Enviar mensaje de ${priority} prioridad sobre "${subject}"?`);
        
        if (confirmed) {
            alert('¡Mensaje enviado exitosamente! Te responderemos pronto.');
            form.reset();
            // Restaurar valores pre-llenados
            document.getElementById('name').value = '{{ Auth::user()->name }}';
            document.getElementById('email').value = '{{ Auth::user()->email }}';
        }
    });
});
</script>
@endpush
