@extends('layouts.admin')

@section('title', 'Mensajería')

@section('content')
<div class="admin-page-content">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-comments me-2"></i>Mensajería
                        </h1>
                        <p class="page-subtitle text-muted">Gestiona las conversaciones con los usuarios</p>
                    </div>
                    @if($unreadCount > 0)
                    <div class="unread-badge-container">
                        <span class="badge bg-danger fs-6 px-3 py-2">
                            <i class="fas fa-envelope me-2"></i>{{ $unreadCount }} mensaje{{ $unreadCount > 1 ? 's' : '' }} no leído{{ $unreadCount > 1 ? 's' : '' }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Lista de conversaciones -->
            @if($conversations->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Conversaciones ({{ $conversations->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Asunto</th>
                                    <th>Último Mensaje</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($conversations as $conversation)
                                <tr class="{{ $conversation->unread_count > 0 ? 'table-warning' : '' }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $conversation->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $conversation->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $conversation->subject }}</strong>
                                        @if($conversation->unread_count > 0)
                                        <span class="badge bg-danger ms-2">{{ $conversation->unread_count }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $lastMessage = $conversation->messages()->latest()->first();
                                        @endphp
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
                                        @if($conversation->unread_count > 0)
                                        <span class="badge bg-warning">No leído</span>
                                        @else
                                        <span class="badge bg-success">Leído</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.messages.show', $conversation) }}" class="btn btn-sm btn-primary">
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
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay conversaciones</h4>
                    <p class="text-muted">Aún no hay usuarios que hayan iniciado conversaciones.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

