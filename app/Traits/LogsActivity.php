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
     * Boot del trait - registra eventos del modelo
     */
    protected static function bootLogsActivity()
    {
        // Registrar cuando se crea un modelo
        static::created(function ($model) {
            static::logActivity($model, 'create');
        });

        // Registrar cuando se actualiza un modelo
        static::updated(function ($model) {
            static::logActivity($model, 'update', $model->getOriginal(), $model->getChanges());
        });

        // Registrar cuando se elimina un modelo
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
    protected static function logActivity($model, string $action, ?array $oldValues = null, ?array $newValues = null)
    {
        $user = Auth::user();
        
        // Determinar qué campos registrar (excluir campos sensibles)
        $hiddenFields = ['password', 'remember_token', 'api_token'];
        
        if ($oldValues) {
            $oldValues = array_diff_key($oldValues, array_flip($hiddenFields));
        }
        
        if ($newValues) {
            $newValues = array_diff_key($newValues, array_flip($hiddenFields));
        }

        // Calcular solo los cambios
        $changes = null;
        if ($oldValues && $newValues) {
            $changes = [];
            foreach ($newValues as $key => $value) {
                if (!isset($oldValues[$key]) || $oldValues[$key] != $value) {
                    $changes[$key] = [
                        'old' => $oldValues[$key] ?? null,
                        'new' => $value,
                    ];
                }
            }
        }

        // Obtener nombre descriptivo del modelo
        $modelName = static::getModelName($model);

        // Determinar la categoría
        $category = static::getCategory($model);

        ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id ?? null,
            'model_name' => $modelName,
            'description' => static::getDescription($model, $action),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'changes' => $changes,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'route' => Request::path(),
            'method' => Request::method(),
            'category' => $category,
            'severity' => static::getSeverity($action),
        ]);
    }

    /**
     * Obtener nombre descriptivo del modelo
     * 
     * @param Model $model
     * @return string
     */
    protected static function getModelName($model): string
    {
        // Intentar obtener un nombre descriptivo
        if (isset($model->name)) {
            return $model->name;
        }
        if (isset($model->title)) {
            return $model->title;
        }
        if (isset($model->email)) {
            return $model->email;
        }
        if (isset($model->order_number)) {
            return $model->order_number;
        }
        if (isset($model->repair_number)) {
            return $model->repair_number;
        }
        
        return class_basename(get_class($model)) . ' #' . ($model->id ?? 'N/A');
    }

    /**
     * Obtener la categoría del modelo
     * 
     * @param Model $model
     * @return string
     */
    protected static function getCategory($model): string
    {
        $className = class_basename(get_class($model));
        
        return match($className) {
            'Product' => 'products',
            'Category' => 'categories',
            'Order' => 'orders',
            'OrderItem' => 'orders',
            'User' => 'users',
            'Repair' => 'repairs',
            'Setting' => 'settings',
            'CartItem' => 'cart',
            'Favorite' => 'favorites',
            'Message' => 'messages',
            'Conversation' => 'messages',
            default => 'general',
        };
    }

    /**
     * Obtener descripción de la acción
     * 
     * @param Model $model
     * @param string $action
     * @return string
     */
    protected static function getDescription($model, string $action): string
    {
        $modelName = static::getModelName($model);
        $className = class_basename(get_class($model));
        
        return match($action) {
            'create' => "Creó {$className}: {$modelName}",
            'update' => "Actualizó {$className}: {$modelName}",
            'delete' => "Eliminó {$className}: {$modelName}",
            default => "Acción {$action} en {$className}: {$modelName}",
        };
    }

    /**
     * Obtener severidad según la acción
     * 
     * @param string $action
     * @return string
     */
    protected static function getSeverity(string $action): string
    {
        return match($action) {
            'delete' => 'warning',
            'update' => 'info',
            'create' => 'info',
            default => 'info',
        };
    }

    /**
     * Registrar una actividad manualmente (método de instancia)
     * 
     * @param string $action
     * @param string|null $description
     * @param array|null $data
     * @return ActivityLog
     */
    public function logManualActivity(string $action, ?string $description = null, ?array $data = null): ActivityLog
    {
        $user = Auth::user();
        
        return ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'action' => $action,
            'model_type' => get_class($this),
            'model_id' => $this->id ?? null,
            'model_name' => static::getModelName($this),
            'description' => $description ?? static::getDescription($this, $action),
            'old_values' => null,
            'new_values' => $data,
            'changes' => null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'route' => Request::path(),
            'method' => Request::method(),
            'category' => static::getCategory($this),
            'severity' => static::getSeverity($action),
        ]);
    }
}

