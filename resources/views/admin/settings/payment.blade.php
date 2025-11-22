@extends('layouts.admin')

@section('title', 'Editar Métodos de Pago')
@section('page-title', 'Editar Métodos de Pago')
@section('page-subtitle', 'Configurar métodos de pago disponibles • DigitalXpress')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="orders-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Métodos de Pago</h3>
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

                <form action="{{ route('admin.settings.payment.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Métodos Disponibles</h5>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="payment_credit_card" 
                                           name="payment_credit_card" 
                                           {{ old('payment_credit_card', $settings->where('key', 'payment_credit_card')->first()->value ?? '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="payment_credit_card">
                                        <strong>Tarjeta de Crédito</strong>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="payment_debit_card" 
                                           name="payment_debit_card" 
                                           {{ old('payment_debit_card', $settings->where('key', 'payment_debit_card')->first()->value ?? '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="payment_debit_card">
                                        <strong>Tarjeta de Débito</strong>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="payment_yape" 
                                           name="payment_yape" 
                                           {{ old('payment_yape', $settings->where('key', 'payment_yape')->first()->value ?? '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="payment_yape">
                                        <strong>Yape</strong>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="payment_cash" 
                                           name="payment_cash" 
                                           {{ old('payment_cash', $settings->where('key', 'payment_cash')->first()->value ?? '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="payment_cash">
                                        <strong>Efectivo</strong>
                                    </label>
                                </div>
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

