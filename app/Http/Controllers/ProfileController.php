<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Validar el email con un patrón personalizado
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]+$/'],  // Validación con expresión regular
        ], [
            'email.regex' => 'El correo electrónico debe tener el formato correcto (ejemplo@dominio.com).',
        ]);

        // Si la validación falla, redirigir con errores
        if ($validator->fails()) {
            return redirect()->route('profile.edit')
                             ->withErrors($validator)
                             ->withInput();
        }

        // Rellenar la información del usuario
        $request->user()->fill($request->validated());

        // Si el email ha cambiado, se debe restablecer la verificación
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Guardar el usuario
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validar la contraseña antes de eliminar la cuenta
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Obtener el usuario y proceder con el cierre de sesión y eliminación
        $user = $request->user();

        Auth::logout();

        $user->delete();

        // Invalidar la sesión
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
