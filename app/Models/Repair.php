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

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repair extends Model
{
    // Trait para registro automático de actividades (auditoría)
    use LogsActivity;
    
    /**
     * Campos que pueden ser asignados masivamente (Mass Assignment)
     * 
     * Estos campos pueden ser llenados usando create() o update().
     */
    protected $fillable = [
        'repair_number',        // Número único de reparación (REPYYYYMM####)
        'user_id',              // ID del usuario que solicita la reparación
        'full_name',            // Nombre completo del cliente
        'email',                // Email de contacto del cliente
        'phone',                // Teléfono de contacto del cliente
        'device_type',          // Tipo de dispositivo (celular, laptop, etc.)
        'brand',                // Marca del dispositivo
        'model',                // Modelo específico del dispositivo
        'problem_description',  // Descripción detallada del problema
        'device_image',         // Ruta de la imagen del dispositivo
        'status',               // Estado: pending, in_progress, completed, cancelled
        'notes',                // Notas adicionales del técnico
        'estimated_cost',      // Costo estimado de la reparación
        'final_cost'            // Costo final de la reparación
    ];

    /**
     * Conversiones automáticas de tipos de datos
     * 
     * Los campos se convierten automáticamente al tipo especificado.
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
    /**
     * Generar número único de reparación con formato REPYYYYMM####
     * 
     * El número se reinicia cada mes. Ejemplo: REP2024110001, REP2024110002...
     * 
     * @return string Número único de reparación
     */
    public static function generateRepairNumber(): string
    {
        // Prefijo fijo para todas las reparaciones
        $prefix = 'REP';
        
        // Obtener año y mes actuales
        $year = date('Y');  // Año en 4 dígitos (ej: 2024)
        $month = date('m'); // Mes en 2 dígitos (ej: 11)
        
        // Buscar la última reparación del mes actual para obtener el siguiente número
        $lastRepair = self::where('repair_number', 'like', $prefix . $year . $month . '%')
            ->orderBy('repair_number', 'desc')
            ->first();
        
        // Si existe una reparación previa este mes, incrementar el número
        if ($lastRepair) {
            // Extraer los últimos 4 dígitos del número anterior (ej: "0001")
            $lastNumber = (int) substr($lastRepair->repair_number, -4);
            // Incrementar en 1
            $newNumber = $lastNumber + 1;
        } else {
            // Si es la primera reparación del mes, empezar en 1
            $newNumber = 1;
        }
        
        // Formatear el número con ceros a la izquierda para tener siempre 4 dígitos
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
    /**
     * Accessor: Obtener clase CSS del badge según el estado
     * 
     * Retorna la clase de Bootstrap para mostrar el badge con el color apropiado.
     * Uso: $repair->status_badge
     * 
     * @return string Clase CSS de Bootstrap (warning, info, success, danger, secondary)
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
     * Accessor: Obtener texto en español del estado
     * 
     * Convierte el estado en inglés a texto en español para mostrar en la interfaz.
     * Uso: $repair->status_text
     * 
     * @return string Texto del estado traducido al español
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pendiente',        // Estado pendiente
            'in_progress' => 'En Progreso',  // Estado en progreso
            'completed' => 'Completado',     // Estado completado
            'cancelled' => 'Cancelado',      // Estado cancelado
            default => 'Desconocido'         // Estado no reconocido
        };
    }
}
