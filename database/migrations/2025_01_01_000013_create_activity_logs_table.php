<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Crea la tabla de registro de actividades (Audit Log)
     * para rastrear todas las acciones de los administradores
     */
    public function up(): void
    {
        if (!Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                
                // Usuario que realizó la acción
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('user_name')->nullable(); // Nombre del usuario (por si se elimina)
                $table->string('user_email')->nullable(); // Email del usuario (por si se elimina)
                
                // Información de la acción
                $table->string('action'); // create, update, delete, view, etc.
                $table->string('model_type')->nullable(); // App\Models\Product, App\Models\Order, etc.
                $table->unsignedBigInteger('model_id')->nullable(); // ID del modelo afectado
                $table->string('model_name')->nullable(); // Nombre descriptivo (ej: "iPhone 15 Pro")
                
                // Detalles del cambio
                $table->text('description')->nullable(); // Descripción de la acción
                $table->json('old_values')->nullable(); // Valores anteriores (solo para updates)
                $table->json('new_values')->nullable(); // Valores nuevos
                $table->json('changes')->nullable(); // Solo los campos que cambiaron
                
                // Información adicional
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->string('route')->nullable(); // Ruta que se ejecutó
                $table->string('method')->nullable(); // GET, POST, PUT, DELETE
                
                // Categoría y tipo
                $table->string('category')->default('general'); // products, orders, users, settings, etc.
                $table->string('severity')->default('info'); // info, warning, error, critical
                
                $table->timestamps();
                
                // Índices para mejorar rendimiento en consultas
                $table->index('user_id');
                $table->index(['model_type', 'model_id']);
                $table->index('action');
                $table->index('category');
                $table->index('created_at');
                $table->index(['user_id', 'created_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

