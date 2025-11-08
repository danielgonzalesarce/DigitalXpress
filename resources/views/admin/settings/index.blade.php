@extends('layouts.admin')

@section('title', 'Configuración')
@section('page-title', 'Configuración')
@section('page-subtitle', 'Ajustes del sistema • DigitalXpress')

@section('content')
    <div class="row g-4">
        <!-- General Settings -->
        <div class="col-lg-8">
            <div class="orders-section">
                <h3 class="mb-4">
                    <i class="fas fa-cog me-2"></i>
                    Configuración General
                </h3>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nota:</strong> Esta sección está en desarrollo. Próximamente podrás configurar las opciones del sistema.
                </div>
                
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-store me-2"></i>
                            Información de la Tienda
                        </h5>
                        <p class="text-muted">Configura el nombre, descripción y datos de contacto de tu tienda.</p>
                        <button class="btn btn-outline-primary" disabled>
                            <i class="fas fa-edit me-2"></i>
                            Editar (Próximamente)
                        </button>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-shipping-fast me-2"></i>
                            Configuración de Envíos
                        </h5>
                        <p class="text-muted">Gestiona las opciones de envío y costos de transporte.</p>
                        <button class="btn btn-outline-primary" disabled>
                            <i class="fas fa-edit me-2"></i>
                            Editar (Próximamente)
                        </button>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-credit-card me-2"></i>
                            Métodos de Pago
                        </h5>
                        <p class="text-muted">Configura los métodos de pago disponibles.</p>
                        <button class="btn btn-outline-primary" disabled>
                            <i class="fas fa-edit me-2"></i>
                            Editar (Próximamente)
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-4">
            <div class="orders-section">
                <h3 class="mb-4">
                    <i class="fas fa-chart-bar me-2"></i>
                    Estadísticas Rápidas
                </h3>
                
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Total Productos</h6>
                                <small class="text-muted">Activos en el sistema</small>
                            </div>
                            <div class="text-primary">
                                <strong>{{ \App\Models\Product::where('is_active', true)->count() }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Total Usuarios</h6>
                                <small class="text-muted">Registrados</small>
                            </div>
                            <div class="text-primary">
                                <strong>{{ \App\Models\User::count() }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Total Pedidos</h6>
                                <small class="text-muted">Excluyendo simulaciones</small>
                            </div>
                            <div class="text-primary">
                                <strong>{{ \App\Models\Order::where('status', '!=', 'demo_simulation')->count() }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

