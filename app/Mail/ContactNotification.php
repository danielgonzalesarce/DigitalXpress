<?php

/**
 * ContactNotification
 * 
 * Clase Mailable para enviar notificaciones por correo cuando un usuario
 * envía un mensaje desde el formulario de contacto.
 * 
 * El correo se envía a soportedigitalxpress@gmail.com con los datos del
 * mensaje de contacto.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Datos del formulario de contacto
     * 
     * @var array
     */
    public $contactData;

    /**
     * Create a new message instance.
     * 
     * @param array $contactData Datos del formulario (name, email, subject, message)
     */
    public function __construct(array $contactData)
    {
        $this->contactData = $contactData;
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
            subject: 'Nuevo Mensaje de Contacto - ' . ($this->contactData['subject'] ?? 'Sin asunto'),
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
            view: 'emails.contact-notification',
            with: [
                'contactData' => $this->contactData,
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
