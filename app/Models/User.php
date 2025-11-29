<?php

/**
 * Modelo User
 * 
 * Representa un usuario del sistema DigitalXpress.
 * Extiende Authenticatable para manejar autenticación de Laravel.
 * 
 * Propiedades principales:
 * - name: Nombre completo del usuario
 * - email: Email único del usuario (usado para login)
 * - password: Contraseña hasheada (requerida pero no usada si se registró con Google)
 * - role: Rol del usuario (admin, customer)
 * - google_id: ID único de Google OAuth (si se registró con Google)
 * - avatar: URL del avatar del usuario (obtenido de Google o personalizado)
 * 
 * Autenticación:
 * - Puede autenticarse con email/password tradicional
 * - Puede autenticarse con Google OAuth usando google_id
 * - Los usuarios con @digitalxpress.com son administradores automáticamente
 * 
 * Relaciones:
 * - hasMany CartItem: el usuario puede tener múltiples items en el carrito
 * - hasMany Order: el usuario puede tener múltiples pedidos
 * - hasMany Repair: el usuario puede tener múltiples solicitudes de reparación
 * - hasMany Favorite: el usuario puede tener múltiples productos favoritos
 * 
 * Métodos:
 * - isAdmin(): Verifica si el usuario es administrador (email @digitalxpress.com)
 * 
 * Seguridad:
 * - password y remember_token están ocultos en serialización
 * - password se hashea automáticamente antes de guardar
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    /**
     * Campos que pueden ser asignados masivamente (Mass Assignment)
     */
    protected $fillable = [
        'name',              // Nombre completo del usuario
        'email',             // Email único del usuario
        'password',          // Contraseña hasheada
        'role',              // Rol del usuario (admin, customer, etc.)
        'google_id',         // ID único de Google OAuth (si se registró con Google)
        'avatar',            // URL del avatar del usuario (de Google o personalizado)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ============================================
     * RELACIONES CON OTROS MODELOS
     * ============================================
     */

    /**
     * Relación: Un usuario puede tener múltiples items en el carrito
     * 
     * @return HasMany Relación con el modelo CartItem
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Relación: Un usuario puede tener múltiples pedidos
     * 
     * @return HasMany Relación con el modelo Order
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relación: Un usuario puede tener múltiples solicitudes de reparación
     * 
     * Relación uno a muchos: Un usuario puede tener muchas reparaciones.
     * 
     * @return HasMany Relación con el modelo Repair
     */
    public function repairs(): HasMany
    {
        // Retornar relación hasMany con Repair (un usuario tiene muchas reparaciones)
        return $this->hasMany(Repair::class);
    }

    /**
     * Relación: Un usuario puede tener múltiples productos favoritos
     * 
     * @return HasMany Relación con el modelo Favorite
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Relación: Mensajes enviados por el usuario
     * 
     * @return HasMany Relación con el modelo Message (como remitente)
     */
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Relación: Mensajes recibidos por el usuario
     * 
     * @return HasMany Relación con el modelo Message (como receptor)
     */
    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /**
     * Relación: Conversaciones donde el usuario es el cliente
     * 
     * @return HasMany Relación con el modelo Conversation
     */
    public function conversationsAsUser(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_id');
    }

    /**
     * Relación: Conversaciones donde el usuario es el administrador
     * 
     * @return HasMany Relación con el modelo Conversation
     */
    public function conversationsAsAdmin(): HasMany
    {
        return $this->hasMany(Conversation::class, 'admin_id');
    }

    /**
     * Verificar si el usuario es administrador
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        return str_ends_with($this->email, '@digitalxpress.com');
    }
}
