<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactoMailable;

class ContactoController extends Controller
{
    public function enviarFormulario(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        // Enviar el correo
        Mail::to('gymtinajo@gmail.com')->send(new ContactoMailable($request->all()));

        return back()->with('success', 'Correo enviado correctamente');
    }

    public function inscribirse(Request $request)
    {
        // Validación de los datos
        $request->validate([
            'email' => 'required|email|max:255'
        ]);

        $datos = [
            'email' => $request->email,  // Ejemplo de lo que puede ser 'datos'
            'tipo' => 'inscripcion',  // Indicar que es una inscripción
        ];

        // Enviar el correo
        Mail::to($request->email)->send(new ContactoMailable($datos));

        return back()->with('success', 'Correo enviado correctamente');
    }
}
