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
    /**
     * Constructor: Recibe la instancia de la reparación
     * 
     * @param Repair $repair Reparación a notificar
     */
    public function __construct(Repair $repair)
    {
        // Guardar la reparación para usar en el correo
        $this->repair = $repair;
    }

    /**
     * Configurar el sobre del correo (asunto y destinatario)
     * 
     * Define el asunto del correo con el número de reparación.
     * 
     * @return Envelope Configuración del sobre del correo
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // Asunto del correo con número de reparación
            subject: 'Nueva Solicitud de Reparación - ' . $this->repair->repair_number,
        );
    }

    /**
     * Configurar el contenido del correo
     * 
     * Define qué vista usar y qué datos pasar a la vista.
     * 
     * @return Content Configuración del contenido del correo
     */
    public function content(): Content
    {
        return new Content(
            // Vista Blade que se usará como plantilla del correo
            view: 'emails.repair-notification',
            // Datos que se pasarán a la vista
            with: [
                'repair' => $this->repair,
            ],
        );
    }

    /**
     * Obtener archivos adjuntos para el correo
     * 
     * Si la reparación tiene una imagen del dispositivo, se adjunta automáticamente.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment> Array de archivos adjuntos
     */
    public function attachments(): array
    {
        $attachments = [];
        
        // Verificar si existe imagen del dispositivo
        if ($this->repair->device_image) {
            // Crear adjunto desde el disco de almacenamiento público
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromStorageDisk('public', $this->repair->device_image)
                ->as('dispositivo-' . $this->repair->repair_number . '.jpg') // Nombre del archivo adjunto
                ->withMime('image/jpeg'); // Tipo MIME de la imagen
        }
        
        return $attachments;
    }
}
