<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Crea la tabla de mensajes para el sistema de mensajería
     * entre usuarios y administradores.
     */
    public function up(): void
    {
        // Eliminar tablas si existen (para evitar errores)
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
        
        // Crear tabla de conversaciones PRIMERO (para que messages pueda referenciarla)
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('subject')->nullable();
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'admin_id']);
        });
        
        // Crear tabla de mensajes DESPUÉS (para poder referenciar conversations)
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // Relaciones: usuario que envía y usuario que recibe
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            
            // Contenido del mensaje
            $table->text('message');
            $table->string('subject')->nullable(); // Asunto opcional
            
            // Estado del mensaje
            $table->boolean('is_read')->default(false); // Si el mensaje ha sido leído
            $table->timestamp('read_at')->nullable(); // Cuándo fue leído
            
            // Tipo de mensaje (para identificar si es de usuario o admin)
            $table->enum('type', ['user_to_admin', 'admin_to_user'])->default('user_to_admin');
            
            // Relación con conversación (thread) - permite agrupar mensajes
            $table->foreignId('conversation_id')->nullable()->constrained('conversations')->onDelete('cascade');
            
            $table->timestamps();
            
            // Índices para mejorar rendimiento en consultas
            $table->index(['sender_id', 'receiver_id']);
            $table->index(['receiver_id', 'is_read']);
            $table->index('conversation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar en orden inverso (primero messages, luego conversations)
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
    }
};
