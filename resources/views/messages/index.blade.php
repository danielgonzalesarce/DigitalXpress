@extends('layouts.app')

@section('title', 'Bandeja de Entrada')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-inbox me-2"></i>Bandeja de Entrada
                </h1>
                <a href="{{ route('messages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nuevo Mensaje
                </a>
            </div>

            <!-- Contador de mensajes no leídos -->
            @if($unreadCount > 0)
            <div class="alert alert-info mb-4">
                <i class="fas fa-envelope me-2"></i>
                Tienes <strong>{{ $unreadCount }}</strong> mensaje{{ $unreadCount > 1 ? 's' : '' }} no leído{{ $unreadCount > 1 ? 's' : '' }}
            </div>
            @endif

            <!-- Lista de conversaciones -->
            @if($conversations->count() > 0)
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Asunto</th>
                                    <th>Administrador</th>
                                    <th>Último Mensaje</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($conversations as $conversation)
                                @php
                                    $unreadInConversation = $conversation->messages()
                                        ->where('receiver_id', Auth::id())
                                        ->where('is_read', false)
                                        ->count();
                                    $lastMessage = $conversation->messages()->latest()->first();
                                @endphp
                                <tr class="{{ $unreadInConversation > 0 ? 'table-warning' : '' }}">
                                    <td>
                                        <strong>{{ $conversation->subject }}</strong>
                                        @if($unreadInConversation > 0)
                                        <span class="badge bg-danger ms-2">{{ $unreadInConversation }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($conversation->admin)
                                        <i class="fas fa-user-shield me-1"></i>{{ $conversation->admin->name }}
                                        @else
                                        <span class="text-muted">Sin asignar</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($lastMessage)
                                        <small class="text-muted">
                                            {{ $lastMessage->created_at->diffForHumans() }}
                                        </small>
                                        <br>
                                        <small class="text-truncate d-inline-block" style="max-width: 200px;">
                                            {{ Str::limit($lastMessage->message, 50) }}
                                        </small>
                                        @else
                                        <span class="text-muted">Sin mensajes</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($unreadInConversation > 0)
                                        <span class="badge bg-warning">No leído</span>
                                        @else
                                        <span class="badge bg-success">Leído</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('messages.show', $conversation) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye me-1"></i>Ver
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <!-- Estado vacío -->
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No tienes mensajes</h4>
                    <p class="text-muted mb-4">Aún no has iniciado ninguna conversación con nuestros administradores.</p>
                    <a href="{{ route('messages.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Enviar tu primer mensaje
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

