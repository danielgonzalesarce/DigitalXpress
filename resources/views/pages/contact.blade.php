@extends('layouts.app')

@section('title', 'Contacto - DigitalXpress')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-4 fw-bold mb-4 text-center">Contáctanos</h1>
            <p class="lead text-muted text-center mb-5">Estamos aquí para ayudarte. Envíanos un mensaje y te responderemos lo antes posible.</p>

            <div class="row g-4 mb-5">
                <div class="col-md-4 text-center">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-phone fa-2x"></i>
                            </div>
                            <h5>Teléfono</h5>
                            <p class="text-muted mb-0">
                                <a href="tel:+51936068781" class="text-decoration-none">+51 936068781</a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 text-center">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fab fa-whatsapp fa-2x"></i>
                            </div>
                            <h5>WhatsApp</h5>
                            <p class="text-muted mb-0">
                                <a href="https://wa.me/51936068781" class="text-decoration-none" target="_blank">+51 936068781</a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 text-center">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-envelope fa-2x"></i>
                            </div>
                            <h5>Email</h5>
                            <p class="text-muted mb-0">
                                <a href="https://mail.google.com/mail/?view=cm&fs=1&to=soportedigitalxpress@gmail.com&su=Consulta%20DigitalXpress" 
                                   target="_blank" 
                                   class="text-decoration-none">soportedigitalxpress@gmail.com</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">Envíanos un Mensaje</h3>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Tu mensaje será enviado a: <strong>soportedigitalxpress@gmail.com</strong>
                        </small>
                    </div>
                    
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-2x me-3"></i>
                            <div>
                                <strong>¡Mensaje Enviado!</strong>
                                <p class="mb-0">{{ session('success') }}</p>
                                @if(session('email_sent'))
                                <small class="text-success">
                                    <i class="fas fa-envelope me-1"></i>
                                    El correo fue enviado a: <strong>soportedigitalxpress@gmail.com</strong>
                                </small>
                                @endif
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                            <div>
                                <strong>Error al Enviar</strong>
                                <p class="mb-0">{{ session('error') }}</p>
                                <small class="text-muted">
                                    Puedes contactarnos directamente haciendo clic en el enlace de correo arriba.
                                </small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form action="{{ route('pages.contact.send') }}" method="POST">
                        @csrf
                        @if(Auth::check())
                        <!-- Usuario autenticado: mostrar información pero no permitir editar -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-user me-2"></i>
                            <strong>Enviando como:</strong> {{ Auth::user()->name }} ({{ Auth::user()->email }})
                        </div>
                        <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                        @else
                        <!-- Usuario no autenticado: mostrar campos editables -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nombre *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @endif
                        <div class="mb-3">
                            <label for="subject" class="form-label">Asunto *</label>
                            <input type="text" 
                                   class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" 
                                   name="subject" 
                                   value="{{ old('subject') }}" 
                                   required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Mensaje *</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" 
                                      name="message" 
                                      rows="5" 
                                      placeholder="Escribe tu mensaje aquí (mínimo 10 caracteres)..."
                                      required>{{ old('message') }}</textarea>
                            <div class="form-text">
                                <span id="char-count">Mínimo 10 caracteres (0/10)</span>
                            </div>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Prioridad del Mensaje -->
                        <div class="mb-4">
                            <label class="form-label">Prioridad del Mensaje *</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="priority" id="priority_low" value="low" {{ old('priority', 'medium') == 'low' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="priority_low">
                                            <span class="badge bg-success">Baja</span> - Consulta general
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="priority" id="priority_medium" value="medium" {{ old('priority', 'medium') == 'medium' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="priority_medium">
                                            <span class="badge bg-warning">Media</span> - Problema técnico
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="priority" id="priority_high" value="high" {{ old('priority') == 'high' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="priority_high">
                                            <span class="badge bg-danger">Alta</span> - Urgente
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('priority')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" id="submit-btn" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-paper-plane me-2"></i> Enviar Mensaje
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-clock me-2"></i> Horarios de Atención</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>Lunes a Viernes:</strong> 9:00 AM - 7:00 PM</li>
                        <li class="mb-2"><strong>Sábados:</strong> 9:00 AM - 2:00 PM</li>
                        <li><strong>Domingos:</strong> Cerrado</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Contador de caracteres para el mensaje
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('char-count');
    const submitBtn = document.getElementById('submit-btn');
    const minLength = 10;
    
    if (messageTextarea && charCount) {
        messageTextarea.addEventListener('input', function() {
            const length = this.value.length;
            const remaining = minLength - length;
            
            if (remaining > 0) {
                charCount.textContent = `Mínimo 10 caracteres (${length}/${minLength})`;
                charCount.className = 'form-text text-warning';
            } else {
                charCount.textContent = `${length} caracteres`;
                charCount.className = 'form-text text-success';
            }
        });
    }
    
    // Validación antes de enviar
    const form = document.querySelector('form[action="{{ route('pages.contact.send') }}"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const message = messageTextarea.value.trim();
            const subject = document.getElementById('subject').value.trim();
            const priority = document.querySelector('input[name="priority"]:checked');
            
            if (message.length < minLength) {
                e.preventDefault();
                alert('El mensaje debe tener al menos 10 caracteres.');
                messageTextarea.focus();
                return false;
            }
            
            if (!subject) {
                e.preventDefault();
                alert('Por favor, escribe un asunto.');
                document.getElementById('subject').focus();
                return false;
            }
            
            if (!priority) {
                e.preventDefault();
                alert('Por favor, selecciona una prioridad para tu mensaje.');
                return false;
            }
            
            // Mostrar estado de carga
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
            
            // El formulario se enviará automáticamente
            return true;
        });
    }
});
</script>
@endpush

@endsection

