<?php

/**
 * Message
 * 
 * Modelo para el sistema de mensajería entre usuarios y administradores.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    /**
     * Atributos que pueden ser asignados masivamente (Mass Assignment)
     * 
     * Estos campos pueden ser llenados usando create() o update().
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',        // ID del usuario que envía el mensaje
        'receiver_id',      // ID del usuario que recibe el mensaje
        'message',          // Contenido del mensaje
        'subject',          // Asunto del mensaje
        'is_read',          // Estado de lectura (true/false)
        'read_at',          // Fecha y hora de lectura
        'type',             // Tipo: 'user_to_admin' o 'admin_to_user'
        'conversation_id',  // ID de la conversación a la que pertenece
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     * 
     * Laravel convierte automáticamente estos campos al tipo especificado.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',      // Convertir a booleano
        'read_at' => 'datetime',     // Convertir a objeto Carbon DateTime
        'created_at' => 'datetime',  // Convertir a objeto Carbon DateTime
        'updated_at' => 'datetime',  // Convertir a objeto Carbon DateTime
    ];

    /**
     * Relación: Un mensaje pertenece a un usuario que lo envía
     * 
     * @return BelongsTo Relación con el modelo User (remitente)
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relación: Un mensaje pertenece a un usuario que lo recibe
     * 
     * @return BelongsTo Relación con el modelo User (destinatario)
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Relación: Un mensaje pertenece a una conversación
     * 
     * @return BelongsTo Relación con el modelo Conversation
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Marcar el mensaje como leído
     * 
     * Actualiza el estado del mensaje a leído y guarda la fecha de lectura.
     * Solo marca como leído si aún no está leído.
     * 
     * @return void
     */
    public function markAsRead(): void
    {
        // Solo marcar como leído si aún no está leído
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,   // Marcar como leído
                'read_at' => now(),  // Guardar fecha y hora de lectura
            ]);
        }
    }

    /**
     * Scope: Obtener solo mensajes no leídos
     * 
     * Permite filtrar mensajes que aún no han sido leídos.
     * Uso: Message::unread()->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query Query builder
     * @return \Illuminate\Database\Eloquent\Builder Query filtrado
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: Obtener mensajes relacionados con un usuario específico
     * 
     * Retorna mensajes donde el usuario es remitente o destinatario.
     * Útil para obtener todos los mensajes de un usuario.
     * Uso: Message::forUser($userId)->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query Query builder
     * @param int $userId ID del usuario
     * @return \Illuminate\Database\Eloquent\Builder Query filtrado
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('sender_id', $userId)      // Mensajes enviados por el usuario
              ->orWhere('receiver_id', $userId); // Mensajes recibidos por el usuario
        });
    }
}
