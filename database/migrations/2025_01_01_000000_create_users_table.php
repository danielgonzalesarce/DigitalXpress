<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migración consolidada de usuarios con todos los campos:
     * - Campos básicos de autenticación
     * - Role (admin, customer)
     * - Google OAuth (google_id, avatar)
     */
    public function up(): void
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                // ID principal autoincremental
                $table->id();
                
                // Información básica del usuario
                $table->string('name');                              // Nombre completo
                $table->string('email')->unique();                   // Email único
                $table->timestamp('email_verified_at')->nullable();   // Fecha de verificación de email
                $table->string('password');                          // Contraseña hasheada
                
                // Rol del usuario (admin, customer, etc.)
                $table->string('role')->default('customer');
                
                // Campos para autenticación con Google OAuth
                $table->string('google_id')->nullable();             // ID único de Google (si se registró con Google)
                $table->string('avatar')->nullable();                // URL del avatar de Google o imagen personalizada
                
                // Token para recordar sesión
                $table->rememberToken();
                
                // Timestamps automáticos
                $table->timestamps();
            });
        } else {
            // Si la tabla existe, solo agregar columnas que falten
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'role')) {
                    $table->string('role')->default('customer')->after('email');
                }
                // Agregar campo google_id si no existe (para autenticación con Google)
                if (!Schema::hasColumn('users', 'google_id')) {
                    $table->string('google_id')->nullable()->after('email');
                }
                // Agregar campo avatar si no existe (URL del avatar de Google)
                if (!Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable()->after('google_id');
                }
            });
        }

        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};

