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
        // Validar email
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Intentar enviar el enlace de restablecimiento
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Si el enlace fue enviado correctamente
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __('Se ha enviado el enlace para restablecer la contraseña.'));
        }

        // Si hubo un error con el email (por ejemplo, no existe en la base de datos)
        return back()->withInput($request->only('email'))
                    ->withErrors(['email' => __('No se encuentra ningún usuario con ese correo electrónico.')]);
    }

}
