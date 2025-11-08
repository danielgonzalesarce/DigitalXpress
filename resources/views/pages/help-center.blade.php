@extends('layouts.app')

@section('title', 'Centro de Ayuda - DigitalXpress')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="display-4 fw-bold mb-4">Centro de Ayuda</h1>
            <p class="lead text-muted mb-5">Encuentra respuestas a las preguntas más frecuentes y obtén el soporte que necesitas.</p>

            <!-- Preguntas Frecuentes -->
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            ¿Cómo puedo realizar un pedido?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Puedes realizar un pedido navegando por nuestros productos, agregándolos al carrito y completando el proceso de checkout. Aceptamos pagos con tarjeta, transferencia bancaria y Yape.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            ¿Cuáles son los métodos de pago disponibles?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Aceptamos pagos con tarjeta de crédito/débito, transferencia bancaria, Yape y otros métodos de pago digital. Todos los pagos son procesados de forma segura.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            ¿Cuánto tiempo tarda el envío?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Los envíos dentro de la ciudad se realizan en 24-48 horas. Para envíos a provincias, el tiempo estimado es de 3-5 días hábiles. Te notificaremos el estado de tu pedido por email.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            ¿Puedo cancelar mi pedido?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Sí, puedes cancelar tu pedido antes de que sea enviado. Contacta con nuestro equipo de atención al cliente al <a href="tel:+51936068781">+51 936068781</a> o envía un email a soporte@digitalxpress.com.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                            ¿Ofrecen servicio técnico?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Sí, ofrecemos servicio técnico profesional para todos nuestros productos. Puedes agendar una cita visitando nuestra sección de <a href="{{ route('repairs.index') }}">Reparaciones</a>.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contacto de Soporte -->
            <div class="card mt-5 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title mb-3">¿No encuentras lo que buscas?</h3>
                    <p class="card-text text-muted mb-4">Nuestro equipo de soporte está disponible para ayudarte.</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="tel:+51936068781" class="btn btn-primary">
                            <i class="fas fa-phone me-2"></i> Llamar: +51 936068781
                        </a>
                        <a href="{{ route('pages.contact') }}" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i> Enviar Mensaje
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

