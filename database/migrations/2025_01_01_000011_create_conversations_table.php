<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Crea la tabla de conversaciones para el sistema de mensajería
     */
    public function up(): void
    {
        if (!Schema::hasTable('conversations')) {
            Schema::create('conversations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('subject')->nullable();
                $table->timestamp('last_message_at')->nullable();
                $table->timestamps();
                
                $table->index(['user_id', 'admin_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};

