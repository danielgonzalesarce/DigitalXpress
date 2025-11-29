<?php

/**
 * Admin\MessageController
 * 
 * Controlador para el sistema de mensajería del administrador.
 * Permite a los administradores:
 * - Ver todas las conversaciones con usuarios
 * - Responder mensajes de usuarios
 * - Ver mensajes no leídos
 * - Gestionar conversaciones
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Mostrar todas las conversaciones para administradores
     * 
     * Muestra todas las conversaciones ordenadas por fecha del último mensaje,
     * con información de mensajes no leídos.
     * 
     * @return \Illuminate\View\View Vista de conversaciones
     */
    public function index()
    {
        $admin = Auth::user();

        // Obtener todas las conversaciones donde el admin está involucrado
        $conversations = Conversation::where('admin_id', $admin->id)
            ->with(['user', 'messages' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Contar mensajes no leídos para el admin
        $unreadCount = Message::where('receiver_id', $admin->id)
            ->where('is_read', false)
            ->count();

        // Contar mensajes no leídos por conversación
        foreach ($conversations as $conversation) {
            $conversation->unread_count = Message::where('conversation_id', $conversation->id)
                ->where('receiver_id', $admin->id)
                ->where('is_read', false)
                ->count();
        }

        return view('admin.messages.index', compact('conversations', 'unreadCount'));
    }

    /**
     * Mostrar una conversación específica
     * 
     * Muestra todos los mensajes de una conversación y permite
     * responder al usuario.
     * 
     * @param Conversation $conversation La conversación a mostrar
     * @return \Illuminate\View\View Vista de la conversación
     */
    public function show(Conversation $conversation)
    {
        $admin = Auth::user();

        // Verificar que el admin es el propietario de la conversación
        if ($conversation->admin_id !== $admin->id) {
            abort(403, 'No tienes permisos para ver esta conversación');
        }

        // Marcar mensajes como leídos
        Message::where('conversation_id', $conversation->id)
            ->where('receiver_id', $admin->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // Cargar mensajes y relaciones
        $conversation->load(['messages.sender', 'messages.receiver', 'admin', 'user']);

        return view('admin.messages.show', compact('conversation'));
    }

    /**
     * Responder a un mensaje en una conversación
     * 
     * @param Request $request Datos del formulario
     * @param Conversation $conversation La conversación donde responder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reply(Request $request, Conversation $conversation)
    {
        $admin = Auth::user();

        // Verificar que el admin es el propietario
        if ($conversation->admin_id !== $admin->id) {
            abort(403, 'No tienes permisos para responder en esta conversación');
        }

        $request->validate([
            'message' => 'required|string|min:10',
        ], [
            'message.required' => 'El mensaje es obligatorio',
            'message.min' => 'El mensaje debe tener al menos 10 caracteres',
        ]);

        try {
            DB::beginTransaction();

            // Crear el mensaje de respuesta
            $message = Message::create([
                'sender_id' => $admin->id,
                'receiver_id' => $conversation->user_id,
                'message' => $request->message,
                'subject' => 'Re: ' . $conversation->subject,
                'type' => 'admin_to_user',
                'conversation_id' => $conversation->id,
            ]);

            // Actualizar última fecha de mensaje
            $conversation->update(['last_message_at' => now()]);

            DB::commit();

            return redirect()->route('admin.messages.show', $conversation)
                ->with('success', 'Mensaje enviado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al responder mensaje: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Hubo un error al enviar tu respuesta. Por favor, intenta nuevamente.')
                ->withInput();
        }
    }

    /**
     * Marcar mensajes como leídos
     * 
     * @param Conversation $conversation La conversación a marcar como leída
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Conversation $conversation)
    {
        $admin = Auth::user();

        if ($conversation->admin_id !== $admin->id) {
            abort(403, 'No tienes permisos para esta acción');
        }

        Message::where('conversation_id', $conversation->id)
            ->where('receiver_id', $admin->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Mensajes marcados como leídos');
    }
}
