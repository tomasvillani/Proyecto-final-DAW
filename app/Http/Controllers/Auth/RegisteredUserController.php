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
        // Mensajes personalizados
        $messages = [
            'dni.required' => 'El campo DNI es obligatorio.',
            'dni.unique' => 'El DNI ingresado ya está registrado.',
            'dni.dni_espanol' => 'El DNI ingresado no es válido.',
            'name.required' => 'El campo nombre es obligatorio.',
            'surname.required' => 'El campo apellido es obligatorio.',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'email.regex' => 'El correo electrónico debe tener el formato texto@texto.dominio.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ];
        
        // Validación de datos con mensajes personalizados
        $request->validate([
            'dni' => ['required', 'string', 'unique:users', 'dni_espanol'],
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'lowercase',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]+$/',
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], $messages);

        // Creación del usuario
        $user = new User();
        $user->dni = $request->dni;
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->tipo_usuario = 'cliente';
        $user->password = Hash::make($request->password);
        
        $user->save();

        // Evento de registro
        event(new Registered($user));

        // Iniciar sesión del usuario
        Auth::login($user);

        // Redirigir a la página del dashboard
        return redirect(route('dashboard', absolute: false));
    }

}

