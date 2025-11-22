@extends('layouts.admin')

@section('title', 'Configuración')
@section('page-title', 'Configuración')
@section('page-subtitle', 'Ajustes del sistema • DigitalXpress')

@section('content')
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid #10b981;">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
            <div class="flex-grow-1">
                <strong>¡Éxito!</strong>
                <p class="mb-0">{{ session('success') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid #ef4444;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle fa-2x me-3 text-danger"></i>
            <div class="flex-grow-1">
                <strong>Error</strong>
                <p class="mb-0">{{ session('error') }}</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <!-- General Settings -->
        <div class="col-lg-8">
            <div class="orders-section">
                <h3 class="mb-4">
                    <i class="fas fa-cog me-2"></i>
                    Configuración General
                </h3>
                
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-store me-2"></i>
                            Información de la Tienda
                        </h5>
                        <p class="text-muted">Configura el nombre, descripción y datos de contacto de tu tienda.</p>
                        <div class="mb-2">
                            <strong>Nombre:</strong> {{ $storeSettings['store_name'] ?? 'DigitalXpress' }}<br>
                            <strong>Email:</strong> {{ $storeSettings['store_email'] ?? 'soporte@digitalxpress.com' }}<br>
                            <strong>Teléfono:</strong> {{ $storeSettings['store_phone'] ?? '+51 936068781' }}
                        </div>
                        <a href="{{ route('admin.settings.store') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>
                            Editar Información de la Tienda
                        </a>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-shipping-fast me-2"></i>
                            Configuración de Envíos
                        </h5>
                        <p class="text-muted">Gestiona las opciones de envío y costos de transporte.</p>
                        <div class="mb-2">
                            <strong>Estado:</strong> {{ ($shippingSettings['shipping_enabled'] ?? '1') == '1' ? 'Habilitado' : 'Deshabilitado' }}<br>
                            <strong>Costo Base:</strong> S/ {{ number_format($shippingSettings['shipping_cost'] ?? 10, 2) }}<br>
                            <strong>Envío Gratis:</strong> S/ {{ number_format($shippingSettings['free_shipping_threshold'] ?? 100, 2) }}
                        </div>
                        <a href="{{ route('admin.settings.shipping') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>
                            Editar Configuración de Envíos
                        </a>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-credit-card me-2"></i>
                            Métodos de Pago
                        </h5>
                        <p class="text-muted">Configura los métodos de pago disponibles.</p>
                        <div class="mb-2">
                            <strong>Métodos Habilitados:</strong><br>
                            @if(($paymentSettings['payment_credit_card'] ?? '1') == '1') ✓ Tarjeta de Crédito<br> @endif
                            @if(($paymentSettings['payment_debit_card'] ?? '1') == '1') ✓ Tarjeta de Débito<br> @endif
                            @if(($paymentSettings['payment_yape'] ?? '1') == '1') ✓ Yape<br> @endif
                            @if(($paymentSettings['payment_cash'] ?? '1') == '1') ✓ Efectivo<br> @endif
                        </div>
                        <a href="{{ route('admin.settings.payment') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>
                            Editar Métodos de Pago
                        </a>
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
