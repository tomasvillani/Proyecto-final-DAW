<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Horario;

class ReservaController extends Controller
{
    // Método privado para verificar el usuario y tipo de usuario
    private function authorizeCliente($userId)
    {
        // Verificar que el usuario autenticado es el mismo que el userId
        if (Auth::id() != (int) $userId) {
            abort(403, 'No tienes permisos para ver las reservas de otro usuario.');
        }

        // Verificar si el usuario autenticado es del tipo 'cliente'
        $user = Auth::user();
        if ($user->tipo_usuario != 'cliente') {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }
    }

    // Mostrar reservas de un usuario
    public function index($userId)
    {
        $this->authorizeCliente($userId); // Validación de usuario y tipo

        $usuario = User::findOrFail($userId);
        $tarifa = $usuario->tarifa;
        if (!$tarifa) {
            return redirect()->route('sin-tarifa');
        }

        $reservas = $usuario->reservas;
        return view('reservas.index', compact('reservas', 'usuario'));
    }

    // Mostrar formulario para crear una nueva reserva
    public function create($userId)
    {
        $this->authorizeCliente($userId); // Validación de usuario y tipo

        $usuario = User::findOrFail($userId);
        
        // Verificar si el usuario tiene tarifa asociada
        $tarifa = $usuario->tarifa;
        if (!$tarifa) {
            return redirect()->route('sin-tarifa');
        }

        return view('reservas.create', compact('usuario', 'tarifa'));
    }

    // Guardar una nueva reserva
    public function store(Request $request)
    {
        $this->authorizeCliente($request->user_id); // Validación de usuario y tipo
        
        $request->validate([
            'clase' => 'required|string',
            'dia' => 'required|date|after_or_equal:' . now()->toDateString(),
            'hora' => 'required|string',  // Esta es la hora de inicio que el usuario selecciona
        ]);

        // Guardar reserva en la base de datos
        Reserva::create([
            'user_id' => $request->user_id,
            'clase' => $request->clase,
            'dia' => $request->dia,
            'hora' => $request->hora, // Se está guardando la hora de inicio
        ]);

        return redirect()->route('mis-reservas.index', $request->user_id)->with('success', 'Reserva realizada correctamente.');
    }

    // Eliminar una reserva
    public function destroy($id)
    {
        $reserva = Reserva::findOrFail($id);
        $this->authorizeCliente($reserva->user_id); // Validación de usuario y tipo
        
        $reserva->delete();
        return redirect()->route('mis-reservas.index', $reserva->user_id)->with('success', 'Reserva eliminada con éxito.');
    }

    public function getDiasDisponibles(Request $request)
    {

        try {
            $clase = $request->clase;
            $hoy = now();  // Día actual

            $diasDisponibles = [];

            // Buscar los próximos 7 días para la clase seleccionada
            for ($i = 0; $i < 7; $i++) {
                $diaSemanaEspañol = $hoy->locale('es')->dayName;  // Obtener el día de la semana en español
                $fecha = $hoy->format('Y-m-d');  // La fecha en formato YYYY-MM-DD

                // Verificar si la clase tiene horarios para este día
                $horarios = $this->obtenerHorariosPorDia($diaSemanaEspañol);

                foreach ($horarios as $horario) {
                    if ($horario['clase'] == $clase) {
                        // Se añade la fecha, pero se usa el nombre del día
                        $diasDisponibles[] = [
                            'nombreDia' => $diaSemanaEspañol,  // Lunes, Martes, etc.
                            'fecha' => $fecha,  // La fecha completa (será utilizada para el registro)
                        ];
                        break;
                    }
                }
                $hoy->addDay();
            }

            // Si no hay días disponibles, devolver mensaje de error
            if (count($diasDisponibles) == 0) {
                return response()->json(['message' => 'No hay días disponibles para la clase'], 404);
            }

            return response()->json(['dias' => $diasDisponibles]);

        } catch (\Exception $e) {
            // Error en el servidor
            return response()->json(['error' => 'Hubo un error en el servidor', 'details' => $e->getMessage()], 500);
        }
    }

    // Método para obtener los horarios de una clase en un día específico
    private function obtenerHorariosPorDia($diaSemana)
    {
        // Consultar la tabla 'horarios' para obtener los horarios de las clases para el día específico
        $horarios = Horario::where('dia', $diaSemana)->get(); // Filtramos por el día de la semana (en español)

        // Si no se encuentran horarios, retornamos un array vacío
        if ($horarios->isEmpty()) {
            return [];
        }

        $horariosArray = [];

        // Convertimos el resultado en un array
        foreach ($horarios as $horario) {
            $horariosArray[] = [
                'clase' => $horario->clase,
                'hora_inicio' => $horario->hora_inicio,
                'hora_fin' => $horario->hora_fin
            ];
        }

        return $horariosArray;
    }

