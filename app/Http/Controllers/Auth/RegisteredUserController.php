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
                ->with('register_error', true)
                ->withErrors([
                    'email' => 'Los usuarios con dominio @digitalxpress.com solo pueden ser creados por el administrador desde el panel administrativo. Por favor, utiliza un email con dominio @gmail.com para registrarte.',
                ]);
        }

        // Solo permitir registro con @gmail.com
        if ($emailDomain !== 'gmail.com') {
            return redirect()->back()
                ->withInput($request->only('name', 'email'))
                ->with('register_error', true)
                ->withErrors([
                    'email' => 'Solo se permiten registros con direcciones de correo @gmail.com.',
                ]);
        }

        try {
            $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'name.string' => 'El nombre debe ser texto.',
                'name.max' => 'El nombre no puede tener más de 255 caracteres.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.string' => 'El correo electrónico debe ser texto.',
                'email.email' => 'El correo electrónico debe ser una dirección válida.',
                'email.unique' => 'Este correo electrónico ya está registrado.',
                'password.required' => 'La contraseña es obligatoria.',
                'password.confirmed' => 'Las contraseñas no coinciden.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Traducir mensajes de validación de contraseña a español
            $errors = $e->errors();
            if (isset($errors['password'])) {
                foreach ($errors['password'] as $key => $message) {
                    if (str_contains($message, 'The password')) {
                        $errors['password'][$key] = str_replace(
                            ['The password', 'must be at least', 'characters'],
                            ['La contraseña', 'debe tener al menos', 'caracteres'],
                            $message
                        );
                    }
                }
            }
            
            return redirect()->back()
                ->withInput($request->only('name', 'email'))
                ->with('register_error', true)
                ->withErrors($errors);
        }

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
