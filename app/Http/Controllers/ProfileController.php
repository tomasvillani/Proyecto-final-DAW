<?php

namespace App\Http\Controllers;

use App\Models\Tarifa;  // Asegúrate de importar el modelo Tarifa
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Horario;
use Carbon\Carbon;
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

        if (!$user->metodo_pago) {
            return view('profile.sin-metodo-pago');
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

    public function actualizarMetodoPago(Request $request)
    {
        $user = Auth::user();

        // Verificamos que el usuario sea un cliente
        if ($user->tipo_usuario !== 'cliente') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        // Validaciones dinámicas según el método de pago seleccionado
        $rules = [
            'metodo_pago' => 'required|in:tarjeta,cuenta_bancaria',
        ];

        if ($request->metodo_pago === 'tarjeta') {
            // Limpiar espacios antes de validar
            $numeroTarjeta = str_replace(' ', '', $request->numero_tarjeta);
            
            // Reemplazar el valor limpio para la validación
            $request->merge(['numero_tarjeta' => $numeroTarjeta]);

            $rules['numero_tarjeta'] = 'required|digits_between:13,19|regex:/^\d+$/';
            $rules['fecha_caducidad'] = 'required|date|after_or_equal:' . now()->format('Y-m');
            $rules['cvv'] = 'required|digits:3|regex:/^\d+$/'; // Validar el CVV (3 dígitos)
        } elseif ($request->metodo_pago === 'cuenta_bancaria') {
            // Limpiar espacios antes de validar el IBAN
            $iban = str_replace(' ', '', $request->cuenta_bancaria);
            $request->merge(['cuenta_bancaria' => $iban]);

            // Expresión regular para IBAN (debe aceptar entre 4 y 30 caracteres alfanuméricos después del código de país y los 2 dígitos de control)
            $rules['cuenta_bancaria'] = 'required|regex:/^[A-Z]{2}\d{2}[A-Z0-9]{4,30}$/';
        }

        $messages = [
            'metodo_pago.required' => 'Debe seleccionar un método de pago.',
            'metodo_pago.in' => 'El método de pago debe ser tarjeta o cuenta bancaria.',
            'numero_tarjeta.required' => 'Debe ingresar el número de tarjeta.',
            'numero_tarjeta.digits_between' => 'El número de tarjeta debe tener entre 13 y 19 dígitos.',
            'numero_tarjeta.regex' => 'El número de tarjeta debe contener solo números.',
            'fecha_caducidad.required' => 'Debe ingresar la fecha de caducidad.',
            'fecha_caducidad.date' => 'La fecha de caducidad no es válida.',
            'fecha_caducidad.after_or_equal' => 'La fecha de caducidad no puede ser anterior al mes actual.',
            'cvv.required' => 'Debe ingresar el CVV.',
            'cvv.digits' => 'El CVV debe tener 3 dígitos.',
            'cvv.regex' => 'El CVV debe contener solo números.',
            'cuenta_bancaria.required' => 'Debe ingresar la cuenta bancaria.',
            'cuenta_bancaria.regex' => 'El formato de la cuenta bancaria no es válido.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        // Validar tarjeta con el algoritmo de Luhn si se elige como método de pago
        if ($request->metodo_pago === 'tarjeta' && !$this->validarTarjetaLuhn($request->numero_tarjeta)) {
            $validator->errors()->add('numero_tarjeta', 'El número de tarjeta no es válido.');
        }

        // Validar cuenta bancaria (IBAN)
        if ($request->metodo_pago === 'cuenta_bancaria' && !$this->validarIBAN($request->cuenta_bancaria)) {
            $validator->errors()->add('cuenta_bancaria', 'El número de cuenta bancaria no es válido.');
        }

        if ($validator->fails()) {
            return redirect()->route('profile.edit')->withErrors($validator)->withInput();
        }

        // Convertir la fecha de caducidad a un formato adecuado 'YYYY-MM-01' si el método de pago es tarjeta
        if ($request->metodo_pago === 'tarjeta') {
            $fechaCaducidad = Carbon::createFromFormat('Y-m', $request->fecha_caducidad)->startOfMonth();
            $user->fecha_caducidad = $fechaCaducidad;
        }

        // Guardar los datos en la base de datos
        $user->metodo_pago = $request->metodo_pago;
        $user->numero_tarjeta = $request->metodo_pago === 'tarjeta' ? $request->numero_tarjeta : null;
        $user->cvv = $request->metodo_pago === 'tarjeta' ? $request->cvv : null;
        $user->cuenta_bancaria = $request->metodo_pago === 'cuenta_bancaria' ? $request->cuenta_bancaria : null;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Método de pago actualizado correctamente.');
    }

    // Algoritmo de Luhn para validar tarjeta de crédito
    public function validarTarjetaLuhn($numero)
    {

        $numero = str_replace(' ', '', $numero);
        $sum = 0;
        $alt = false;
        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $n = (int) $numero[$i];
            if ($alt) {
                $n *= 2;
                if ($n > 9) {
                    $n -= 9;
                }
            }
            $sum += $n;
            $alt = !$alt;
        }
        return $sum % 10 === 0;
    }

    // Validar IBAN con estructura y checksum
    public function validarIBAN($iban)
    {

        $user = Auth::user();

        $iban = strtoupper(str_replace(' ', '', $iban));
        if (!preg_match('/^[A-Z]{2}\d{2}[A-Z0-9]{11,30}$/', $iban)) {
            return false;
        }
        $ibanReordenado = substr($iban, 4) . substr($iban, 0, 4);
        $ibanNumerico = '';
        foreach (str_split($ibanReordenado) as $char) {
            $ibanNumerico .= is_numeric($char) ? $char : (ord($char) - 55);
        }
        return bcmod($ibanNumerico, '97') === '1';
    }
}
