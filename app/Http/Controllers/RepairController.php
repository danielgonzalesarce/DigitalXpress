<?php

/**
 * RepairController
 * 
 * Controlador para la gestión del servicio de reparaciones.
 * Permite a los usuarios autenticados:
 * - Ver información del servicio
 * - Acceder a su dashboard de reparaciones
 * - Crear nuevas solicitudes de reparación
 * - Ver detalles de sus reparaciones
 * - Agendar citas
 * - Contactar soporte
 * - Descargar reportes
 * 
 * Todas las operaciones requieren autenticación excepto index().
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Mail\RepairNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class RepairController extends Controller
{
    /**
     * Mostrar página de información del servicio de reparaciones
     * 
     * Si el usuario está autenticado, redirige automáticamente al dashboard.
     * Si no está autenticado, muestra la página informativa.
     * 
     * @return \Illuminate\View\View Vista de información o redirección al dashboard
     */
    public function index()
    {
        // Si el usuario está autenticado, redirigir automáticamente al dashboard
        if (Auth::check()) {
            return redirect()->route('repairs.dashboard');
        }
        
        // Si no está autenticado, mostrar página informativa
        return view('repairs.index');
    }

    /**
     * Mostrar dashboard de reparaciones del usuario
     * 
     * Muestra todas las reparaciones del usuario autenticado,
     * ordenadas por fecha de creación (más recientes primero).
     * 
     * @return \Illuminate\View\View Vista del dashboard con lista de reparaciones
     * @return \Illuminate\Http\RedirectResponse Redirige al login si no está autenticado
     */
    public function dashboard()
    {
        // Solo usuarios autenticados pueden acceder
        if (!Auth::check()) {
            return redirect()->route('repairs.index')
                ->with('error', 'Necesitas iniciar sesión para acceder al servicio técnico');
        }

        // Obtener todas las reparaciones del usuario ordenadas por fecha
        $repairs = Auth::user()->repairs()->orderBy('created_at', 'desc')->get();
        
        return view('repairs.dashboard', compact('repairs'));
    }

    /**
     * Mostrar formulario para crear nueva reparación
     * 
     * Muestra el formulario y también las últimas 5 reparaciones del usuario
     * para referencia.
     * 
     * @return \Illuminate\View\View Vista del formulario de creación
     * @return \Illuminate\Http\RedirectResponse Redirige al login si no está autenticado
     */
    public function create()
    {
        // Solo usuarios autenticados pueden crear reparaciones
        if (!Auth::check()) {
            return redirect()->route('repairs.index')
                ->with('error', 'Necesitas iniciar sesión para crear una reparación');
        }

        // Obtener últimas 5 reparaciones del usuario para mostrar como referencia
        $repairs = Auth::user()->repairs()->orderBy('created_at', 'desc')->limit(5)->get();
        return view('repairs.create', compact('repairs'));
    }

    /**
     * Guardar nueva solicitud de reparación
     * 
     * Valida los datos del formulario, procesa la imagen del dispositivo,
     * genera un número único de reparación y crea el registro en la base de datos.
     * 
     * @param Request $request Datos del formulario de reparación
     * @return \Illuminate\Http\RedirectResponse Redirige al dashboard con mensaje de éxito/error
     */
    public function store(Request $request)
    {
        // Solo usuarios autenticados pueden crear reparaciones
        if (!Auth::check()) {
            return redirect()->route('repairs.index')
                ->with('error', 'Necesitas iniciar sesión para crear una reparación');
        }

        // Validar datos del formulario
        $request->validate([
            'full_name' => 'required|string|max:255', // Nombre completo obligatorio
            'email' => 'required|email|max:255', // Email válido obligatorio
            'phone' => 'required|string|max:20', // Teléfono obligatorio
            'device_type' => 'required|string|max:100', // Tipo de dispositivo obligatorio
            'brand' => 'required|string|max:100', // Marca obligatoria
            'model' => 'required|string|max:100', // Modelo obligatorio
            'problem_description' => 'required|string|min:20', // Descripción mínima 20 caracteres
            'device_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Imagen opcional, máximo 2MB
        ], [
            'problem_description.min' => 'La descripción del problema debe tener al menos 20 caracteres.',
            'device_image.image' => 'El archivo debe ser una imagen válida.',
            'device_image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'device_image.max' => 'La imagen no debe ser mayor a 2MB.'
        ]);

        // Preparar datos para crear la reparación
        $repairData = [
            'repair_number' => Repair::generateRepairNumber(), // Generar número único automáticamente
            'user_id' => Auth::id(), // Asignar al usuario autenticado
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'device_type' => $request->device_type,
            'brand' => $request->brand,
            'model' => $request->model,
            'problem_description' => $request->problem_description,
            'status' => 'pending'
        ];

        // Manejar subida de imagen
        if ($request->hasFile('device_image')) {
            $imagePath = $request->file('device_image')->store('repairs', 'public');
            $repairData['device_image'] = $imagePath;
        }

        $repair = Repair::create($repairData);

        // Cargar relación de usuario para el correo
        $repair->load('user');

        // Enviar correo de notificación a soporte
        $supportEmail = config('mail.support_email', 'soportedigitalxpress@gmail.com');
        $emailSent = false;
        $emailError = null;
        
        try {
            // Enviar correo de forma síncrona (inmediata)
            Mail::to($supportEmail)->send(new RepairNotification($repair));
            
            // Ejecutar comandos automáticamente para asegurar que el correo se procese
            $this->processEmailDelivery();
            
            $emailSent = true;
            \Log::info('Correo de notificación de reparación enviado exitosamente', [
                'repair_id' => $repair->id,
                'repair_number' => $repair->repair_number,
                'to' => $supportEmail
            ]);
        } catch (\Exception $e) {
            $emailError = $e->getMessage();
            \Log::error('Error al enviar correo de notificación de reparación', [
                'repair_id' => $repair->id,
                'repair_number' => $repair->repair_number,
                'to' => $supportEmail,
                'error' => $emailError,
                'trace' => $e->getTraceAsString()
            ]);
        }

        $message = 'Solicitud de reparación enviada exitosamente. Te contactaremos pronto.';
        if (!$emailSent) {
            $message .= ' Nota: Hubo un problema al enviar la notificación por correo, pero tu solicitud fue registrada correctamente.';
        }

        return redirect()->route('repairs.dashboard')
            ->with('success', $message)
            ->with('email_sent', $emailSent);
    }

    public function show(Repair $repair)
    {
        // Solo el propietario puede ver su reparación
        if ($repair->user_id !== Auth::id()) {
            abort(403, 'No tienes permisos para ver esta reparación');
        }

        return view('repairs.show', compact('repair'));
    }

    public function schedule()
    {
        // Solo usuarios autenticados pueden agendar citas
        if (!Auth::check()) {
            return redirect()->route('repairs.index')
                ->with('error', 'Necesitas iniciar sesión para agendar una cita');
        }

        return view('repairs.schedule');
    }

    public function contact()
    {
        // Solo usuarios autenticados pueden contactar soporte
        if (!Auth::check()) {
            return redirect()->route('repairs.index')
                ->with('error', 'Necesitas iniciar sesión para contactar soporte');
        }

        return view('repairs.contact');
    }

    public function downloadReport()
    {
        // Solo usuarios autenticados pueden descargar reportes
        if (!Auth::check()) {
            return redirect()->route('repairs.index')
                ->with('error', 'Necesitas iniciar sesión para descargar reportes');
        }

        $repairs = Auth::user()->repairs()->orderBy('created_at', 'desc')->get();
        
        // Generar reporte en PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('repairs.report', compact('repairs'));
        
        $filename = 'reporte_reparaciones_' . Auth::user()->name . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
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
