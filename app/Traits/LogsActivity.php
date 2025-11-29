<?php

/**
 * LogsActivity Trait
 * 
 * Trait para registrar automáticamente las actividades de los modelos.
 * Se puede usar en cualquier modelo que necesite ser auditado.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Boot del trait - registra eventos del modelo automáticamente
     * 
     * Este método se ejecuta cuando el trait se carga en un modelo.
     * Registra listeners para los eventos create, update y delete.
     */
    protected static function bootLogsActivity()
    {
        // Registrar listener para cuando se crea un nuevo registro
        static::created(function ($model) {
            static::logActivity($model, 'create');
        });

        // Registrar listener para cuando se actualiza un registro existente
        // Pasa valores originales y nuevos para comparar cambios
        static::updated(function ($model) {
            static::logActivity($model, 'update', $model->getOriginal(), $model->getChanges());
        });

        // Registrar listener para cuando se elimina un registro
        static::deleted(function ($model) {
            static::logActivity($model, 'delete');
        });
    }

    /**
     * Registrar una actividad
     * 
     * @param Model $model
     * @param string $action
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return void
     */
    /**
     * Registrar una actividad en el log de auditoría
     * 
     * Crea un registro en la tabla activity_logs con toda la información
     * de la acción realizada sobre el modelo.
     * 
     * @param Model $model Modelo sobre el que se realizó la acción
     * @param string $action Acción realizada (create, update, delete)
     * @param array|null $oldValues Valores anteriores (solo para update)
     * @param array|null $newValues Valores nuevos (solo para update)
     * @return void
     */
    protected static function logActivity($model, string $action, ?array $oldValues = null, ?array $newValues = null)
    {
        // Obtener usuario autenticado (puede ser null si es acción del sistema)
        $user = Auth::user();
        
        // Lista de campos sensibles que no deben registrarse en el log
        $hiddenFields = ['password', 'remember_token', 'api_token'];
        
        // Filtrar campos sensibles de los valores antiguos
        if ($oldValues) {
            $oldValues = array_diff_key($oldValues, array_flip($hiddenFields));
        }
        
        // Filtrar campos sensibles de los valores nuevos
        if ($newValues) {
            $newValues = array_diff_key($newValues, array_flip($hiddenFields));
        }

        // Calcular solo los campos que realmente cambiaron (solo para update)
        $changes = null;
        if ($oldValues && $newValues) {
            $changes = [];
            // Comparar cada campo nuevo con el antiguo
            foreach ($newValues as $key => $value) {
                // Si el campo no existía antes o cambió su valor
                if (!isset($oldValues[$key]) || $oldValues[$key] != $value) {
                    $changes[$key] = [
                        'old' => $oldValues[$key] ?? null, // Valor anterior
                        'new' => $value,                    // Valor nuevo
                    ];
                }
            }
        }

        // Obtener nombre descriptivo del modelo (ej: "REP2024110001" para Repair)
        $modelName = static::getModelName($model);

        // Determinar la categoría del modelo (ej: "repairs" para Repair)
        $category = static::getCategory($model);

        // Crear registro en la tabla activity_logs
        ActivityLog::create([
            'user_id' => $user?->id,                    // ID del usuario que realizó la acción
            'user_name' => $user?->name,               // Nombre del usuario
            'user_email' => $user?->email,             // Email del usuario
            'action' => $action,                        // Acción: create, update, delete
            'model_type' => get_class($model),         // Clase completa del modelo (ej: App\Models\Repair)
            'model_id' => $model->id ?? null,          // ID del registro afectado
            'model_name' => $modelName,                // Nombre descriptivo (ej: repair_number)
            'description' => static::getDescription($model, $action), // Descripción legible
            'old_values' => $oldValues,                 // Valores anteriores (JSON)
            'new_values' => $newValues,                 // Valores nuevos (JSON)
            'changes' => $changes,                      // Solo campos que cambiaron (JSON)
            'ip_address' => Request::ip(),             // IP del cliente
            'user_agent' => Request::userAgent(),      // Navegador del cliente
            'route' => Request::path(),                // Ruta accedida
            'method' => Request::method(),             // Método HTTP (GET, POST, etc.)
            'category' => $category,                   // Categoría: repairs, products, etc.
            'severity' => static::getSeverity($action), // Severidad: info, warning, error
        ]);
    }

    /**
     * Obtener nombre descriptivo del modelo para el log
     * 
     * Intenta obtener un nombre descriptivo del modelo basado en campos comunes.
     * Para Repair, usa repair_number (ej: "REP2024110001").
     * 
     * @param Model $model Modelo a obtener nombre
     * @return string Nombre descriptivo del modelo
     */
    protected static function getModelName($model): string
    {
        // Intentar obtener nombre descriptivo según campos comunes del modelo
        if (isset($model->name)) {
            return $model->name; // Para Product, Category, User
        }
        if (isset($model->title)) {
            return $model->title; // Para modelos con campo title
        }
        if (isset($model->email)) {
            return $model->email; // Para User (como alternativa)
        }
        if (isset($model->order_number)) {
            return $model->order_number; // Para Order
        }
        if (isset($model->repair_number)) {
            return $model->repair_number; // Para Repair (ej: "REP2024110001")
        }
        
        // Por defecto, usar nombre de clase e ID
        return class_basename(get_class($model)) . ' #' . ($model->id ?? 'N/A');
    }

    /**
     * Obtener la categoría del modelo para organizar los logs
     * 
     * Retorna una categoría basada en el tipo de modelo para facilitar
     * el filtrado y organización de los logs de actividad.
     * Para Repair, retorna 'repairs'.
     * 
     * @param Model $model Modelo a categorizar
     * @return string Categoría del modelo
     */
    protected static function getCategory($model): string
    {
        // Obtener nombre de la clase sin namespace
        $className = class_basename(get_class($model));
        
        // Retornar categoría según el tipo de modelo
        return match($className) {
            'Product' => 'products',        // Categoría: productos
            'Category' => 'categories',     // Categoría: categorías
            'Order' => 'orders',           // Categoría: pedidos
            'OrderItem' => 'orders',       // Categoría: pedidos (mismo que Order)
            'User' => 'users',             // Categoría: usuarios
            'Repair' => 'repairs',         // Categoría: reparaciones
            'Setting' => 'settings',       // Categoría: configuraciones
            'CartItem' => 'cart',          // Categoría: carrito
            'Favorite' => 'favorites',     // Categoría: favoritos
            'Message' => 'messages',      // Categoría: mensajes
            'Conversation' => 'messages',  // Categoría: mensajes (mismo que Message)
            default => 'general',          // Categoría general por defecto
        };
    }

    /**
     * Obtener descripción legible de la acción realizada
     * 
     * Genera una descripción en español de la acción realizada sobre el modelo.
     * Ejemplo para Repair: "Creó Repair: REP2024110001"
     * 
     * @param Model $model Modelo afectado
     * @param string $action Acción realizada (create, update, delete)
     * @return string Descripción legible de la acción
     */
    protected static function getDescription($model, string $action): string
    {
        // Obtener nombre descriptivo del modelo
        $modelName = static::getModelName($model);
        // Obtener nombre de la clase sin namespace
        $className = class_basename(get_class($model));
        
        // Generar descripción según la acción
        return match($action) {
            'create' => "Creó {$className}: {$modelName}",      // Ej: "Creó Repair: REP2024110001"
            'update' => "Actualizó {$className}: {$modelName}",   // Ej: "Actualizó Repair: REP2024110001"
            'delete' => "Eliminó {$className}: {$modelName}",    // Ej: "Eliminó Repair: REP2024110001"
            default => "Acción {$action} en {$className}: {$modelName}", // Acción personalizada
        };
    }

    /**
     * Obtener severidad del log según la acción realizada
     * 
     * Define el nivel de severidad para filtrar y destacar logs importantes.
     * 
     * @param string $action Acción realizada
     * @return string Severidad: info, warning, error
     */
    protected static function getSeverity(string $action): string
    {
        return match($action) {
            'delete' => 'warning',  // Eliminar es más crítico (warning)
            'update' => 'info',     // Actualizar es informativo
            'create' => 'info',      // Crear es informativo
            default => 'info',      // Por defecto es informativo
        };
    }

    /**
     * Registrar una actividad manualmente (método de instancia)
     * 
     * Permite registrar actividades personalizadas que no son create/update/delete.
     * Útil para acciones específicas como "enviar correo", "generar PDF", etc.
     * 
     * @param string $action Acción personalizada a registrar
     * @param string|null $description Descripción personalizada (opcional)
     * @param array|null $data Datos adicionales a guardar en new_values
     * @return ActivityLog Instancia del log creado
     */
    public function logManualActivity(string $action, ?string $description = null, ?array $data = null): ActivityLog
    {
        // Obtener usuario autenticado
        $user = Auth::user();
        
        // Crear registro de actividad manual
        return ActivityLog::create([
            'user_id' => $user?->id,                           // ID del usuario
            'user_name' => $user?->name,                        // Nombre del usuario
            'user_email' => $user?->email,                      // Email del usuario
            'action' => $action,                                 // Acción personalizada
            'model_type' => get_class($this),                   // Clase del modelo
            'model_id' => $this->id ?? null,                   // ID del modelo
            'model_name' => static::getModelName($this),        // Nombre descriptivo
            'description' => $description ?? static::getDescription($this, $action), // Descripción
            'old_values' => null,                               // Sin valores antiguos
            'new_values' => $data,                              // Datos adicionales
            'changes' => null,                                  // Sin cambios específicos
            'ip_address' => Request::ip(),                     // IP del cliente
            'user_agent' => Request::userAgent(),             // Navegador del cliente
            'route' => Request::path(),                         // Ruta accedida
            'method' => Request::method(),                      // Método HTTP
            'category' => static::getCategory($this),          // Categoría del modelo
            'severity' => static::getSeverity($action),         // Severidad de la acción
        ]);
    }
}

