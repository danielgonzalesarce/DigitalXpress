@extends('layouts.app')

@section('title', 'Términos y Condiciones - DigitalXpress')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <h1 class="display-4 fw-bold mb-4">Términos y Condiciones</h1>
            <p class="text-muted mb-4">Última actualización: {{ date('d/m/Y') }}</p>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">1. Aceptación de los Términos</h3>
                    <p>Al acceder y utilizar el sitio web de DigitalXpress, aceptas cumplir con estos términos y condiciones. Si no estás de acuerdo con alguna parte de estos términos, no debes utilizar nuestro sitio web.</p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">2. Uso del Sitio Web</h3>
                    <p>El sitio web de DigitalXpress está destinado para uso personal y no comercial. No puedes:</p>
                    <ul>
                        <li>Utilizar el sitio para fines ilegales o no autorizados</li>
                        <li>Intentar acceder a áreas restringidas del sitio</li>
                        <li>Interferir con el funcionamiento del sitio</li>
                        <li>Reproducir, duplicar o copiar el contenido sin autorización</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">3. Productos y Precios</h3>
                    <p>Nos esforzamos por mantener la información de productos y precios actualizada. Sin embargo:</p>
                    <ul>
                        <li>Los precios están sujetos a cambios sin previo aviso</li>
                        <li>Las imágenes son ilustrativas y pueden variar del producto real</li>
                        <li>Nos reservamos el derecho de modificar o discontinuar productos</li>
                        <li>La disponibilidad de productos está sujeta a stock</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">4. Pedidos y Pagos</h3>
                    <p>Al realizar un pedido:</p>
                    <ul>
                        <li>Debes proporcionar información precisa y completa</li>
                        <li>El pedido está sujeto a confirmación de stock</li>
                        <li>Los pagos se procesan de forma segura</li>
                        <li>Nos reservamos el derecho de rechazar pedidos</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">5. Envíos y Entregas</h3>
                    <p>Los tiempos de envío son estimados y pueden variar. No nos hacemos responsables por retrasos causados por terceros (empresas de envío, aduanas, etc.).</p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">6. Devoluciones y Reembolsos</h3>
                    <p>Las devoluciones están sujetas a nuestra <a href="{{ route('pages.returns') }}">Política de Devoluciones</a>. Los reembolsos se procesarán según el método de pago original.</p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">7. Propiedad Intelectual</h3>
                    <p>Todo el contenido del sitio web, incluyendo textos, gráficos, logos, imágenes, es propiedad de DigitalXpress y está protegido por leyes de propiedad intelectual.</p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">8. Limitación de Responsabilidad</h3>
                    <p>DigitalXpress no será responsable por daños indirectos, incidentales o consecuentes que resulten del uso o la imposibilidad de usar nuestro sitio web o productos.</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="mb-3">9. Modificaciones</h3>
                    <p>Nos reservamos el derecho de modificar estos términos en cualquier momento. Los cambios entrarán en vigor inmediatamente después de su publicación en el sitio web.</p>
                </div>
            </div>

            <div class="alert alert-info mt-4">
                <h5 class="alert-heading">¿Tienes preguntas?</h5>
                <p class="mb-0">Si tienes alguna pregunta sobre estos términos, contáctanos al <a href="tel:+51936068781">+51 936068781</a> o envía un email a soporte@digitalxpress.com</p>
            </div>
        </div>
    </div>
</div>
@endsection

