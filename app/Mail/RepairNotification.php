<?php

/**
 * RepairNotification
 * 
 * Clase Mailable para enviar notificaciones por correo cuando un usuario
 * solicita una nueva reparación.
 * 
 * El correo se envía a soportedigitalxpress@gmail.com con todos los detalles
 * de la solicitud de reparación.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Mail;

use App\Models\Repair;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RepairNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Instancia de la reparación
     * 
     * @var Repair
     */
    public $repair;

    /**
     * Create a new message instance.
     * 
     * @param Repair $repair Instancia de la reparación solicitada
     */
    public function __construct(Repair $repair)
    {
        $this->repair = $repair;
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
            subject: 'Nueva Solicitud de Reparación - ' . $this->repair->repair_number,
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
            view: 'emails.repair-notification',
            with: [
                'repair' => $this->repair,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     * 
     * Si la reparación tiene una imagen del dispositivo, se adjunta al correo
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        
        // Adjuntar imagen del dispositivo si existe
        if ($this->repair->device_image) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromStorageDisk('public', $this->repair->device_image)
                ->as('dispositivo-' . $this->repair->repair_number . '.jpg')
                ->withMime('image/jpeg');
        }
        
        return $attachments;
    }
}
