<?php

/**
 * Modelo Favorite
 * 
 * Representa la relación entre un usuario y un producto marcado como favorito.
 * Tabla pivot que conecta usuarios con productos favoritos.
 * 
 * Propiedades principales:
 * - user_id: ID del usuario que marcó el producto como favorito
 * - product_id: ID del producto marcado como favorito
 * 
 * Relaciones:
 * - belongsTo User: cada favorito pertenece a un usuario
 * - belongsTo Product: cada favorito está asociado a un producto
 * 
 * Nota: La combinación user_id + product_id debe ser única
 * (un usuario no puede tener el mismo producto dos veces en favoritos)
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'user_id',
        'product_id',
    ];

    /**
     * ============================================
     * RELACIONES CON OTROS MODELOS
     * ============================================
     */

    /**
     * Relación: Un favorito pertenece a un usuario
     * 
     * @return BelongsTo Relación con el modelo User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un favorito está asociado a un producto
     * 
     * @return BelongsTo Relación con el modelo Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
