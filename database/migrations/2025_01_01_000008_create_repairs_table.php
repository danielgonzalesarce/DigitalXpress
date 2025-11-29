<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Crear la tabla repairs en la base de datos
     * 
     * Esta migración crea la tabla que almacena todas las solicitudes de reparación.
     */
    public function up(): void
    {
        // Verificar que la tabla no exista antes de crearla
        if (!Schema::hasTable('repairs')) {
            Schema::create('repairs', function (Blueprint $table) {
                // ID principal autoincremental
                $table->id();
                
                // Número único de reparación (formato: REPYYYYMM####)
                $table->string('repair_number')->unique();
                
                // Relación con tabla users (eliminación en cascada)
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                
                // Información del cliente
                $table->string('full_name');        // Nombre completo del cliente
                $table->string('email');            // Email de contacto
                $table->string('phone');            // Teléfono de contacto
                
                // Información del dispositivo
                $table->string('device_type');      // Tipo: celular, laptop, tablet, etc.
                $table->string('brand');            // Marca del dispositivo
                $table->string('model');            // Modelo específico
                $table->text('problem_description'); // Descripción detallada del problema
                $table->string('device_image')->nullable(); // Ruta de imagen del dispositivo (opcional)
                
                // Estado y seguimiento
                $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
                $table->text('notes')->nullable();  // Notas adicionales del técnico
                
                // Costos
                $table->decimal('estimated_cost', 10, 2)->nullable(); // Costo estimado
                $table->decimal('final_cost', 10, 2)->nullable();      // Costo final
                
                // Timestamps automáticos (created_at, updated_at)
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};

