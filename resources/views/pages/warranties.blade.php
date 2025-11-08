@extends('layouts.app')

@section('title', 'Garantías - DigitalXpress')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <h1 class="display-4 fw-bold mb-4">Política de Garantías</h1>
            <p class="lead text-muted mb-5">Tu tranquilidad es nuestra prioridad. Conoce todos los detalles sobre nuestras garantías.</p>

            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-shield-alt fa-lg"></i>
                                </div>
                                <h4 class="mb-0">Garantía de Fábrica</h4>
                            </div>
                            <p class="text-muted">Todos nuestros productos incluyen garantía oficial del fabricante, que varía según el producto (generalmente 1 año).</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-check-circle fa-lg"></i>
                                </div>
                                <h4 class="mb-0">Garantía DigitalXpress</h4>
                            </div>
                            <p class="text-muted">Además de la garantía del fabricante, ofrecemos garantía adicional de 30 días para defectos de fabricación.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-info-circle me-2"></i> ¿Qué cubre la garantía?</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="fas fa-check text-success me-2"></i> Defectos de fabricación</li>
                        <li class="mb-3"><i class="fas fa-check text-success me-2"></i> Fallas en componentes originales</li>
                        <li class="mb-3"><i class="fas fa-check text-success me-2"></i> Problemas de funcionamiento dentro del período de garantía</li>
                        <li class="mb-3"><i class="fas fa-check text-success me-2"></i> Reparación o reemplazo según corresponda</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i> ¿Qué NO cubre la garantía?</h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3"><i class="fas fa-times text-danger me-2"></i> Daños por mal uso o negligencia</li>
                        <li class="mb-3"><i class="fas fa-times text-danger me-2"></i> Modificaciones no autorizadas</li>
                        <li class="mb-3"><i class="fas fa-times text-danger me-2"></i> Daños por accidentes o caídas</li>
                        <li class="mb-3"><i class="fas fa-times text-danger me-2"></i> Desgaste normal del producto</li>
                        <li class="mb-3"><i class="fas fa-times text-danger me-2"></i> Daños por exposición a líquidos (a menos que el producto sea resistente al agua)</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="mb-4">Proceso de Reclamación de Garantía</h3>
                    <ol class="mb-0">
                        <li class="mb-3">Contacta con nuestro equipo de soporte al <a href="tel:+51936068781">+51 936068781</a> o envía un email a soporte@digitalxpress.com</li>
                        <li class="mb-3">Proporciona el número de pedido y detalles del problema</li>
                        <li class="mb-3">Nuestro equipo evaluará tu caso y te guiará en el proceso</li>
                        <li class="mb-3">Si aplica, coordinaremos la reparación o reemplazo del producto</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

