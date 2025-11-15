<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar el dominio del email antes de la validación estándar
        $email = strtolower(trim($request->email));
        $emailParts = explode('@', $email);
        $emailDomain = isset($emailParts[1]) ? strtolower(trim($emailParts[1])) : '';

        // No permitir registro con @digitalxpress.com
        if ($emailDomain === 'digitalxpress.com') {
            return redirect()->back()
                ->withInput($request->only('name', 'email'))
                ->withErrors([
                    'email' => 'Los usuarios con dominio @digitalxpress.com solo pueden ser creados por el administrador desde el panel administrativo. Por favor, utiliza un email con dominio @gmail.com para registrarte.',
                ]);
        }

        // Solo permitir registro con @gmail.com
        if ($emailDomain !== 'gmail.com') {
            return redirect()->back()
                ->withInput($request->only('name', 'email'))
                ->withErrors([
                    'email' => 'Solo se permiten registros con direcciones de correo @gmail.com.',
                ]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirigir al home normal
        return redirect(route('home', absolute: false));
    }
}
