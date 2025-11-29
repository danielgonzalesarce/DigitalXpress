<?php

/**
 * Modelo Setting
 * 
 * Representa una configuración del sistema DigitalXpress.
 * Sistema flexible de configuración usando clave-valor.
 * 
 * Propiedades principales:
 * - key: Clave única de la configuración (ej: 'store_name', 'shipping_cost')
 * - value: Valor de la configuración (puede ser string, JSON, etc.)
 * - type: Tipo de dato (string, number, boolean, json)
 * - group: Grupo al que pertenece (store, shipping, payment)
 * - label: Etiqueta descriptiva para mostrar en formularios
 * - description: Descripción detallada de la configuración
 * 
 * Métodos estáticos útiles:
 * - get($key, $default): Obtener valor de una configuración
 * - set($key, $value): Establecer o actualizar una configuración
 * - getGroup($group): Obtener todas las configuraciones de un grupo
 * 
 * Ejemplos de uso:
 * - Setting::get('store_name', 'DigitalXpress')
 * - Setting::set('shipping_cost', 10.00)
 * - Setting::getGroup('store') // Retorna array con todas las configuraciones del grupo 'store'
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use LogsActivity;
    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'key',          // Clave única de la configuración
        'value',        // Valor de la configuración
        'type',         // Tipo de dato (string, number, boolean, json)
        'group',        // Grupo de configuración (store, shipping, payment)
        'label',        // Etiqueta para mostrar en formularios
        'description',  // Descripción de la configuración
    ];

    /**
     * ============================================
     * MÉTODOS ESTÁTICOS ÚTILES
     * ============================================
     */

    /**
     * Obtener el valor de una configuración por su clave
     * 
     * Busca la configuración por su clave y retorna su valor.
     * Si no existe, retorna el valor por defecto proporcionado.
     * 
     * @param string $key Clave de la configuración
     * @param mixed $default Valor por defecto si no existe
     * @return mixed Valor de la configuración o valor por defecto
     * 
     * @example Setting::get('store_name', 'DigitalXpress')
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Establecer el valor de una configuración
     * 
     * Si la configuración existe, la actualiza.
     * Si no existe, la crea.
     * 
     * @param string $key Clave de la configuración
     * @param mixed $value Valor a establecer
     * @return Setting Modelo de la configuración creada/actualizada
     * 
     * @example Setting::set('shipping_cost', 10.00)
     */
    public static function set($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],      // Buscar por clave
            ['value' => $value]   // Actualizar o crear con este valor
        );
    }

    /**
     * Obtener todas las configuraciones de un grupo
     * 
     * Retorna un array asociativo con clave => valor
     * de todas las configuraciones del grupo especificado.
     * 
     * @param string $group Nombre del grupo
     * @return array Array asociativo con las configuraciones del grupo
     * 
     * @example Setting::getGroup('store') // Retorna ['store_name' => '...', 'store_email' => '...']
     */
    public static function getGroup($group)
    {
        return self::where('group', $group)->get()->pluck('value', 'key')->toArray();
    }
}
