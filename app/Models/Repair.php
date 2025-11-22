<?php

/**
 * Modelo Repair
 * 
 * Representa una solicitud de reparación de un dispositivo.
 * Los usuarios pueden solicitar servicios técnicos para sus dispositivos.
 * 
 * Propiedades principales:
 * - repair_number: Número único de reparación (generado automáticamente)
 * - user_id: ID del usuario que solicita la reparación
 * - full_name: Nombre completo del cliente
 * - email: Email del cliente
 * - phone: Teléfono del cliente
 * - device_type: Tipo de dispositivo (celular, laptop, tablet, etc.)
 * - brand: Marca del dispositivo
 * - model: Modelo del dispositivo
 * - problem_description: Descripción del problema
 * - device_image: Ruta de la imagen del dispositivo con el problema
 * - status: Estado de la reparación (pending, in_progress, completed, cancelled)
 * - notes: Notas adicionales del técnico
 * - estimated_cost: Costo estimado de la reparación
 * - final_cost: Costo final de la reparación
 * 
 * Relaciones:
 * - belongsTo User: cada reparación pertenece a un usuario
 * 
 * Métodos estáticos:
 * - generateRepairNumber(): Genera un número único de reparación con formato REPYYYYMM####
 * 
 * Accessors:
 * - status_badge: Retorna la clase CSS del badge según el estado
 * - status_text: Retorna el texto en español del estado
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repair extends Model
{
    /**
     * Campos que pueden ser asignados masivamente
     */
    protected $fillable = [
        'repair_number',        // Número único de reparación
        'user_id',              // ID del usuario
        'full_name',            // Nombre completo del cliente
        'email',                // Email del cliente
        'phone',                // Teléfono del cliente
        'device_type',          // Tipo de dispositivo
        'brand',                // Marca del dispositivo
        'model',                // Modelo del dispositivo
        'problem_description',  // Descripción del problema
        'device_image',         // Imagen del dispositivo
        'status',               // Estado de la reparación
        'notes',                // Notas del técnico
        'estimated_cost',      // Costo estimado
        'final_cost'            // Costo final
    ];

    /**
     * Conversiones automáticas de tipos de datos
     */
    protected $casts = [
        'estimated_cost' => 'decimal:2', // Convertir a decimal con 2 decimales
        'final_cost' => 'decimal:2',     // Convertir a decimal con 2 decimales
    ];

    /**
     * ============================================
     * RELACIONES CON OTROS MODELOS
     * ============================================
     */

    /**
     * Relación: Una reparación pertenece a un usuario
     * 
     * @return BelongsTo Relación con el modelo User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ============================================
     * MÉTODOS ESTÁTICOS
     * ============================================
     */

    /**
     * Generar número único de reparación
     * 
     * Genera un número único con formato: REPYYYYMM####
     * Ejemplo: REP2024110001, REP2024110002, etc.
     * 
     * El número se incrementa automáticamente cada mes.
     * Si es el primer pedido del mes, empieza en 0001.
     * 
     * @return string Número único de reparación
     */
    public static function generateRepairNumber(): string
    {
        $prefix = 'REP'; // Prefijo fijo
        $year = date('Y'); // Año actual (4 dígitos)
        $month = date('m'); // Mes actual (2 dígitos)
        
        // Buscar el último número de reparación del mes actual
        $lastRepair = self::where('repair_number', 'like', $prefix . $year . $month . '%')
            ->orderBy('repair_number', 'desc')
            ->first();
        
        // Si existe un número previo, incrementar el último número
        if ($lastRepair) {
            // Extraer los últimos 4 dígitos del número anterior
            $lastNumber = (int) substr($lastRepair->repair_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            // Si no existe, empezar en 1
            $newNumber = 1;
        }
        
        // Formatear el número con ceros a la izquierda (4 dígitos)
        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * ============================================
     * ACCESSORS (Atributos Calculados)
     * ============================================
     */

    /**
     * Obtener la clase CSS del badge según el estado
     * 
     * Uso: $repair->status_badge
     * 
     * @return string Clase CSS de Bootstrap para el badge
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',      // Amarillo para pendiente
            'in_progress' => 'info',     // Azul para en progreso
            'completed' => 'success',    // Verde para completado
            'cancelled' => 'danger',     // Rojo para cancelado
            default => 'secondary'       // Gris para estado desconocido
        };
    }

    /**
     * Obtener el texto en español del estado
     * 
     * Uso: $repair->status_text
     * 
     * @return string Texto del estado traducido al español
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'in_progress' => 'En Progreso',
            'completed' => 'Completado',
            'cancelled' => 'Cancelado',
            default => 'Desconocido'
        };
    }
}
