<?php

/**
 * PageController
 * 
 * Controlador para páginas estáticas/informativas de DigitalXpress.
 * Maneja la visualización de páginas de contenido estático:
 * - Centro de ayuda
 * - Garantías
 * - Devoluciones
 * - Contacto
 * - Sobre nosotros
 * - Términos y condiciones
 * - Política de privacidad
 * - Blog
 * - Página de desarrollo (en construcción)
 * 
 * Todas estas páginas son públicas y no requieren autenticación.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Http\Controllers;

use App\Mail\ContactNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

class PageController extends Controller
{
    /**
     * Mostrar página del Centro de Ayuda
     * 
     * @return \Illuminate\View\View Vista del centro de ayuda
     */
    public function helpCenter()
    {
        return view('pages.help-center');
    }

    /**
     * Mostrar página de Garantías
     * 
     * @return \Illuminate\View\View Vista de garantías
     */
    public function warranties()
    {
        return view('pages.warranties');
    }

    /**
     * Mostrar página de Devoluciones
     * 
     * @return \Illuminate\View\View Vista de devoluciones
     */
    public function returns()
    {
        return view('pages.returns');
    }

    /**
     * Mostrar página de Contacto
     * 
     * @return \Illuminate\View\View Vista de contacto
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Mostrar página Sobre Nosotros
     * 
     * @return \Illuminate\View\View Vista sobre nosotros
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Mostrar página de Términos y Condiciones
     * 
     * @return \Illuminate\View\View Vista de términos
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Mostrar página de Política de Privacidad
     * 
     * @return \Illuminate\View\View Vista de privacidad
     */
    public function privacy()
    {
        return view('pages.privacy');
    }

    /**
     * Mostrar página del Blog
     * 
     * @return \Illuminate\View\View Vista del blog
     */
    public function blog()
    {
        return view('pages.blog');
    }

    /**
     * Mostrar página de Desarrollo (En Construcción)
     * 
     * Página temporal para funcionalidades en desarrollo.
     * 
     * @return \Illuminate\View\View Vista de desarrollo
     */
    public function development()
    {
        return view('pages.development');
    }

    /**
     * Procesar formulario de contacto
     * 
     * Valida los datos del formulario y envía un correo de notificación
     * a soportedigitalxpress@gmail.com con los detalles del mensaje.
     * 
     * @param Request $request Datos del formulario de contacto
     * @return \Illuminate\Http\RedirectResponse Redirige de vuelta con mensaje de éxito/error
     */
    public function sendContact(Request $request)
    {
        // Validar datos del formulario
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'priority' => 'required|in:low,medium,high',
        ], [
            'subject.required' => 'El asunto es obligatorio.',
            'message.required' => 'El mensaje es obligatorio.',
            'message.min' => 'El mensaje debe tener al menos 10 caracteres.',
            'priority.required' => 'La prioridad es obligatoria.',
            'priority.in' => 'La prioridad seleccionada no es válida.',
        ]);

        // Si el usuario está autenticado, usar sus datos de sesión
        // Si no está autenticado, usar los datos del formulario
        if (Auth::check()) {
            $contactData = [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'priority' => $validated['priority'],
                'user_id' => Auth::id(),
                'is_authenticated' => true,
            ];
            
            \Log::info('Usuario autenticado enviando mensaje de contacto', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'user_email' => Auth::user()->email,
                'subject' => $validated['subject'],
                'priority' => $validated['priority']
            ]);
        } else {
            // Validar nombre y email solo si no está autenticado
            $validatedGuest = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser válido.',
            ]);
            
            $contactData = [
                'name' => $validatedGuest['name'],
                'email' => $validatedGuest['email'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'priority' => $validated['priority'],
                'is_authenticated' => false,
            ];
        }

        // Enviar correo de notificación a soporte
        $supportEmail = config('mail.support_email', 'soportedigitalxpress@gmail.com');
        $emailSent = false;
        $emailError = null;
        
        try {
            \Log::info('Intentando enviar correo de contacto', [
                'from' => $contactData['email'],
                'to' => $supportEmail,
                'subject' => $contactData['subject'],
                'user_id' => $contactData['user_id'] ?? null,
                'is_authenticated' => $contactData['is_authenticated']
            ]);
            
            // Enviar correo de forma síncrona (inmediata)
            Mail::to($supportEmail)->send(new ContactNotification($contactData));
            
            // Ejecutar comandos automáticamente para asegurar que el correo se procese
            $this->processEmailDelivery();
            
            $emailSent = true;
            
            \Log::info('Correo de contacto enviado exitosamente', [
                'from' => $contactData['email'],
                'to' => $supportEmail,
                'subject' => $contactData['subject'],
                'user_id' => $contactData['user_id'] ?? null,
                'is_authenticated' => $contactData['is_authenticated'],
                'message_preview' => substr($contactData['message'], 0, 50) . '...'
            ]);
            
            $message = 'Tu mensaje ha sido enviado exitosamente a soportedigitalxpress@gmail.com. Te responderemos pronto.';
            
            return redirect()->route('pages.contact')
                ->with('success', $message)
                ->with('email_sent', true);
                
        } catch (\Exception $e) {
            $emailError = $e->getMessage();
            \Log::error('Error al enviar correo de contacto', [
                'from' => $contactData['email'],
                'to' => $supportEmail,
                'subject' => $contactData['subject'],
                'error' => $emailError,
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Hubo un error al enviar tu mensaje. Por favor, intenta nuevamente o contáctanos directamente a soportedigitalxpress@gmail.com')
                ->with('email_sent', false)
                ->withInput();
        }
    }

    /**
     * Ejecutar comandos automáticamente para procesar el envío de correo
     * 
     * Este método ejecuta comandos de Laravel para asegurar que el correo
     * se procese y envíe correctamente inmediatamente después del envío.
     * 
     * Los comandos ejecutados son:
     * - config:clear: Limpia la caché de configuración
     * - queue:work --once: Procesa cualquier cola pendiente de correo
     * 
     * @return void
     */
    private function processEmailDelivery()
    {
        try {
            // Limpiar caché de configuración para asegurar que los cambios se reflejen
            Artisan::call('config:clear');
            
            // Procesar colas pendientes (si hay alguna)
            // Esto asegura que cualquier correo en cola se procese inmediatamente
            try {
                Artisan::call('queue:work', [
                    '--once' => true,
                    '--timeout' => 10,
                    '--tries' => 1
                ]);
            } catch (\Exception $queueException) {
                // Si no hay colas configuradas o no hay trabajos pendientes, ignorar el error
                // El correo ya se envió con Mail::send() de forma síncrona
                \Log::debug('No hay colas pendientes o colas no configuradas: ' . $queueException->getMessage());
            }
            
            \Log::info('Comandos de procesamiento de correo ejecutados exitosamente');
        } catch (\Exception $e) {
            // No interrumpir el flujo si hay error en los comandos
            // El correo ya se envió con Mail::send() de forma síncrona
            \Log::warning('Error al ejecutar comandos de procesamiento de correo: ' . $e->getMessage());
        }
    }
}

