<?php

/**
 * Conversation
 * 
 * Modelo para agrupar mensajes entre un usuario y un administrador.
 * Permite mantener un historial de conversaciones.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
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
        'user_id',          // ID del usuario (cliente) que inicia la conversación
        'admin_id',         // ID del administrador asignado a la conversación
        'subject',          // Asunto de la conversación
        'last_message_at',  // Fecha y hora del último mensaje (para ordenar)
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     * 
     * Laravel convierte automáticamente estos campos al tipo especificado.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'last_message_at' => 'datetime',  // Convertir a objeto Carbon DateTime
        'created_at' => 'datetime',       // Convertir a objeto Carbon DateTime
        'updated_at' => 'datetime',        // Convertir a objeto Carbon DateTime
    ];

    /**
     * Relación: Una conversación pertenece a un usuario (cliente)
     * 
     * @return BelongsTo Relación con el modelo User (cliente)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación: Una conversación pertenece a un administrador
     * 
     * @return BelongsTo Relación con el modelo User (administrador)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relación: Una conversación tiene muchos mensajes
     * 
     * @return HasMany Relación con el modelo Message
     */
    public function messages(): HasMany
    {
        // Retornar mensajes ordenados por fecha de creación (más antiguos primero)
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Obtener el último mensaje de la conversación
     * 
     * Retorna el mensaje más reciente de la conversación.
     * Útil para mostrar vista previa en listas de conversaciones.
     * 
     * @return Message|null Último mensaje o null si no hay mensajes
     */
    public function lastMessage()
    {
        return $this->messages()->latest()->first();
    }

    /**
     * Contar mensajes no leídos para un usuario específico
     * 
     * Cuenta cuántos mensajes no leídos tiene un usuario en esta conversación.
     * Útil para mostrar badges de notificaciones.
     * 
     * @param int $userId ID del usuario
     * @return int Cantidad de mensajes no leídos
     */
    public function unreadCountFor($userId)
    {
        return $this->messages()
            ->where('receiver_id', $userId)      // Solo mensajes recibidos por el usuario
            ->where('is_read', false)             // Solo los que no están leídos
            ->count();
    }
}
