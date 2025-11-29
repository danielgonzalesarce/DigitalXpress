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
     */
    public function up(): void
    {
        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                
                // Relaciones: usuario que envía y usuario que recibe
                $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
                
                // Contenido del mensaje
                $table->text('message');
                $table->string('subject')->nullable();
                
                // Estado del mensaje
                $table->boolean('is_read')->default(false);
                $table->timestamp('read_at')->nullable();
                
                // Tipo de mensaje
                $table->enum('type', ['user_to_admin', 'admin_to_user'])->default('user_to_admin');
                
                // Relación con conversación
                $table->foreignId('conversation_id')->nullable()->constrained('conversations')->onDelete('cascade');
                
                $table->timestamps();
                
                // Índices para mejorar rendimiento
                $table->index(['sender_id', 'receiver_id']);
                $table->index(['receiver_id', 'is_read']);
                $table->index('conversation_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

