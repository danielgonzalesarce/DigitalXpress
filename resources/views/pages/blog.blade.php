@extends('layouts.app')

@section('title', 'Blog - DigitalXpress')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <h1 class="display-4 fw-bold mb-4 text-center">Blog</h1>
            <p class="lead text-muted text-center mb-5">Mantente al día con las últimas tendencias en tecnología</p>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-laptop"></i>
                                </div>
                                <div>
                                    <small class="text-muted">15 de Enero, 2024</small>
                                    <h5 class="mb-0 mt-1">Guía Completa: Cómo Elegir el Laptop Perfecto</h5>
                                </div>
                            </div>
                            <p class="text-muted">Descubre los factores clave a considerar al comprar un laptop: procesador, RAM, almacenamiento y más.</p>
                            <a href="#" class="btn btn-outline-primary">Leer más</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div>
                                    <small class="text-muted">10 de Enero, 2024</small>
                                    <h5 class="mb-0 mt-1">Los Mejores Smartphones de 2024</h5>
                                </div>
                            </div>
                            <p class="text-muted">Revisión de los smartphones más destacados del año: características, precios y recomendaciones.</p>
                            <a href="#" class="btn btn-outline-success">Leer más</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-headphones"></i>
                                </div>
                                <div>
                                    <small class="text-muted">5 de Enero, 2024</small>
                                    <h5 class="mb-0 mt-1">Audífonos Inalámbricos: Todo lo que Necesitas Saber</h5>
                                </div>
                            </div>
                            <p class="text-muted">Comparativa de tecnologías de cancelación de ruido, batería y calidad de audio en audífonos inalámbricos.</p>
                            <a href="#" class="btn btn-outline-info">Leer más</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <small class="text-muted">1 de Enero, 2024</small>
                                    <h5 class="mb-0 mt-1">Seguridad Digital: Protege tus Dispositivos</h5>
                                </div>
                            </div>
                            <p class="text-muted">Consejos esenciales para mantener tus dispositivos seguros: antivirus, contraseñas y buenas prácticas.</p>
                            <a href="#" class="btn btn-outline-warning">Leer más</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <p class="text-muted">Próximamente: más artículos sobre tecnología, reviews y guías de compra.</p>
            </div>
        </div>
    </div>
</div>
@endsection

