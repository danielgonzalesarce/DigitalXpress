<?php

/**
 * TestEmailCommand
 * 
 * Comando para probar el envío de correos electrónicos.
 * Permite enviar correos de prueba para verificar la configuración.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Console\Commands;

use App\Mail\RepairNotification;
use App\Mail\OrderNotification;
use App\Mail\ContactNotification;
use App\Models\Repair;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {type=repair : Tipo de correo a probar (repair, order, contact)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Probar el envío de correos electrónicos (reparación, pedido o contacto)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        $supportEmail = config('mail.support_email', 'soportedigitalxpress@gmail.com');

        $this->info("📧 Probando envío de correo tipo: {$type}");
        $this->info("📬 Correo de destino: {$supportEmail}");
        $this->newLine();

        try {
            switch ($type) {
                case 'repair':
                    $this->testRepairEmail($supportEmail);
                    break;
                case 'order':
                    $this->testOrderEmail($supportEmail);
                    break;
                case 'contact':
                    $this->testContactEmail($supportEmail);
                    break;
                default:
                    $this->error("Tipo de correo no válido. Usa: repair, order o contact");
                    return 1;
            }

            $this->newLine();
            $this->info("✅ Correo enviado exitosamente!");
            $this->info("📬 Revisa tu bandeja de entrada en: {$supportEmail}");
            return 0;

        } catch (\Exception $e) {
            $this->newLine();
            $this->error("❌ Error al enviar correo:");
            $this->error($e->getMessage());
            $this->newLine();
            $this->warn("💡 Verifica tu configuración en el archivo .env:");
            $this->line("   MAIL_MAILER=smtp");
            $this->line("   MAIL_HOST=smtp.gmail.com");
            $this->line("   MAIL_PORT=587");
            $this->line("   MAIL_USERNAME=tu_correo@gmail.com");
            $this->line("   MAIL_PASSWORD=tu_contraseña_de_aplicacion");
            $this->line("   MAIL_ENCRYPTION=tls");
            return 1;
        }
    }

    /**
     * Probar correo de reparación
     */
    private function testRepairEmail($supportEmail)
    {
        $repair = Repair::latest()->first();

        if (!$repair) {
            $this->warn("⚠️  No hay reparaciones en la base de datos. Creando una de prueba...");
            
            // Crear reparación de prueba
            $repair = Repair::create([
                'repair_number' => Repair::generateRepairNumber(),
                'user_id' => \App\Models\User::first()?->id,
                'full_name' => 'Usuario de Prueba',
                'email' => 'test@example.com',
                'phone' => '+51 999999999',
                'device_type' => 'Smartphone',
                'brand' => 'Apple',
                'model' => 'iPhone 13',
                'problem_description' => 'Esta es una reparación de prueba para verificar el sistema de correos.',
                'status' => 'pending'
            ]);
        }

        $repair->load('user');
        Mail::to($supportEmail)->send(new RepairNotification($repair));
        $this->info("✅ Correo de reparación enviado (Reparación #{$repair->repair_number})");
    }

    /**
     * Probar correo de pedido
     */
    private function testOrderEmail($supportEmail)
    {
        $order = Order::with('orderItems.product', 'user')->latest()->first();

        if (!$order) {
            $this->error("❌ No hay pedidos en la base de datos.");
            $this->info("💡 Crea un pedido desde la aplicación primero.");
            throw new \Exception("No hay pedidos disponibles para probar");
        }

        Mail::to($supportEmail)->send(new OrderNotification($order));
        $this->info("✅ Correo de pedido enviado (Pedido #{$order->order_number})");
    }

    /**
     * Probar correo de contacto
     */
    private function testContactEmail($supportEmail)
    {
        $contactData = [
            'name' => 'Usuario de Prueba',
            'email' => 'test@example.com',
            'subject' => 'Mensaje de Prueba - Sistema de Correos',
            'message' => 'Este es un mensaje de prueba para verificar que el sistema de correos funciona correctamente.'
        ];

        Mail::to($supportEmail)->send(new ContactNotification($contactData));
        $this->info("✅ Correo de contacto enviado");
    }
}
