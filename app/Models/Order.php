<?php

/**
 * Modelo Order
 * 
 * Representa un pedido/orden de compra en DigitalXpress.
 * 
 * Propiedades principales:
 * - order_number: Número único de pedido (generado automáticamente)
 * - user_id: ID del usuario que realizó el pedido (null para invitados)
 * - status: Estado del pedido (pending, processing, completed, cancelled)
 * - subtotal: Subtotal del pedido (sin impuestos ni envío)
 * - tax_amount: Monto de impuestos
 * - shipping_amount: Costo de envío
 * - total_amount: Total del pedido (subtotal + impuestos + envío)
 * - payment_status: Estado del pago (pending, paid, failed)
 * - payment_method: Método de pago usado (credit_card, debit_card, paypal, yape, cash)
 * - billing_address: Dirección de facturación (array JSON)
 * - shipping_address: Dirección de envío (array JSON)
 * - notes: Notas adicionales del cliente
 * - customer_name: Nombre del cliente (puede diferir del usuario si es invitado)
 * - customer_email: Email del cliente
 * - customer_phone: Teléfono del cliente
 * - transaction_id: ID de transacción del procesador de pagos
 * - session_id: ID de sesión para pedidos de invitados
 * 
 * Relaciones:
 * - belongsTo User: el pedido puede pertenecer a un usuario (opcional)
 * - hasMany OrderItem: un pedido contiene múltiples items
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use LogsActivity;
    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'order_number',      // Número único del pedido
        'user_id',           // ID del usuario (null para invitados)
        'status',            // Estado del pedido
        'subtotal',          // Subtotal sin impuestos ni envío
        'tax_amount',        // Monto de impuestos
        'shipping_amount',   // Costo de envío
        'total_amount',      // Total del pedido
        'payment_status',    // Estado del pago
        'payment_method',    // Método de pago usado
        'billing_address',   // Dirección de facturación (JSON)
        'shipping_address',  // Dirección de envío (JSON)
        'notes',             // Notas adicionales
        'customer_name',      // Nombre del cliente
        'customer_email',    // Email del cliente
        'customer_phone',    // Teléfono del cliente
        'transaction_id',    // ID de transacción del procesador
        'session_id'         // ID de sesión para invitados
    ];

    /**
     * Conversiones automáticas de tipos de datos
     */
    protected $casts = [
        'billing_address' => 'array',   // Convertir JSON a array
        'shipping_address' => 'array',   // Convertir JSON a array
        'subtotal' => 'decimal:2',       // Convertir a decimal con 2 decimales
        'tax_amount' => 'decimal:2',    // Convertir a decimal con 2 decimales
        'shipping_amount' => 'decimal:2', // Convertir a decimal con 2 decimales
        'total_amount' => 'decimal:2',   // Convertir a decimal con 2 decimales
    ];

    /**
     * ============================================
     * RELACIONES CON OTROS MODELOS
     * ============================================
     */

    /**
     * Relación: Un pedido puede pertenecer a un usuario
     * (null para pedidos de invitados)
     * 
     * @return BelongsTo Relación con el modelo User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un pedido contiene múltiples items
     * 
     * @return HasMany Relación con el modelo OrderItem
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
