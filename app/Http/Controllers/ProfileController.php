<?php

namespace App\Http\Controllers;

use App\Models\Tarifa;  // Asegúrate de importar el modelo Tarifa
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Horario;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // Mostrar formulario de perfil
    public function edit(Request $request): View
    {
        $user = $request->user();
        $tarifaVencida = false;

        // Verificamos si la tarifa ha vencido
        if ($user->tarifa_id && $user->fecha_expiracion < now()) {
            // Si la tarifa ha vencido, eliminamos la relación de tarifa
            $user->tarifa_id = null;
            $user->fecha_inicio = null;
            $user->fecha_expiracion = null;
            $user->clases = []; // Limpiamos las clases asociadas
            $user->save();

            // Marcamos la tarifa como vencida
            $tarifaVencida = true;
        }

        return view('profile.edit', [
            'user' => $user,
            'tarifaVencida' => $tarifaVencida,
        ]);
    }

    // Actualizar información del perfil
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'regex:/^[\w\.-]+@[\w\.-]+\.[a-zA-Z]+$/'], 
        ], [
            'email.regex' => 'El correo electrónico debe tener el formato correcto (ejemplo@dominio.com).',
        ]);

        if ($validator->fails()) {
            return redirect()->route('profile.edit')
                             ->withErrors($validator)
                             ->withInput();
        }

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    // Cambiar tarifa
    public function cambiarTarifa(Request $request)
    {
        $user = Auth::user();

        // Verificamos que el usuario sea un cliente
        if ($user->tipo_usuario !== 'cliente') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        // Verificamos si la tarifa actual ha vencido
        if ($user->tarifa_id && $user->fecha_expiracion >= now()) {
            return redirect()->route('profile.edit')->with('error', 'No puedes cambiar la tarifa hasta que la actual haya vencido.');
        }

        $tarifa = Tarifa::findOrFail($request->tarifa_id);

        // Si la tarifa anterior ha vencido, eliminamos la relación de tarifa
        if ($user->tarifa_id && $user->fecha_expiracion < now()) {
            $user->tarifa_id = null;
            $user->fecha_inicio = null;
            $user->fecha_expiracion = null;
            $user->clases = []; // Limpiamos las clases asociadas
        }

        // Establecemos la nueva tarifa
        $fechaExpiracion = now()->addDays($tarifa->duracion); // Fecha de expiración calculada
        $user->tarifa_id = $tarifa->id;
        $user->fecha_inicio = now();
        $user->fecha_expiracion = $fechaExpiracion;

        // Guardamos la tarifa antes de redirigir
        $user->save();

        // Si la tarifa es personalizada, redirigimos al usuario para que elija clases
        if ($tarifa->nombre == "Tarifa Personalizada") {
            return redirect()->route('perfil.elegir-clases');
        }

        // Si no es tarifa personalizada, asignamos las clases correspondientes
        $clasesDisponibles = Horario::all()->pluck('clase')->unique()->toArray();
        $user->clases = $clasesDisponibles;
        $user->save();

        // Redirigimos a la página de perfil con un mensaje de éxito
        return redirect()->back()->with('success', 'Tarifa cambiada correctamente');
    }

    // Mostrar clases disponibles
    public function mostrarClases()
    {
        $user = Auth::user();

        if ($user->tipo_usuario !== 'cliente' || $user->tarifa_id == null) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }else if($user->clases){
            return view('error-clases');
        }

        return view('profile.elegir-clases');
    }

    // Guardar clases seleccionadas
    public function guardarClases(Request $request)
    {
        $user = Auth::user();

        // Verificamos que el usuario sea un cliente
        if ($user->tipo_usuario !== 'cliente') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        // Validamos las clases seleccionadas
        $clasesSeleccionadas = $request->clases ?? [];
        $totalClases = Horario::all()->unique('clase')->count();
        $minimoClases = 1;
        $maximoClases = $totalClases - 1;

        // Verificamos que se haya seleccionado al menos 1 clase
        if (empty($clasesSeleccionadas) || count($clasesSeleccionadas) < $minimoClases) {
            return redirect()->route('perfil.elegir-clases')
                            ->withErrors(['clases' => 'Debes seleccionar al menos 1 clase.']);
        }

        // Verificamos que no se hayan seleccionado más clases de las permitidas
        if (count($clasesSeleccionadas) > $maximoClases) {
            return redirect()->route('perfil.elegir-clases')
                            ->withErrors(['clases' => 'No puedes seleccionar más de ' . $maximoClases . ' clases.']);
        }

        // Guardamos las clases seleccionadas directamente en el usuario
        $user->clases = $clasesSeleccionadas;
        $user->save();

        // Redirigimos a la página de perfil con un mensaje de éxito
        return redirect()->route('profile.edit')->with('success', 'Clases guardadas correctamente');
    }

    // Eliminar cuenta
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'La contraseña es obligatoria.',
            'password.current_password' => 'La contraseña no es correcta.',
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

}
