<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     * Redirige al home ya que el login se maneja mediante modal.
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('home');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Verificar si el usuario es administrador (email @digitalxpress.com)
        $user = Auth::user();
        $emailParts = explode('@', $user->email);
        $emailDomain = isset($emailParts[1]) ? strtolower(trim($emailParts[1])) : '';
        
        // Si es admin, redirigir al panel de administración
        if ($emailDomain === 'digitalxpress.com') {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        // Si no es admin, redirigir al home normal
        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
