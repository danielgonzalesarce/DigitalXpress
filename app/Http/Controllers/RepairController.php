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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        Repair::create($repairData);

        return redirect()->route('repairs.dashboard')
            ->with('success', 'Solicitud de reparación enviada exitosamente. Te contactaremos pronto.');
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
}
