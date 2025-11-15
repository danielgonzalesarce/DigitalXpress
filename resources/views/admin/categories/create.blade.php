@extends('layouts.admin')

@section('title', 'Crear Categoría')
@section('page-title', 'Crear Categoría')
@section('page-subtitle', 'Agregar una nueva categoría de productos • DigitalXpress')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="orders-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Nueva Categoría</h3>
                    <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Volver
                    </a>
                </div>

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Información de la Categoría</h5>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre de la Categoría <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    El slug se generará automáticamente a partir del nombre.
                                </small>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Descripción</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">URL de la Imagen</label>
                                <input type="text" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" value="{{ old('image') }}" 
                                       placeholder="https://ejemplo.com/imagen.jpg">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    URL de la imagen representativa de la categoría (opcional).
                                </small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sort_order" class="form-label">Orden de Visualización</label>
                                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                               id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" 
                                               min="0" step="1">
                                        @error('sort_order')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            Número menor = aparece primero en la lista.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="is_active" name="is_active" 
                                                   {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Categoría Activa
                                            </label>
                                        </div>
                                        <small class="form-text text-muted d-block">
                                            Las categorías inactivas no se mostrarán en el sitio.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Crear Categoría
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

