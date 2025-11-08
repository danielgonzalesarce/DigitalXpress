@extends('layouts.app')

@section('title', 'Política de Devoluciones - DigitalXpress')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <h1 class="display-4 fw-bold mb-4">Política de Devoluciones</h1>
            <p class="lead text-muted mb-5">Tu satisfacción es importante para nosotros. Conoce cómo puedes devolver o cambiar un producto.</p>

            <div class="alert alert-info mb-5">
                <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i> Período de Devolución</h5>
                <p class="mb-0">Tienes <strong>15 días calendario</strong> desde la fecha de recepción para solicitar una devolución o cambio.</p>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="card-body">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-box fa-2x"></i>
                            </div>
                            <h5>Producto Original</h5>
                            <p class="text-muted small">El producto debe estar en su empaque original, sin usar y con todos los accesorios.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="card-body">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-receipt fa-2x"></i>
                            </div>
                            <h5>Factura o Boleta</h5>
                            <p class="text-muted small">Debes presentar la factura o boleta de compra original para procesar la devolución.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="card-body">
                            <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-calendar-alt fa-2x"></i>
                            </div>
                            <h5>15 Días</h5>
                            <p class="text-muted small">El plazo máximo para solicitar una devolución es de 15 días desde la recepción.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0"><i class="fas fa-check-circle me-2"></i> Condiciones para Devolución</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Producto sin uso y en su empaque original</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Todos los accesorios y manuales incluidos</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Etiquetas y sellos originales intactos</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Factura o boleta de compra</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Solicitud dentro del período de 15 días</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0"><i class="fas fa-times-circle me-2"></i> No Aceptamos Devoluciones de</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-times text-danger me-2"></i> Productos personalizados o configurados especialmente</li>
                        <li class="mb-2"><i class="fas fa-times text-danger me-2"></i> Software o licencias activadas</li>
                        <li class="mb-2"><i class="fas fa-times text-danger me-2"></i> Productos dañados por mal uso</li>
                        <li class="mb-2"><i class="fas fa-times text-danger me-2"></i> Productos sin empaque original</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="mb-4">Proceso de Devolución</h3>
                    <ol>
                        <li class="mb-3">Contacta con nuestro equipo al <a href="tel:+51936068781">+51 936068781</a> o email a soporte@digitalxpress.com</li>
                        <li class="mb-3">Proporciona el número de pedido y motivo de devolución</li>
                        <li class="mb-3">Recibirás instrucciones para el envío del producto</li>
                        <li class="mb-3">Una vez recibido y verificado, procesaremos el reembolso en 5-7 días hábiles</li>
                    </ol>
                    <div class="alert alert-warning mt-4">
                        <strong>Nota:</strong> Los costos de envío de la devolución corren por cuenta del cliente, excepto en casos de productos defectuosos o errores de nuestra parte.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

