<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('text');
                $table->string('group')->default('general');
                $table->string('label');
                $table->text('description')->nullable();
                $table->timestamps();
            });

            // Insertar configuración inicial
            DB::table('settings')->insert([
            // Información de la Tienda
            ['key' => 'store_name', 'value' => 'DigitalXpress', 'type' => 'text', 'group' => 'store', 'label' => 'Nombre de la Tienda', 'description' => 'Nombre que aparece en el sitio web', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'store_description', 'value' => 'Tu tienda de tecnología de confianza. Los mejores productos, precios competitivos y servicio excepcional.', 'type' => 'textarea', 'group' => 'store', 'label' => 'Descripción de la Tienda', 'description' => 'Descripción que aparece en el footer', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'store_email', 'value' => 'soporte@digitalxpress.com', 'type' => 'text', 'group' => 'store', 'label' => 'Email de Contacto', 'description' => 'Email principal de contacto', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'store_phone', 'value' => '+51 936068781', 'type' => 'text', 'group' => 'store', 'label' => 'Teléfono de Contacto', 'description' => 'Teléfono principal de contacto', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'store_address', 'value' => '', 'type' => 'textarea', 'group' => 'store', 'label' => 'Dirección', 'description' => 'Dirección física de la tienda', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'store_website', 'value' => 'www.digitalxpress.com', 'type' => 'text', 'group' => 'store', 'label' => 'Sitio Web', 'description' => 'URL del sitio web', 'created_at' => now(), 'updated_at' => now()],
            
            // Configuración de Envíos
            ['key' => 'shipping_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'shipping', 'label' => 'Habilitar Envíos', 'description' => 'Activar o desactivar el servicio de envíos', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shipping_cost', 'value' => '10.00', 'type' => 'number', 'group' => 'shipping', 'label' => 'Costo de Envío Base', 'description' => 'Costo base de envío en soles', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'free_shipping_threshold', 'value' => '100.00', 'type' => 'number', 'group' => 'shipping', 'label' => 'Umbral de Envío Gratis', 'description' => 'Monto mínimo para envío gratis', 'created_at' => now(), 'updated_at' => now()],
            
            // Métodos de Pago
            ['key' => 'payment_credit_card', 'value' => '1', 'type' => 'boolean', 'group' => 'payment', 'label' => 'Tarjeta de Crédito', 'description' => 'Habilitar pago con tarjeta de crédito', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'payment_debit_card', 'value' => '1', 'type' => 'boolean', 'group' => 'payment', 'label' => 'Tarjeta de Débito', 'description' => 'Habilitar pago con tarjeta de débito', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'payment_yape', 'value' => '1', 'type' => 'boolean', 'group' => 'payment', 'label' => 'Yape', 'description' => 'Habilitar pago con Yape', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'payment_cash', 'value' => '1', 'type' => 'boolean', 'group' => 'payment', 'label' => 'Efectivo', 'description' => 'Habilitar pago en efectivo', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

