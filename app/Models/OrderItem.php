<?php

/**
 * Modelo OrderItem
 * 
 * Representa un item individual dentro de un pedido (Order).
 * Cada pedido puede contener múltiples items (productos).
 * 
 * Propiedades principales:
 * - order_id: ID del pedido al que pertenece este item
 * - product_id: ID del producto incluido en el pedido
 * - quantity: Cantidad del producto en el pedido
 * - price: Precio unitario del producto al momento de la compra (precio histórico)
 * - total: Total del item (quantity × price)
 * 
 * Relaciones:
 * - belongsTo Order: cada item pertenece a un pedido
 * - belongsTo Product: cada item está asociado a un producto
 * 
 * Nota importante:
 * El precio se guarda aquí para mantener el precio histórico del producto
 * al momento de la compra, incluso si el precio del producto cambia después.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'order_id',    // ID del pedido
        'product_id',  // ID del producto
        'quantity',    // Cantidad del producto
        'price',       // Precio unitario (precio histórico)
        'total'        // Total del item (quantity × price)
    ];

    /**
     * Conversiones automáticas de tipos de datos
     */
    protected $casts = [
        'price' => 'decimal:2', // Convertir a decimal con 2 decimales
        'total' => 'decimal:2', // Convertir a decimal con 2 decimales
    ];

    /**
     * ============================================
     * RELACIONES CON OTROS MODELOS
     * ============================================
     */

    /**
     * Relación: Un item del pedido pertenece a un pedido
     * 
     * @return BelongsTo Relación con el modelo Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación: Un item del pedido está asociado a un producto
     * 
     * @return BelongsTo Relación con el modelo Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
