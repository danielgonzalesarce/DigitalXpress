<?php

/**
 * Modelo User
 * 
 * Representa un usuario del sistema DigitalXpress.
 * Extiende Authenticatable para manejar autenticación de Laravel.
 * 
 * Propiedades principales:
 * - name: Nombre del usuario
 * - email: Email único del usuario (usado para login)
 * - password: Contraseña hasheada
 * - role: Rol del usuario (admin, customer)
 * - google_id: ID de Google si se registró con OAuth
 * - avatar: URL del avatar del usuario
 * 
 * Relaciones:
 * - hasMany CartItem: el usuario puede tener múltiples items en el carrito
 * - hasMany Order: el usuario puede tener múltiples pedidos
 * - hasMany Repair: el usuario puede tener múltiples solicitudes de reparación
 * - hasMany Favorite: el usuario puede tener múltiples productos favoritos
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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'google_id',
        'avatar',
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
     * @return HasMany Relación con el modelo Repair
     */
    public function repairs(): HasMany
    {
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
}
