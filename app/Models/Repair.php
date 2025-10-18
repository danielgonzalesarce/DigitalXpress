<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repair extends Model
{
    protected $fillable = [
        'repair_number',
        'user_id',
        'full_name',
        'email',
        'phone',
        'device_type',
        'brand',
        'model',
        'problem_description',
        'device_image',
        'status',
        'notes',
        'estimated_cost',
        'final_cost'
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateRepairNumber(): string
    {
        $prefix = 'REP';
        $year = date('Y');
        $month = date('m');
        
        $lastRepair = self::where('repair_number', 'like', $prefix . $year . $month . '%')
            ->orderBy('repair_number', 'desc')
            ->first();
        
        if ($lastRepair) {
            $lastNumber = (int) substr($lastRepair->repair_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $year . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

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
