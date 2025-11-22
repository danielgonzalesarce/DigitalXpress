<?php

/**
 * ProfileController
 * 
 * Controlador para la gestión del perfil de usuario.
 * Permite a los usuarios autenticados:
 * - Ver y editar su información personal
 * - Actualizar su perfil
 * - Eliminar su cuenta
 * 
 * Todas las operaciones requieren autenticación.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Mostrar formulario de edición del perfil
     * 
     * Muestra el formulario con los datos actuales del usuario autenticado.
     * 
     * @param Request $request Petición HTTP
     * @return View Vista del formulario de edición
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(), // Obtener usuario autenticado
        ]);
    }

    /**
     * Actualizar información del perfil del usuario
     * 
     * Valida y actualiza los datos del perfil del usuario.
     * Si el email cambia, marca el email como no verificado.
     * 
     * @param ProfileUpdateRequest $request Request con validación personalizada
     * @return RedirectResponse Redirige al formulario con mensaje de éxito
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Llenar el modelo con los datos validados
        $request->user()->fill($request->validated());

        // Si el email cambió, marcar como no verificado
        // Esto requiere que el usuario verifique el nuevo email
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Guardar los cambios en la base de datos
        $request->user()->save();

        return Redirect::route('profile.edit')->with('success', 'Perfil actualizado exitosamente');
    }

    /**
     * Eliminar la cuenta del usuario
     * 
     * Requiere confirmación con la contraseña actual.
     * Elimina permanentemente la cuenta y todos sus datos asociados.
     * 
     * @param Request $request Petición HTTP con contraseña de confirmación
     * @return RedirectResponse Redirige al home después de eliminar
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $userName = $user->name;

        // Eliminar datos relacionados
        $user->cartItems()->delete();
        $user->orders()->delete();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('success', "Cuenta de {$userName} eliminada exitosamente");
    }
}
