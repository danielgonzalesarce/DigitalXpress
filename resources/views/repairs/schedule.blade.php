@extends('layouts.app')

@section('title', 'Agendar Cita - DigitalXpress')

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
                        <i class="fas fa-calendar me-2"></i> Agendar Cita
                    </h1>
                    <p class="text-muted mb-0">Programa una cita para el servicio técnico</p>
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
        <!-- Formulario de Cita -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i> Información de la Cita
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="#" method="POST" id="scheduleForm">
                        @csrf
                        
                        <div class="row">
                            <!-- Fecha -->
                            <div class="col-md-6 mb-4">
                                <label for="appointment_date" class="form-label fw-bold">
                                    Fecha de la Cita <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control form-control-lg @error('appointment_date') is-invalid @enderror" 
                                       id="appointment_date" 
                                       name="appointment_date" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       required>
                                @error('appointment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Hora -->
                            <div class="col-md-6 mb-4">
                                <label for="appointment_time" class="form-label fw-bold">
                                    Hora de la Cita <span class="text-danger">*</span>
                                </label>
                                <select class="form-select form-select-lg @error('appointment_time') is-invalid @enderror" 
                                        id="appointment_time" 
                                        name="appointment_time" 
                                        required>
                                    <option value="">Seleccione una hora</option>
                                    <option value="09:00">09:00 AM</option>
                                    <option value="10:00">10:00 AM</option>
                                    <option value="11:00">11:00 AM</option>
                                    <option value="12:00">12:00 PM</option>
                                    <option value="14:00">02:00 PM</option>
                                    <option value="15:00">03:00 PM</option>
                                    <option value="16:00">04:00 PM</option>
                                    <option value="17:00">05:00 PM</option>
                                </select>
                                @error('appointment_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tipo de Servicio -->
                        <div class="mb-4">
                            <label for="service_type" class="form-label fw-bold">
                                Tipo de Servicio <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-lg @error('service_type') is-invalid @enderror" 
                                    id="service_type" 
                                    name="service_type" 
                                    required>
                                <option value="">Seleccione el tipo de servicio</option>
                                <option value="diagnostico">Diagnóstico General</option>
                                <option value="reparacion">Reparación</option>
                                <option value="mantenimiento">Mantenimiento</option>
                                <option value="consulta">Consulta Técnica</option>
                                <option value="entrega">Entrega de Equipo</option>
                            </select>
                            @error('service_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dispositivo -->
                        <div class="mb-4">
                            <label for="device_info" class="form-label fw-bold">
                                Información del Dispositivo
                            </label>
                            <textarea class="form-control @error('device_info') is-invalid @enderror" 
                                      id="device_info" 
                                      name="device_info" 
                                      rows="3" 
                                      placeholder="Describe brevemente el dispositivo y el problema..."></textarea>
                            @error('device_info')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notas Adicionales -->
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold">
                                Notas Adicionales
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="2" 
                                      placeholder="Cualquier información adicional que consideres importante..."></textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botón Enviar -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-enhanced btn-primary btn-lg">
                                <i class="fas fa-calendar-check"></i> <span>Confirmar Cita</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Información de Horarios -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i> Horarios de Atención
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold text-primary">Lunes - Viernes</h6>
                        <p class="mb-0">09:00 AM - 06:00 PM</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold text-primary">Sábados</h6>
                        <p class="mb-0">09:00 AM - 02:00 PM</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="fw-bold text-primary">Domingos</h6>
                        <p class="mb-0 text-muted">Cerrado</p>
                    </div>
                    
                    <hr>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Nota:</strong> Las citas se confirman por email y teléfono.
                    </div>
                </div>
            </div>

            <!-- Contacto Rápido -->
            <div class="card shadow-lg border-0 mt-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-phone me-2"></i> Contacto Rápido
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">¿Necesitas ayuda inmediata?</p>
                    <div class="d-grid gap-2">
                        <a href="tel:+51936068781" class="btn btn-success">
                            <i class="fas fa-phone me-2"></i> Llamar Ahora
                        </a>
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=soportedigitalxpress@gmail.com&su=Consulta%20Reparaciones" target="_blank" class="btn btn-outline-success">
                            <i class="fas fa-envelope me-2"></i> Enviar Email
                        </a>
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
    const form = document.getElementById('scheduleForm');
    const dateInput = document.getElementById('appointment_date');
    const timeSelect = document.getElementById('appointment_time');
    
    // Validar fecha mínima
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    const minDate = tomorrow.toISOString().split('T')[0];
    dateInput.min = minDate;
    
    // Validar que no sea domingo
    dateInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const dayOfWeek = selectedDate.getDay();
        
        if (dayOfWeek === 0) { // Domingo
            alert('No trabajamos los domingos. Por favor selecciona otro día.');
            this.value = '';
        }
    });
    
    // Simular envío de formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const appointmentDate = formData.get('appointment_date');
        const appointmentTime = formData.get('appointment_time');
        const serviceType = formData.get('service_type');
        
        if (!appointmentDate || !appointmentTime || !serviceType) {
            alert('Por favor completa todos los campos obligatorios.');
            return;
        }
        
        // Simular confirmación
        const confirmed = confirm(`¿Confirmar cita para el ${appointmentDate} a las ${appointmentTime}?`);
        
        if (confirmed) {
            alert('¡Cita agendada exitosamente! Te contactaremos pronto para confirmar.');
            form.reset();
        }
    });
});
</script>
@endpush
