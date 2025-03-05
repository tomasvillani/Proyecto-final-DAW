<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;

class HorarioController extends Controller
{
    // Mostrar los horarios
    public function cargar_horarios()
    {
        $horarios = Horario::all();
        $dias = Horario::DIAS;
        return view('classes', compact('horarios', 'dias'));
    }

    // Cambiar disponibilidad de la clase
    public function cambiarDisponibilidad(Request $request, $id)
    {
        // Buscar el horario por ID
        $horario = Horario::findOrFail($id);

        // Cambiar el valor del campo disponible
        $horario->disponible = $request->input('disponible');
        $horario->save();

        // Devolver una respuesta en formato JSON
        return response()->json([
            'success' => true,
            'disponible' => $horario->disponible
        ]);
    }
}
