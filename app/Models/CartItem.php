<?php

/**
 * Modelo CartItem
 * 
 * Representa un item individual en el carrito de compras.
 * Puede pertenecer a un usuario autenticado o a una sesión de invitado.
 * 
 * Propiedades principales:
 * - user_id: ID del usuario (null si es invitado)
 * - product_id: ID del producto agregado al carrito
 * - quantity: Cantidad del producto en el carrito
 * - price: Precio del producto al momento de agregarlo (se guarda para mantener precio histórico)
 * - session_id: ID de sesión para usuarios invitados (null si es usuario autenticado)
 * 
 * Relaciones:
 * - belongsTo User: puede pertenecer a un usuario (opcional, null para invitados)
 * - belongsTo Product: siempre está asociado a un producto
 * 
 * Accessors:
 * - total: calcula el total del item (quantity * price)
 * 
 * Nota: Para usuarios invitados, se usa session_id en lugar de user_id.
 * Cuando un invitado se registra, los items del carrito se pueden migrar usando session_id.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'user_id',        // ID del usuario (null para invitados)
        'product_id',     // ID del producto
        'quantity',       // Cantidad en el carrito
        'price',          // Precio al momento de agregar (mantiene precio histórico)
        'session_id'      // ID de sesión para invitados
    ];

    /**
     * Conversiones automáticas de tipos de datos
     */
    protected $casts = [
        'price' => 'decimal:2', // Convertir a decimal con 2 decimales
    ];

    /**
     * ============================================
     * RELACIONES CON OTROS MODELOS
     * ============================================
     */

    /**
     * Relación: Un item del carrito puede pertenecer a un usuario
     * (null para usuarios invitados que usan session_id)
     * 
     * @return BelongsTo Relación con el modelo User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un item del carrito siempre está asociado a un producto
     * 
     * @return BelongsTo Relación con el modelo Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * ============================================
     * ACCESSORS (Atributos Calculados)
     * ============================================
     */

    /**
     * Calcular el total del item del carrito
     * Multiplica la cantidad por el precio unitario
     * 
     * Uso: $cartItem->total
     * 
     * @return float Total del item (quantity * price)
     */
    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }
}
