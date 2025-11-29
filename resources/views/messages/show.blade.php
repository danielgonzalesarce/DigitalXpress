@extends('layouts.app')

@section('title', 'Conversación')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-comments me-2"></i>{{ $conversation->subject }}
                    </h1>
                    <small class="text-muted">
                        Con: <strong>{{ $conversation->admin->name ?? 'Administrador' }}</strong>
                    </small>
                </div>
                <a href="{{ route('messages.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Mensajes -->
            <div class="card shadow-sm mb-4">
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    @if($conversation->messages->count() > 0)
                    @foreach($conversation->messages as $message)
                    <div class="mb-4 {{ $message->sender_id === Auth::id() ? 'text-end' : '' }}">
                        <div class="d-inline-block {{ $message->sender_id === Auth::id() ? 'bg-primary text-white' : 'bg-light' }} rounded p-3" style="max-width: 70%;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <strong>
                                    {{ $message->sender_id === Auth::id() ? 'Tú' : $message->sender->name }}
                                </strong>
                                <small class="ms-3">
                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="message-content">
                                {!! nl2br(e($message->message)) !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <p class="text-muted text-center">No hay mensajes en esta conversación.</p>
                    @endif
                </div>
            </div>

            <!-- Formulario de respuesta -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-reply me-2"></i>Responder
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('messages.send', $conversation) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="message" class="form-label">Tu mensaje *</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" 
                                      name="message" 
                                      rows="5" 
                                      placeholder="Escribe tu respuesta aquí (mínimo 10 caracteres)..."
                                      required></textarea>
                            <div class="form-text">
                                <span id="char-count">Mínimo 10 caracteres (0/10)</span>
                            </div>
                            @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Enviar Respuesta
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
    // Scroll al final de los mensajes
    const messagesContainer = document.querySelector('.card-body[style*="overflow-y"]');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Contador de caracteres
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

