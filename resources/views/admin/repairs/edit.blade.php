@extends('layouts.admin')

@section('title', 'Editar Reparación')
@section('page-title', 'Editar Reparación')
@section('page-subtitle', 'Modificar información de la reparación • DigitalXpress')

@section('content')
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="orders-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Editar Reparación: {{ $repair->repair_number }}</h3>
                    <a href="{{ route('admin.repairs') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Volver
                    </a>
                </div>

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('admin.repairs.update', $repair) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Información del Cliente</h5>

                            <div class="mb-3">
                                <label for="user_id" class="form-label">Cliente <span class="text-danger">*</span></label>
                                <select class="form-select @error('user_id') is-invalid @enderror" 
                                        id="user_id" name="user_id" required>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $repair->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                       id="full_name" name="full_name" value="{{ old('full_name', $repair->full_name) }}" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $repair->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone', $repair->phone) }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Información del Dispositivo</h5>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="device_type" class="form-label">Tipo de Dispositivo <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('device_type') is-invalid @enderror" 
                                               id="device_type" name="device_type" value="{{ old('device_type', $repair->device_type) }}" required>
                                        @error('device_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="brand" class="form-label">Marca <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                               id="brand" name="brand" value="{{ old('brand', $repair->brand) }}" required>
                                        @error('brand')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="model" class="form-label">Modelo <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                               id="model" name="model" value="{{ old('model', $repair->model) }}" required>
                                        @error('model')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="problem_description" class="form-label">Descripción del Problema <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('problem_description') is-invalid @enderror" 
                                          id="problem_description" name="problem_description" rows="4" required>{{ old('problem_description', $repair->problem_description) }}</textarea>
                                @error('problem_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if($repair->device_image)
                            <div class="mb-3">
                                <label class="form-label">Imagen Actual</label>
                                <div>
                                    <img src="{{ Storage::url($repair->device_image) }}" alt="Imagen del dispositivo" 
                                         class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                            @endif

                            <div class="mb-3">
                                <label for="device_image" class="form-label">Nueva Imagen del Dispositivo</label>
                                <input type="file" class="form-control @error('device_image') is-invalid @enderror" 
                                       id="device_image" name="device_image" accept="image/*">
                                @error('device_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Dejar en blanco para mantener la imagen actual</small>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Estado y Costos</h5>

                            <div class="mb-3">
                                <label for="status" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="pending" {{ old('status', $repair->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="in_progress" {{ old('status', $repair->status) == 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                                    <option value="completed" {{ old('status', $repair->status) == 'completed' ? 'selected' : '' }}>Completado</option>
                                    <option value="cancelled" {{ old('status', $repair->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="estimated_cost" class="form-label">Costo Estimado</label>
                                        <input type="number" class="form-control @error('estimated_cost') is-invalid @enderror" 
                                               id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost', $repair->estimated_cost) }}" 
                                               step="0.01" min="0">
                                        @error('estimated_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="final_cost" class="form-label">Costo Final</label>
                                        <input type="number" class="form-control @error('final_cost') is-invalid @enderror" 
                                               id="final_cost" name="final_cost" value="{{ old('final_cost', $repair->final_cost) }}" 
                                               step="0.01" min="0">
                                        @error('final_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notas</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3">{{ old('notes', $repair->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.repairs') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Actualizar Reparación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

