<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RepairController extends Controller
{
    public function index()
    {
        // Si el usuario está autenticado, redirigir automáticamente al dashboard
        if (Auth::check()) {
            return redirect()->route('repairs.dashboard');
        }
        
        return view('repairs.index');
    }

    public function dashboard()
    {
        // Solo usuarios autenticados pueden acceder
        if (!Auth::check()) {
            return redirect()->route('repairs.index')
                ->with('error', 'Necesitas iniciar sesión para acceder al servicio técnico');
        }

        $repairs = Auth::user()->repairs()->orderBy('created_at', 'desc')->get();
        
        return view('repairs.dashboard', compact('repairs'));
    }

    public function create()
    {
        // Solo usuarios autenticados pueden crear reparaciones
        if (!Auth::check()) {
            return redirect()->route('repairs.index')
                ->with('error', 'Necesitas iniciar sesión para crear una reparación');
        }

        $repairs = Auth::user()->repairs()->orderBy('created_at', 'desc')->limit(5)->get();
        return view('repairs.create', compact('repairs'));
    }

    public function store(Request $request)
    {
        // Solo usuarios autenticados pueden crear reparaciones
        if (!Auth::check()) {
            return redirect()->route('repairs.index')
                ->with('error', 'Necesitas iniciar sesión para crear una reparación');
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'device_type' => 'required|string|max:100',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'problem_description' => 'required|string|min:20',
            'device_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'problem_description.min' => 'La descripción del problema debe tener al menos 20 caracteres.',
            'device_image.image' => 'El archivo debe ser una imagen válida.',
            'device_image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif.',
            'device_image.max' => 'La imagen no debe ser mayor a 2MB.'
        ]);

        $repairData = [
            'repair_number' => Repair::generateRepairNumber(),
            'user_id' => Auth::id(),
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
