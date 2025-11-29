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
    /**
     * Mostrar todas las conversaciones para administradores
     * 
     * Muestra todas las conversaciones donde el administrador está involucrado,
     * ordenadas por fecha del último mensaje (más recientes primero).
     * Incluye contador de mensajes no leídos por conversación y total.
     * 
     * @return \Illuminate\View\View Vista de conversaciones del administrador
     */
    public function index()
    {
        $admin = Auth::user();

        // Obtener todas las conversaciones donde este admin está asignado
        $conversations = Conversation::where('admin_id', $admin->id)
            ->with(['user', 'messages' => function($query) {
                // Cargar mensajes ordenados por fecha (más recientes primero)
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('last_message_at', 'desc') // Ordenar conversaciones por último mensaje
            ->get();

        // Contar total de mensajes no leídos para este administrador
        $unreadCount = Message::where('receiver_id', $admin->id)
            ->where('is_read', false)
            ->count();

        // Agregar contador de mensajes no leídos a cada conversación
        // Esto permite mostrar badges en la vista
        foreach ($conversations as $conversation) {
            $conversation->unread_count = Message::where('conversation_id', $conversation->id)
                ->where('receiver_id', $admin->id)      // Solo mensajes recibidos por el admin
                ->where('is_read', false)                // Solo no leídos
                ->count();
        }

        // Retornar vista con conversaciones y contador total
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
    /**
     * Mostrar una conversación específica para el administrador
     * 
     * Muestra todos los mensajes de una conversación y permite responder al usuario.
     * Automáticamente marca los mensajes como leídos cuando el admin los ve.
     * 
     * @param Conversation $conversation La conversación a mostrar
     * @return \Illuminate\View\View Vista de la conversación con mensajes
     */
    public function show(Conversation $conversation)
    {
        $admin = Auth::user();

        // Verificar que el admin es el asignado a esta conversación
        // Solo el administrador asignado puede ver y responder
        if ($conversation->admin_id !== $admin->id) {
            abort(403, 'No tienes permisos para ver esta conversación');
        }

        // Marcar todos los mensajes no leídos como leídos cuando el admin abre la conversación
        Message::where('conversation_id', $conversation->id)
            ->where('receiver_id', $admin->id)      // Solo mensajes recibidos por el admin
            ->where('is_read', false)                // Solo los que no están leídos
            ->update([
                'is_read' => true,                  // Marcar como leído
                'read_at' => now(),                 // Guardar fecha de lectura
            ]);

        // Cargar todas las relaciones necesarias para la vista
        $conversation->load(['messages.sender', 'messages.receiver', 'admin', 'user']);

        // Mostrar vista con la conversación completa
        return view('admin.messages.show', compact('conversation'));
    }

    /**
     * Responder a un mensaje en una conversación
     * 
     * @param Request $request Datos del formulario
     * @param Conversation $conversation La conversación donde responder
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Responder a un mensaje en una conversación
     * 
     * Permite al administrador responder a los mensajes de los usuarios.
     * El mensaje se envía al usuario que inició la conversación.
     * 
     * @param Request $request Datos del formulario (message)
     * @param Conversation $conversation La conversación donde responder
     * @return \Illuminate\Http\RedirectResponse Redirección a la conversación
     */
    public function reply(Request $request, Conversation $conversation)
    {
        $admin = Auth::user();

        // Verificar que el admin es el asignado a esta conversación
        // Solo el administrador asignado puede responder
        if ($conversation->admin_id !== $admin->id) {
            abort(403, 'No tienes permisos para responder en esta conversación');
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

            // Crear mensaje de respuesta del administrador al usuario
            $message = Message::create([
                'sender_id' => $admin->id,                    // Administrador envía el mensaje
                'receiver_id' => $conversation->user_id,     // Usuario recibe el mensaje
                'message' => $request->message,              // Contenido de la respuesta
                'subject' => 'Re: ' . $conversation->subject, // Asunto con prefijo "Re:"
                'type' => 'admin_to_user',                    // Tipo: de administrador a usuario
                'conversation_id' => $conversation->id,       // ID de la conversación
            ]);

            // Actualizar fecha del último mensaje para ordenar conversaciones
            $conversation->update(['last_message_at' => now()]);

            // Confirmar transacción
            DB::commit();

            // Redirigir a la conversación con mensaje de éxito
            return redirect()->route('admin.messages.show', $conversation)
                ->with('success', 'Mensaje enviado exitosamente');

        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            DB::rollBack();
            // Registrar error en logs
            \Log::error('Error al responder mensaje: ' . $e->getMessage());
            
            // Redirigir con mensaje de error
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
    /**
     * Marcar todos los mensajes de una conversación como leídos
     * 
     * Permite al administrador marcar manualmente todos los mensajes
     * de una conversación como leídos sin tener que abrir cada uno.
     * 
     * @param Conversation $conversation La conversación a marcar como leída
     * @return \Illuminate\Http\RedirectResponse Redirección a la página anterior
     */
    public function markAsRead(Conversation $conversation)
    {
        $admin = Auth::user();

        // Verificar que el admin es el asignado a esta conversación
        if ($conversation->admin_id !== $admin->id) {
            abort(403, 'No tienes permisos para esta acción');
        }

        // Marcar todos los mensajes no leídos como leídos
        Message::where('conversation_id', $conversation->id)
            ->where('receiver_id', $admin->id)      // Solo mensajes recibidos por el admin
            ->where('is_read', false)                // Solo los que no están leídos
            ->update([
                'is_read' => true,                   // Marcar como leído
                'read_at' => now(),                 // Guardar fecha de lectura
            ]);

        // Redirigir a la página anterior con mensaje de éxito
        return redirect()->back()->with('success', 'Mensajes marcados como leídos');
    }
}
