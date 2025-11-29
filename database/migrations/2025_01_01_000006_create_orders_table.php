<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Migración consolidada de pedidos con todos los campos:
     * - Campos básicos del pedido
     * - Campos de checkout (customer_name, customer_email, customer_phone, transaction_id, session_id)
     * - user_id nullable para permitir pedidos de invitados
     */
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
                $table->enum('status', ['pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
                $table->decimal('subtotal', 10, 2);
                $table->decimal('tax_amount', 10, 2)->default(0);
                $table->decimal('shipping_amount', 10, 2)->default(0);
                $table->decimal('total_amount', 10, 2);
                $table->string('payment_status')->default('pending');
                $table->string('payment_method')->nullable();
                $table->json('billing_address')->nullable();
                $table->json('shipping_address')->nullable();
                $table->text('notes')->nullable();
                // Campos de checkout
                $table->string('customer_name')->nullable();
                $table->string('customer_email')->nullable();
                $table->string('customer_phone')->nullable();
                $table->string('transaction_id')->nullable();
                $table->string('session_id')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

