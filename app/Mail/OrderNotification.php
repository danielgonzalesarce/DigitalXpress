<?php

/**
 * OrderNotification
 * 
 * Clase Mailable para enviar notificaciones por correo cuando un usuario
 * realiza un nuevo pedido.
 * 
 * El correo se envía a soportedigitalxpress@gmail.com con todos los detalles
 * del pedido realizado.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Instancia del pedido
     * 
     * @var Order
     */
    public $order;

    /**
     * Create a new message instance.
     * 
     * @param Order $order Instancia del pedido realizado
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     * 
     * Define el asunto y destinatario del correo
     * 
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nuevo Pedido - ' . $this->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     * 
     * Define la vista del correo y los datos a pasar
     * 
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-notification',
            with: [
                'order' => $this->order,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
