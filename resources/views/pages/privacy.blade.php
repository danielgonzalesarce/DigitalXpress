@extends('layouts.app')

@section('title', 'Política de Privacidad - DigitalXpress')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <h1 class="display-4 fw-bold mb-4">Política de Privacidad</h1>
            <p class="text-muted mb-4">Última actualización: {{ date('d/m/Y') }}</p>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">1. Información que Recopilamos</h3>
                    <p>Recopilamos información que nos proporcionas directamente, incluyendo:</p>
                    <ul>
                        <li>Nombre y datos de contacto</li>
                        <li>Información de pago</li>
                        <li>Dirección de envío</li>
                        <li>Historial de compras</li>
                        <li>Información de cuenta</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">2. Uso de la Información</h3>
                    <p>Utilizamos tu información para:</p>
                    <ul>
                        <li>Procesar y completar tus pedidos</li>
                        <li>Comunicarnos contigo sobre tu pedido</li>
                        <li>Mejorar nuestros productos y servicios</li>
                        <li>Enviar promociones y ofertas (con tu consentimiento)</li>
                        <li>Cumplir con obligaciones legales</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">3. Protección de Datos</h3>
                    <p>Implementamos medidas de seguridad técnicas y organizativas para proteger tu información personal contra acceso no autorizado, alteración, divulgación o destrucción.</p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">4. Compartir Información</h3>
                    <p>No vendemos ni alquilamos tu información personal. Podemos compartir información con:</p>
                    <ul>
                        <li>Proveedores de servicios que nos ayudan a operar nuestro negocio</li>
                        <li>Empresas de envío para entregar tus pedidos</li>
                        <li>Proveedores de pago para procesar transacciones</li>
                        <li>Cuando sea requerido por ley</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">5. Cookies</h3>
                    <p>Utilizamos cookies para mejorar tu experiencia en nuestro sitio web. Puedes configurar tu navegador para rechazar cookies, aunque esto puede afectar algunas funcionalidades del sitio.</p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">6. Tus Derechos</h3>
                    <p>Tienes derecho a:</p>
                    <ul>
                        <li>Acceder a tu información personal</li>
                        <li>Corregir información incorrecta</li>
                        <li>Solicitar la eliminación de tus datos</li>
                        <li>Oponerte al procesamiento de tus datos</li>
                        <li>Retirar tu consentimiento en cualquier momento</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-3">7. Retención de Datos</h3>
                    <p>Conservamos tu información personal durante el tiempo necesario para cumplir con los propósitos descritos en esta política, a menos que la ley requiera un período de retención más largo.</p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="mb-3">8. Cambios a esta Política</h3>
                    <p>Podemos actualizar esta política de privacidad ocasionalmente. Te notificaremos sobre cambios importantes publicando la nueva política en esta página.</p>
                </div>
            </div>

            <div class="alert alert-info mt-4">
                <h5 class="alert-heading">¿Tienes preguntas sobre privacidad?</h5>
                <p class="mb-0">Contáctanos al <a href="tel:+51936068781">+51 936068781</a> o envía un email a privacidad@digitalxpress.com</p>
            </div>
        </div>
    </div>
</div>
@endsection

