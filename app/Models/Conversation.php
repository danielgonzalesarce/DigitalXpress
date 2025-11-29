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
     * Atributos que pueden ser asignados masivamente
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'admin_id',
        'subject',
        'last_message_at',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'last_message_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario (cliente)
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con el administrador
     * 
     * @return BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relación con los mensajes de la conversación
     * 
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Obtener el último mensaje de la conversación
     * 
     * @return Message|null
     */
    public function lastMessage()
    {
        return $this->messages()->latest()->first();
    }

    /**
     * Contar mensajes no leídos para un usuario específico
     * 
     * @param int $userId
     * @return int
     */
    public function unreadCountFor($userId)
    {
        return $this->messages()
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();
    }
}
