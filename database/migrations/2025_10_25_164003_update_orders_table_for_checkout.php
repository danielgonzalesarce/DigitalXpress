<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Agregar campos necesarios para el checkout (solo si no existen)
        if (!Schema::hasColumn('orders', 'customer_name')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('customer_name')->nullable();
            });
        }
        
        if (!Schema::hasColumn('orders', 'customer_email')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('customer_email')->nullable();
            });
        }
        
        if (!Schema::hasColumn('orders', 'customer_phone')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('customer_phone')->nullable();
            });
        }
        
        if (!Schema::hasColumn('orders', 'transaction_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('transaction_id')->nullable();
            });
        }
        
        if (!Schema::hasColumn('orders', 'session_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('session_id')->nullable();
            });
        }
        
        // Cambiar el enum de status para incluir 'paid' (solo si la columna existe y necesita actualizarse)
        if (Schema::hasColumn('orders', 'status')) {
            // Verificar si ya tiene el valor 'paid' en el enum
            // Si no, necesitamos recrear la columna
            try {
                Schema::table('orders', function (Blueprint $table) {
                    $table->dropColumn('status');
                });
                
                Schema::table('orders', function (Blueprint $table) {
                    $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
                });
            } catch (\Exception $e) {
                // Si falla, la columna puede que ya tenga el formato correcto
            }
        } else {
            // Si no existe la columna, crearla
            Schema::table('orders', function (Blueprint $table) {
                $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_email', 
                'customer_phone',
                'shipping_address',
                'transaction_id',
                'session_id'
            ]);
            
            $table->dropColumn('status');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
        });
    }
};