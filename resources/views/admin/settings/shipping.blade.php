@extends('layouts.admin')

@section('title', 'Editar Configuración de Envíos')
@section('page-title', 'Editar Configuración de Envíos')
@section('page-subtitle', 'Gestionar opciones de envío • DigitalXpress')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="orders-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Configuración de Envíos</h3>
                    <a href="{{ route('admin.settings') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Volver
                    </a>
                </div>

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('admin.settings.shipping.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Opciones de Envío</h5>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="shipping_enabled" 
                                           name="shipping_enabled" 
                                           {{ old('shipping_enabled', $settings->where('key', 'shipping_enabled')->first()->value ?? '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="shipping_enabled">
                                        <strong>Habilitar Envíos</strong>
                                    </label>
                                </div>
                                <small class="form-text text-muted">Activa o desactiva el servicio de envíos en el sitio web</small>
                            </div>

                            <div class="mb-3">
                                <label for="shipping_cost" class="form-label">Costo de Envío Base (S/) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control @error('shipping_cost') is-invalid @enderror" 
                                       id="shipping_cost" name="shipping_cost" 
                                       value="{{ old('shipping_cost', $settings->where('key', 'shipping_cost')->first()->value ?? 10) }}" required>
                                @error('shipping_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Costo base que se cobrará por cada envío</small>
                            </div>

                            <div class="mb-3">
                                <label for="free_shipping_threshold" class="form-label">Umbral de Envío Gratis (S/)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('free_shipping_threshold') is-invalid @enderror" 
                                       id="free_shipping_threshold" name="free_shipping_threshold" 
                                       value="{{ old('free_shipping_threshold', $settings->where('key', 'free_shipping_threshold')->first()->value ?? 100) }}">
                                @error('free_shipping_threshold')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Monto mínimo de compra para que el envío sea gratis (dejar en 0 para desactivar)</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.settings') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

