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
                                <a href="mailto:soporte@digitalxpress.com" class="text-decoration-none">soporte@digitalxpress.com</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="mb-4">Envíanos un Mensaje</h3>
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Asunto *</label>
                            <input type="text" class="form-control" id="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Mensaje *</label>
                            <textarea class="form-control" id="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">
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
@endsection