    // Método para normalizar el día a minúsculas sin acento
    private function normalizarDia($dia)
    {
        // Mapeo de los días en español
        $diasConAcento = [
            'Lunes' => 'lunes',
            'Martes' => 'martes',
            'Miércoles' => 'miércoles',
            'Jueves' => 'jueves',
            'Viernes' => 'viernes',
            'Sábado' => 'sábado',
            'Domingo' => 'domingo',
        ];

        // Convertimos el día a minúsculas y quitamos acentos
        $diaNormalizado = ucfirst(strtolower($dia));

        // Si existe en el mapeo, lo retornamos, si no, retornamos el mismo valor
        return $diasConAcento[$diaNormalizado] ?? $diaNormalizado;
    }

    public function getHorasDisponibles(Request $request)
    {

        try {
            // Obtener los valores de clase y dia
            $clase = $request->clase;
            $dia = $this->normalizarDia($request->dia);  // Asegúrate de normalizar el día

            // Realizamos la consulta con los valores que recibimos
            $horarios = Horario::where('clase', $clase)
                                ->where('dia', $dia) // Aquí se usa el nombre del día de la semana
                                ->get();

            // Verificamos si hay horarios disponibles
            if ($horarios->isEmpty()) {
                return response()->json(['message' => 'No hay horas disponibles para esta clase en el día seleccionado.'], 404);
            }

            // Creamos un array para las horas disponibles en formato 'hora_inicio - hora_fin'
            $horasDisponibles = [];
            foreach ($horarios as $horario) {
                $horasDisponibles[] = $horario->hora_inicio . ' - ' . $horario->hora_fin;
            }

            // Retornamos las horas disponibles
            return response()->json(['horas' => $horasDisponibles]);

        } catch (\Exception $e) {
            // Si hay algún error, lo manejamos
            return response()->json(['error' => 'Hubo un error en el servidor', 'details' => $e->getMessage()], 500);
        }
    }

    public function verReserva($userId, $reservaId)
    {

        // Obtener los datos de la reserva
        $reserva = Reserva::findOrFail($reservaId);
        
        // Verificar si la reserva pertenece al usuario
        if ($reserva->user_id != $userId) {
            abort(403, 'No tienes permisos para ver esta reserva.');
        }

        return view('reservas.ver', compact('reserva'));
    }

    public function admin_index()
    {
        $reservas = Reserva::all(); // Obtener todas las reservas
        return view('reservas.admin.index', compact('reservas'));
    }

    public function admin_create()
    {
        // Obtén las clases disponibles para el admin (asumiendo que las clases se obtienen de un modelo o una configuración)
        $clases = Horario::distinct()->pluck('clase'); // Esto obtiene todas las clases únicas de la tabla horarios (ajústalo según tu modelo)

        return view('reservas.admin.create', compact('clases')); // Pasamos $clases a la vista
    }

    public function admin_store(Request $request)
    {

        // Validación de la solicitud
        $request->validate([
            'dni' => 'required|string|size:9',  // Validar DNI
            'clase' => 'required|string',
            'dia' => 'required|date|after_or_equal:' . now()->toDateString(),
            'hora' => 'required|string',
        ]);

        // Buscar al usuario por DNI
        $usuario = User::where('dni', $request->dni)->first();

        if (!$usuario) {
            return redirect()->route('reservas.index')->with('error', 'Usuario no encontrado.');
        }

        // Crear la reserva para el usuario encontrado
        Reserva::create([
            'user_id' => $usuario->id,
            'clase' => $request->clase,
            'dia' => $request->dia,
            'hora' => $request->hora,
        ]);

        return redirect()->route('admin-reservas.index')->with('success', 'Reserva creada correctamente.');
    }

    public function admin_destroy($id)
    {
        $reserva = Reserva::findOrFail($id);
        
        $reserva->delete();
        return redirect()->route('admin-reservas.index', $reserva->user_id)->with('success', 'Reserva eliminada con éxito.');
    }

    public function buscarUsuarioPorDNI(Request $request)
    {
        $dni = $request->input('dni');
        
        // Buscar al usuario por DNI
        $usuario = User::where('dni', $dni)->first();

        // Verificar si el usuario fue encontrado y si tiene el atributo 'clases'
        if ($usuario) {
            // Si el atributo 'clases' existe y tiene datos
            if ($usuario->clases) {
                return response()->json(['usuario' => $usuario]);
            } else {
                // Si el atributo 'clases' está vacío o no existe
                return response()->json(['error' => 'El usuario no tiene clases asignadas'], 404);
            }
        } else {
            // Si no se encuentra el usuario por el DNI
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
    }

}
