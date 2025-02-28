<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Intentamos enviar el enlace de restablecimiento de contraseña
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Si el enlace fue enviado correctamente
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        // Si el error es "throttled" (demasiados intentos)
        if ($status == Password::THROTTLED) {
            return back()->withErrors(['email' => __('Demasiados intentos. Por favor inténtalo más tarde en :seconds segundos.', ['seconds' => 60])]);
        }

        // Si hubo otro error
        return back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
    }
}
