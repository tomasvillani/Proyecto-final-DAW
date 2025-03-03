<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ClienteController extends Controller
{
    // Mensajes comunes de validación
    protected function validationMessages()
    {
        return [
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
    }

    // Mostrar todos los clientes
    public function index()
    {
        $clientes = User::where('tipo_usuario', 'cliente')->get(); // Solo clientes
        return view('clientes.index', compact('clientes'));
    }

    // Mostrar formulario para crear un nuevo cliente
    public function create()
    {
        return view('clientes.create');
    }

    // Guardar un nuevo cliente
    public function store(Request $request)
    {
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
        ], $this->validationMessages());

        // Crear cliente
        User::create([
            'dni' => $request->dni,
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'tipo_usuario' => 'cliente', // Establecer tipo cliente
            'password' => Hash::make($request->password), // Encriptar contraseña
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado con éxito.');
    }

    // Mostrar el formulario para editar un cliente
    public function edit($id)
    {
        $cliente = User::findOrFail($id);
        if ($cliente->tipo_usuario != 'cliente') {
            return redirect()->route('clientes.index')->with('error', 'Este no es un cliente válido.');
        }

        return view('clientes.edit', compact('cliente'));
    }

    // Actualizar un cliente
    public function update(Request $request, $id)
    {
        // Validación de datos con mensajes personalizados
        $request->validate([
            'dni' => ['required', 'string', 'unique:users,dni,' . $id, 'dni_espanol'],
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'lowercase',
                'max:255',
                'unique:users,email,' . $id,
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]+$/',
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ], $this->validationMessages());

        $cliente = User::findOrFail($id);
        $cliente->update([
            'dni' => $request->dni,
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $cliente->password, // Solo actualizar si se proporciona
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado con éxito.');
    }

    // Eliminar un cliente
    public function destroy($id)
    {
        $cliente = User::findOrFail($id);

        if ($cliente->tipo_usuario != 'cliente') {
            return redirect()->route('clientes.index')->with('error', 'Este no es un cliente válido.');
        }

        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado con éxito.');
    }

    public function show($id)
    {
        // Obtener el cliente por su ID
        $cliente = User::findOrFail($id);

        // Pasar el cliente a la vista
        return view('clientes.show', compact('cliente'));
    }
}
