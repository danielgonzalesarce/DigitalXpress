@extends('layouts.app')

@section('title', 'Sobre Nosotros - DigitalXpress')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <h1 class="display-4 fw-bold mb-4 text-center">Sobre Nosotros</h1>
            <p class="lead text-muted text-center mb-5">Tu tienda de tecnología de confianza desde 2020</p>

            <div class="card shadow-sm mb-5">
                <div class="card-body p-5">
                    <h2 class="mb-4">Nuestra Historia</h2>
                    <p class="mb-4">DigitalXpress nació con la visión de hacer la tecnología accesible para todos. Desde nuestros inicios, nos hemos comprometido a ofrecer los mejores productos tecnológicos con precios competitivos y un servicio al cliente excepcional.</p>
                    <p class="mb-0">Somos más que una tienda; somos tu socio tecnológico de confianza, ayudándote a encontrar exactamente lo que necesitas para tu vida digital.</p>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 text-center">
                        <div class="card-body">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-bullseye fa-3x"></i>
                            </div>
                            <h4>Nuestra Misión</h4>
                            <p class="text-muted">Proporcionar tecnología de calidad a precios accesibles, con un servicio al cliente excepcional que supere las expectativas de nuestros clientes.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 text-center">
                        <div class="card-body">
                            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-eye fa-3x"></i>
                            </div>
                            <h4>Nuestra Visión</h4>
                            <p class="text-muted">Ser la tienda de tecnología líder en el mercado, reconocida por nuestra innovación, calidad y compromiso con la satisfacción del cliente.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 text-center">
                        <div class="card-body">
                            <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-heart fa-3x"></i>
                            </div>
                            <h4>Nuestros Valores</h4>
                            <p class="text-muted">Integridad, calidad, innovación y compromiso con nuestros clientes. Estos valores guían cada decisión que tomamos.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h2 class="mb-4">¿Por qué elegir DigitalXpress?</h2>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>Productos Auténticos</h5>
                                    <p class="text-muted mb-0">Todos nuestros productos son 100% originales y cuentan con garantía oficial del fabricante.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>Precios Competitivos</h5>
                                    <p class="text-muted mb-0">Ofrecemos los mejores precios del mercado sin comprometer la calidad.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>Servicio Técnico</h5>
                                    <p class="text-muted mb-0">Contamos con un equipo de técnicos profesionales para reparar tus dispositivos.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>Atención 24/7</h5>
                                    <p class="text-muted mb-0">Nuestro equipo de soporte está disponible para ayudarte cuando lo necesites.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

