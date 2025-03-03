<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    // Mensajes comunes de validación
    protected function validationMessages()
    {
        return [
            'titulo.required' => 'El campo título es obligatorio.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'fecha.required' => 'La fecha del evento es obligatoria.',
            'fecha.date' => 'Debe ser una fecha válida.',
            'hora.required' => 'La hora del evento es obligatoria.',
            'hora.date_format' => 'Debe ser un formato de hora válido.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'imagen.mimes' => 'La imagen debe ser de tipo jpg, png o jpeg.',
        ];
    }

    // Mostrar todos los eventos
    public function index()
    {
        $eventos = Evento::orderBy('fecha', 'desc')->orderBy('hora', 'desc')->paginate(6);
        return view('eventos.index', compact('eventos'));
    }

    // Mostrar formulario para crear un nuevo evento
    public function create()
    {
        return view('eventos.create');
    }

    // Guardar un nuevo evento
    public function store(Request $request)
    {
        // Validación de datos
        $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,png,jpeg'],
        ], $this->validationMessages());

        // Subir imagen si existe
        $imagenPath = $request->file('imagen') ? $request->file('imagen')->store('eventos', 'public') : null;

        // Crear evento
        Evento::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'imagen' => $imagenPath,
        ]);

        return redirect()->route('eventos.index')->with('success', 'Evento creado con éxito.');
    }

    // Mostrar el formulario para editar un evento
    public function edit($id)
    {
        $evento = Evento::findOrFail($id);
        return view('eventos.edit', compact('evento'));
    }

    // Actualizar un evento
    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);
        
        // Validación de datos
        $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'fecha' => ['required', 'date'],
            'hora' => ['required', 'date_format:H:i'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,png,jpeg'],
        ], $this->validationMessages());

        // Verificar si se marca el checkbox para eliminar la imagen
        if ($request->has('eliminar_imagen') && $request->eliminar_imagen == 'on') {
            // Eliminar la imagen actual
            if ($evento->imagen) {
                Storage::disk('public')->delete($evento->imagen);
            }
            $imagenPath = null; // No hay nueva imagen
        } else {
            // Subir nueva imagen si existe
            $imagenPath = $request->hasFile('imagen') ? $request->file('imagen')->store('eventos', 'public') : $evento->imagen;
        }

        // Actualizar evento
        $evento->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'imagen' => $imagenPath,
        ]);

        return redirect()->route('eventos.index')->with('success', 'Evento actualizado con éxito.');
    }

    // Eliminar un evento
    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);
        $evento->delete();

        return redirect()->route('eventos.index')->with('success', 'Evento eliminado con éxito.');
    }

}
