@extends('layouts.app')

@section('title', 'Nuevo Mensaje')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-paper-plane me-2"></i>Nuevo Mensaje
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('messages.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="subject" class="form-label">Asunto *</label>
                            <input type="text" 
                                   class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" 
                                   name="subject" 
                                   value="{{ old('subject') }}" 
                                   placeholder="Ej: Consulta sobre mi pedido"
                                   required>
                            @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Mensaje *</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" 
                                      name="message" 
                                      rows="8" 
                                      placeholder="Escribe tu mensaje aquí (mínimo 10 caracteres)..."
                                      required>{{ old('message') }}</textarea>
                            <div class="form-text">
                                <span id="char-count">Mínimo 10 caracteres (0/10)</span>
                            </div>
                            @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('messages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Enviar Mensaje
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('char-count');
    const minLength = 10;
    
    if (messageTextarea && charCount) {
        messageTextarea.addEventListener('input', function() {
            const length = this.value.length;
            const remaining = minLength - length;
            
            if (remaining > 0) {
                charCount.textContent = `Mínimo 10 caracteres (${length}/${minLength})`;
                charCount.className = 'form-text text-warning';
            } else {
                charCount.textContent = `${length} caracteres`;
                charCount.className = 'form-text text-success';
            }
        });
    }
});
</script>
@endpush
@endsection

