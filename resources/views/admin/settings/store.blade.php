@extends('layouts.admin')

@section('title', 'Editar Información de la Tienda')
@section('page-title', 'Editar Información de la Tienda')
@section('page-subtitle', 'Modificar datos de la tienda • DigitalXpress')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="orders-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Información de la Tienda</h3>
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

                <form action="{{ route('admin.settings.store.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Datos Básicos</h5>

                            <div class="mb-3">
                                <label for="store_name" class="form-label">Nombre de la Tienda <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('store_name') is-invalid @enderror" 
                                       id="store_name" name="store_name" 
                                       value="{{ old('store_name', $settings->where('key', 'store_name')->first()->value ?? 'DigitalXpress') }}" required>
                                @error('store_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="store_description" class="form-label">Descripción <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('store_description') is-invalid @enderror" 
                                          id="store_description" name="store_description" rows="3" required>{{ old('store_description', $settings->where('key', 'store_description')->first()->value ?? '') }}</textarea>
                                @error('store_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Esta descripción aparecerá en el footer del sitio web</small>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Información de Contacto</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="store_email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('store_email') is-invalid @enderror" 
                                               id="store_email" name="store_email" 
                                               value="{{ old('store_email', $settings->where('key', 'store_email')->first()->value ?? '') }}" required>
                                        @error('store_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="store_phone" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('store_phone') is-invalid @enderror" 
                                               id="store_phone" name="store_phone" 
                                               value="{{ old('store_phone', $settings->where('key', 'store_phone')->first()->value ?? '') }}" required>
                                        @error('store_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="store_address" class="form-label">Dirección</label>
                                <textarea class="form-control @error('store_address') is-invalid @enderror" 
                                          id="store_address" name="store_address" rows="2">{{ old('store_address', $settings->where('key', 'store_address')->first()->value ?? '') }}</textarea>
                                @error('store_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="store_website" class="form-label">Sitio Web</label>
                                <input type="text" class="form-control @error('store_website') is-invalid @enderror" 
                                       id="store_website" name="store_website" 
                                       value="{{ old('store_website', $settings->where('key', 'store_website')->first()->value ?? '') }}">
                                @error('store_website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

