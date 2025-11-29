<?php

/**
 * ActivityLog
 * 
 * Modelo para el sistema de registro de actividades (Audit Log).
 * Registra todas las acciones realizadas por los administradores.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    /**
     * Atributos que pueden ser asignados masivamente
     * 
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'action',
        'model_type',
        'model_id',
        'model_name',
        'description',
        'old_values',
        'new_values',
        'changes',
        'ip_address',
        'user_agent',
        'route',
        'method',
        'category',
        'severity',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changes' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que realizó la acción
     * 
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener el modelo relacionado (si existe)
     * 
     * @return Model|null
     */
    public function getModelAttribute()
    {
        if ($this->model_type && $this->model_id) {
            return $this->model_type::find($this->model_id);
        }
        return null;
    }

    /**
     * Scope para filtrar por acción
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $action
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope para filtrar por categoría
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope para filtrar por usuario
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para filtrar por modelo
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $modelType
     * @param int|null $modelId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForModel($query, string $modelType, ?int $modelId = null)
    {
        $query->where('model_type', $modelType);
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        return $query;
    }

    /**
     * Obtener el icono según la acción
     * 
     * @return string
     */
    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'create' => 'fas fa-plus-circle text-success',
            'update' => 'fas fa-edit text-primary',
            'delete' => 'fas fa-trash text-danger',
            'view' => 'fas fa-eye text-info',
            'login' => 'fas fa-sign-in-alt text-success',
            'logout' => 'fas fa-sign-out-alt text-warning',
            default => 'fas fa-circle text-secondary',
        };
    }

    /**
     * Obtener el color del badge según la severidad
     * 
     * @return string
     */
    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'critical' => 'danger',
            'error' => 'danger',
            'warning' => 'warning',
            'info' => 'info',
            default => 'secondary',
        };
    }
}

