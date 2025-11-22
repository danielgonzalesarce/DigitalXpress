<?php

/**
 * Modelo Category
 * 
 * Representa una categoría de productos en DigitalXpress.
 * Las categorías organizan los productos del catálogo.
 * 
 * Propiedades principales:
 * - name: Nombre de la categoría
 * - slug: URL amigable generada automáticamente del nombre
 * - description: Descripción de la categoría
 * - image: Imagen representativa de la categoría
 * - is_active: Indica si la categoría está activa y visible
 * - sort_order: Orden de visualización (menor número = aparece primero)
 * 
 * Relaciones:
 * - hasMany Product: una categoría puede tener múltiples productos
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order'
    ];

    /**
     * Conversiones automáticas de tipos de datos
     */
    protected $casts = [
        'is_active' => 'boolean', // Convertir a booleano
    ];

    /**
     * Relación: Una categoría puede tener múltiples productos
     * 
     * @return HasMany Relación con el modelo Product
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
