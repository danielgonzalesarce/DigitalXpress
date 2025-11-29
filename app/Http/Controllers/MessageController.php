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
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para enviar mensajes');
        }

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
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a tus mensajes');
        }

        $user = Auth::user();
        
        // Obtener todas las conversaciones del usuario
        $conversations = Conversation::where('user_id', $user->id)
            ->with(['admin', 'messages' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Contar mensajes no leídos
        $unreadCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();

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
    public function show(Conversation $conversation)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Verificar que el usuario es el propietario de la conversación
        if ($conversation->user_id !== $user->id) {
            abort(403, 'No tienes permisos para ver esta conversación');
        }

        // Marcar mensajes como leídos
        Message::where('conversation_id', $conversation->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // Cargar mensajes y relaciones
        $conversation->load(['messages.sender', 'messages.receiver', 'admin', 'user']);

        return view('messages.show', compact('conversation'));
    }

    /**
     * Crear una nueva conversación y enviar el primer mensaje
     * 
     * @param Request $request Datos del formulario
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ], [
            'subject.required' => 'El asunto es obligatorio',
            'message.required' => 'El mensaje es obligatorio',
            'message.min' => 'El mensaje debe tener al menos 10 caracteres',
        ]);

        try {
            DB::beginTransaction();

            // Obtener el primer administrador disponible
            $admin = User::where('email', 'like', '%@digitalxpress.com')->first();

            if (!$admin) {
                return redirect()->back()
                    ->with('error', 'No hay administradores disponibles. Por favor, contacta directamente a soportedigitalxpress@gmail.com')
                    ->withInput();
            }

            // Crear o obtener conversación existente
            $conversation = Conversation::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'admin_id' => $admin->id,
                ],
                [
                    'subject' => $request->subject,
                    'last_message_at' => now(),
                ]
            );

            // Crear el mensaje
            $message = Message::create([
                'sender_id' => Auth::id(),
                'receiver_id' => $admin->id,
                'message' => $request->message,
                'subject' => $request->subject,
                'type' => 'user_to_admin',
                'conversation_id' => $conversation->id,
            ]);

            // Actualizar última fecha de mensaje
            $conversation->update(['last_message_at' => now()]);

            DB::commit();

            return redirect()->route('messages.show', $conversation)
                ->with('success', 'Mensaje enviado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al crear mensaje: ' . $e->getMessage());
            
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
    public function sendMessage(Request $request, Conversation $conversation)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Verificar que el usuario es el propietario
        if ($conversation->user_id !== $user->id) {
            abort(403, 'No tienes permisos para enviar mensajes en esta conversación');
        }

        $request->validate([
            'message' => 'required|string|min:10',
        ], [
            'message.required' => 'El mensaje es obligatorio',
            'message.min' => 'El mensaje debe tener al menos 10 caracteres',
        ]);

        try {
            DB::beginTransaction();

            // Crear el mensaje
            $message = Message::create([
                'sender_id' => $user->id,
                'receiver_id' => $conversation->admin_id,
                'message' => $request->message,
                'subject' => $conversation->subject,
                'type' => 'user_to_admin',
                'conversation_id' => $conversation->id,
            ]);

            // Actualizar última fecha de mensaje
            $conversation->update(['last_message_at' => now()]);

            DB::commit();

            return redirect()->route('messages.show', $conversation)
                ->with('success', 'Mensaje enviado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al enviar mensaje: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Hubo un error al enviar tu mensaje. Por favor, intenta nuevamente.')
                ->withInput();
        }
    }
}
