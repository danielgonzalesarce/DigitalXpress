<?php

/**
 * MessageController
 * 
 * Controlador para el sistema de mensajería del usuario.
 * Permite a los usuarios autenticados:
 * - Ver su bandeja de entrada
 * - Enviar mensajes a administradores
 * - Ver conversaciones
 * - Marcar mensajes como leídos
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Mostrar formulario para crear nuevo mensaje
     * 
     * @return \Illuminate\View\View Vista del formulario
     */
    /**
     * Mostrar formulario para crear nuevo mensaje
     * 
     * Permite al usuario iniciar una nueva conversación con un administrador.
     * 
     * @return \Illuminate\View\View Vista del formulario de creación
     * @return \Illuminate\Http\RedirectResponse Redirige al login si no está autenticado
     */
    public function create()
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para enviar mensajes');
        }

        // Mostrar formulario para crear nuevo mensaje
        return view('messages.create');
    }

    /**
     * Mostrar bandeja de entrada del usuario
     * 
     * Muestra todas las conversaciones del usuario con administradores,
     * ordenadas por fecha del último mensaje.
     * 
     * @return \Illuminate\View\View Vista de la bandeja de entrada
     */
    /**
     * Mostrar bandeja de entrada del usuario
     * 
     * Muestra todas las conversaciones del usuario con administradores,
     * ordenadas por fecha del último mensaje (más recientes primero).
     * También muestra el contador de mensajes no leídos.
     * 
     * @return \Illuminate\View\View Vista de la bandeja de entrada
     * @return \Illuminate\Http\RedirectResponse Redirige al login si no está autenticado
     */
    public function index()
    {
        // Verificar autenticación del usuario
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a tus mensajes');
        }

        $user = Auth::user();
        
        // Obtener todas las conversaciones del usuario con sus relaciones
        $conversations = Conversation::where('user_id', $user->id)
            ->with(['admin', 'messages' => function($query) {
                // Cargar mensajes ordenados por fecha (más recientes primero)
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('last_message_at', 'desc') // Ordenar conversaciones por último mensaje
            ->get();

        // Contar total de mensajes no leídos del usuario
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

        // Retornar vista con conversaciones y contador de no leídos
        return view('messages.index', compact('conversations', 'unreadCount'));
    }

    /**
     * Mostrar una conversación específica
     * 
     * Muestra todos los mensajes de una conversación y permite
     * enviar nuevos mensajes.
     * 
     * @param Conversation $conversation La conversación a mostrar
     * @return \Illuminate\View\View Vista de la conversación
     */
    /**
     * Mostrar una conversación específica con todos sus mensajes
     * 
     * Muestra todos los mensajes de una conversación y permite enviar nuevos mensajes.
     * Automáticamente marca los mensajes como leídos cuando el usuario los ve.
     * 
     * @param Conversation $conversation La conversación a mostrar
     * @return \Illuminate\View\View Vista de la conversación con mensajes
     * @return \Illuminate\Http\RedirectResponse Redirige al login si no está autenticado
     */
    public function show(Conversation $conversation)
    {
        // Verificar autenticación del usuario
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Verificar que el usuario es el propietario de la conversación
        // Solo el usuario que inició la conversación puede verla
        if ($conversation->user_id !== $user->id) {
            abort(403, 'No tienes permisos para ver esta conversación');
        }

        // Marcar todos los mensajes no leídos como leídos cuando el usuario abre la conversación
        Message::where('conversation_id', $conversation->id)
            ->where('receiver_id', $user->id)      // Solo mensajes recibidos por el usuario
            ->where('is_read', false)               // Solo los que no están leídos
            ->update([
                'is_read' => true,                  // Marcar como leído
                'read_at' => now(),                 // Guardar fecha de lectura
            ]);

        // Cargar todas las relaciones necesarias para la vista
        $conversation->load(['messages.sender', 'messages.receiver', 'admin', 'user']);

        // Mostrar vista con la conversación completa
        return view('messages.show', compact('conversation'));
    }

    /**
     * Crear una nueva conversación y enviar el primer mensaje
     * 
     * @param Request $request Datos del formulario
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Crear una nueva conversación y enviar el primer mensaje
     * 
     * Crea una nueva conversación con un administrador y envía el primer mensaje.
     * Si ya existe una conversación con el mismo administrador, la reutiliza.
     * 
     * @param Request $request Datos del formulario (subject, message)
     * @return \Illuminate\Http\RedirectResponse Redirección a la conversación creada
     */
    public function store(Request $request)
    {
        // Verificar autenticación del usuario
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Validar datos del formulario
        $request->validate([
            'subject' => 'required|string|max:255',    // Asunto obligatorio, máximo 255 caracteres
            'message' => 'required|string|min:10',    // Mensaje obligatorio, mínimo 10 caracteres
        ], [
            'subject.required' => 'El asunto es obligatorio',
            'message.required' => 'El mensaje es obligatorio',
            'message.min' => 'El mensaje debe tener al menos 10 caracteres',
        ]);

        try {
            // Iniciar transacción de base de datos
            DB::beginTransaction();

            // Obtener el primer administrador disponible (con dominio @digitalxpress.com)
            $admin = User::where('email', 'like', '%@digitalxpress.com')->first();

            // Verificar que haya un administrador disponible
            if (!$admin) {
                return redirect()->back()
                    ->with('error', 'No hay administradores disponibles. Por favor, contacta directamente a soportedigitalxpress@gmail.com')
                    ->withInput();
            }

            // Crear nueva conversación o obtener existente si ya hay una con este admin
            $conversation = Conversation::firstOrCreate(
                [
                    'user_id' => Auth::id(),           // ID del usuario actual
                    'admin_id' => $admin->id,          // ID del administrador asignado
                ],
                [
                    'subject' => $request->subject,    // Asunto de la conversación
                    'last_message_at' => now(),       // Fecha del último mensaje
                ]
            );

            // Crear el primer mensaje de la conversación
            $message = Message::create([
                'sender_id' => Auth::id(),             // Usuario envía el mensaje
                'receiver_id' => $admin->id,          // Administrador recibe el mensaje
                'message' => $request->message,       // Contenido del mensaje
                'subject' => $request->subject,       // Asunto del mensaje
                'type' => 'user_to_admin',            // Tipo: de usuario a administrador
                'conversation_id' => $conversation->id, // ID de la conversación
            ]);

            // Actualizar fecha del último mensaje en la conversación
            $conversation->update(['last_message_at' => now()]);

            // Confirmar transacción
            DB::commit();

            // Redirigir a la conversación creada con mensaje de éxito
            return redirect()->route('messages.show', $conversation)
                ->with('success', 'Mensaje enviado exitosamente');

        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            // Registrar error en logs
            \Log::error('Error al crear mensaje: ' . $e->getMessage());
            
            // Redirigir con mensaje de error
            return redirect()->back()
                ->with('error', 'Hubo un error al enviar tu mensaje. Por favor, intenta nuevamente.')
                ->withInput();
        }
    }

    /**
     * Enviar un mensaje en una conversación existente
     * 
     * @param Request $request Datos del formulario
     * @param Conversation $conversation La conversación donde enviar el mensaje
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Enviar un mensaje en una conversación existente
     * 
     * Permite al usuario responder en una conversación ya iniciada.
     * El mensaje se envía al administrador asignado a la conversación.
     * 
     * @param Request $request Datos del formulario (message)
     * @param Conversation $conversation La conversación donde enviar el mensaje
     * @return \Illuminate\Http\RedirectResponse Redirección a la conversación
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        // Verificar autenticación del usuario
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Verificar que el usuario es el propietario de la conversación
        // Solo el usuario que inició la conversación puede enviar mensajes
        if ($conversation->user_id !== $user->id) {
            abort(403, 'No tienes permisos para enviar mensajes en esta conversación');
        }

        // Validar que el mensaje tenga contenido mínimo
        $request->validate([
            'message' => 'required|string|min:10', // Mensaje obligatorio, mínimo 10 caracteres
        ], [
            'message.required' => 'El mensaje es obligatorio',
            'message.min' => 'El mensaje debe tener al menos 10 caracteres',
        ]);

        try {
            // Iniciar transacción de base de datos
            DB::beginTransaction();

            // Crear nuevo mensaje en la conversación existente
            $message = Message::create([
                'sender_id' => $user->id,                    // Usuario envía el mensaje
                'receiver_id' => $conversation->admin_id,  // Administrador recibe el mensaje
                'message' => $request->message,            // Contenido del mensaje
                'subject' => $conversation->subject,       // Usar el asunto de la conversación
                'type' => 'user_to_admin',                 // Tipo: de usuario a administrador
                'conversation_id' => $conversation->id,    // ID de la conversación
            ]);

            // Actualizar fecha del último mensaje para ordenar conversaciones
            $conversation->update(['last_message_at' => now()]);

            // Confirmar transacción
            DB::commit();

            // Redirigir a la conversación con mensaje de éxito
            return redirect()->route('messages.show', $conversation)
                ->with('success', 'Mensaje enviado exitosamente');

        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            // Registrar error en logs
            \Log::error('Error al enviar mensaje: ' . $e->getMessage());
            
            // Redirigir con mensaje de error
            return redirect()->back()
                ->with('error', 'Hubo un error al enviar tu mensaje. Por favor, intenta nuevamente.')
                ->withInput();
        }
    }
}
