<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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
        $this->authorizeCliente($userId); 

        // Eliminar reservas vencidas antes de mostrar las reservas activas
        $this->eliminarReservasVencidas();

        $usuario = User::findOrFail($userId);
        $tarifa = $usuario->tarifa;
        if (!$tarifa) {
            return redirect()->route('sin-tarifa');
        }else if($tarifa && !$usuario->clases){
            return view('reservas.sin-clases');
        }

        $reservas = $usuario->reservas()
                    ->orderBy('dia', 'asc') // Ordena por fecha más próxima
                    ->orderBy('hora', 'asc') // Ordena por hora más temprana
                    ->paginate(6);

        $reservas->getCollection()->transform(function ($reserva) {
            $reserva->dia = \Carbon\Carbon::parse($reserva->dia)->format('m/d/Y'); // Formatea la fecha
            return $reserva;
        });
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

        // Validar los datos de la solicitud
        $request->validate([
            'clase' => 'required|string',
            'dia' => 'required|date|after_or_equal:' . now()->toDateString(),
            'hora' => 'required|string',
        ], [
            'clase.required' => 'La clase es obligatoria.',
            'clase.string' => 'La clase debe ser un texto válido.',
            
            'dia.required' => 'El día es obligatorio.',
            'dia.date' => 'El día debe ser una fecha válida.',
            'dia.after_or_equal' => 'El día debe ser hoy o una fecha posterior.',
        
            'hora.required' => 'La hora es obligatoria.',
            'hora.string' => 'La hora debe ser un texto válido.',
        ]);
        

        // Validar si la hora elegida ya pasó en el día de la reserva
        $horaReserva = Carbon::parse($request->dia . ' ' . $request->hora);
        if ($horaReserva->isBefore(Carbon::now())) {
            return redirect()->back()->with('error', 'La fecha y hora seleccionada ya ha pasado.')->withInput();
        }

        // Verificar si ya existe una reserva para el mismo día, hora y clase
        $existeReserva = Reserva::where('user_id', $request->user_id)
                                ->where('clase', $request->clase)
                                ->whereDate('dia', $request->dia)
                                ->where('hora', $request->hora)
                                ->exists();

        if ($existeReserva) {
            return redirect()->back()->with('error', 'Ya tienes una reserva para esta clase, día y hora.')->withInput();
        }

        // Guardar la nueva reserva
        Reserva::create([
            'user_id' => $request->user_id,
            'clase' => $request->clase,
            'dia' => $request->dia,
            'hora' => $request->hora,
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
                $diaSemanaEspanhol = $hoy->locale('es')->dayName;  // Obtener el día de la semana en espanhol
                $fecha = $hoy->format('Y-m-d');  // La fecha en formato YYYY-MM-DD

                // Verificar si la clase tiene horarios para este día
                $horarios = $this->obtenerHorariosPorDia($diaSemanaEspanhol);

                foreach ($horarios as $horario) {
                    if ($horario['clase'] == $clase) {
                        // Se anhade la fecha, pero se usa el nombre del día
                        $diasDisponibles[] = [
                            'nombreDia' => $diaSemanaEspanhol,  // Lunes, Martes, etc.
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
        $horarios = Horario::where('dia', $diaSemana)
                        ->where('disponible', true) // Filtrar por disponibilidad
                        ->get(); // Filtramos por el día de la semana (en español)

        // Si no se encuentran horarios disponibles, retornamos un array vacío
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
        // Mapeo de los días en espanhol
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

    // Mostrar formulario para editar una reserva de un cliente
    public function edit($userId, $reservaId)
    {
        $this->authorizeCliente($userId);

        $reserva = Reserva::findOrFail($reservaId);

        // Verificar que la reserva pertenece al cliente
        if ($reserva->user_id != $userId) {
            abort(403, 'No tienes permisos para editar esta reserva.');
        }

        $usuario = User::findOrFail($userId);
        $tarifa = $usuario->tarifa;

        if (!$tarifa) {
            return redirect()->route('sin-tarifa');
        }

        // Obtener horarios disponibles de acuerdo con la clase
        $horariosDisponibles = $this->getDiasHorasDisponibles($reserva->clase);

        return view('reservas.edit', compact('reserva', 'usuario', 'tarifa', 'horariosDisponibles'));
    }

    private function getDiasHorasDisponibles($clase)
    {
        $diasDisponibles = [];
        $horasDisponibles = [];

        $hoy = now();
        for ($i = 0; $i < 7; $i++) {
            $diaSemana = $hoy->locale('es')->dayName;
            $fecha = $hoy->format('Y-m-d');

            $horarios = $this->obtenerHorariosPorDia($diaSemana);

            foreach ($horarios as $horario) {
                if ($horario['clase'] == $clase) {
                    $diasDisponibles[] = [
                        'nombreDia' => $diaSemana,
                        'fecha' => $fecha
                    ];
                    break;
                }
            }
            $hoy->addDay();
        }

        // Buscar las horas disponibles para la clase
        if (count($diasDisponibles) > 0) {
            foreach ($diasDisponibles as $dia) {
                $horarios = Horario::where('clase', $clase)
                    ->where('dia', $dia['nombreDia'])
                    ->get();

                foreach ($horarios as $horario) {
                    $horasDisponibles[] = $horario->hora_inicio . ' - ' . $horario->hora_fin;
                }
            }
        }

        return ['dias' => $diasDisponibles, 'horas' => $horasDisponibles];
    }

    // Actualizar una reserva
    public function update(Request $request, $userId, $reservaId)
    {
        // Autorización: Verifica que el usuario que está haciendo la petición sea el propietario de la reserva.
        $this->authorizeCliente($userId);

        // Validación de los datos del formulario.
        $request->validate([
            'clase' => 'required|string',
            'dia' => 'required|date|after_or_equal:' . now()->toDateString(),  // La fecha debe ser actual o futura
            'hora' => 'required|string',
        ], [
            'clase.required' => 'La clase es obligatoria.',
            'clase.string' => 'La clase debe ser un texto válido.',
            
            'dia.required' => 'El día es obligatorio.',
            'dia.date' => 'El día debe ser una fecha válida.',
            'dia.after_or_equal' => 'El día debe ser hoy o una fecha posterior.',
        
            'hora.required' => 'La hora es obligatoria.',
            'hora.string' => 'La hora debe ser un texto válido.',
        ]);
        

        // Comprobar que la fecha y hora no son del pasado
        $fechaHoraReserva = $request->dia . ' ' . $request->hora;
        if (strtotime($fechaHoraReserva) < time()) {
            return redirect()->back()->with('error', 'La fecha y hora seleccionada ya ha pasado.')->withInput();
        }

        // Buscar la reserva a actualizar.
        $reserva = Reserva::findOrFail($reservaId);

        // Verificar si la reserva pertenece al cliente (usuario).
        if ($reserva->user_id != $userId) {
            abort(403, 'No tienes permisos para editar esta reserva.');
        }

        // Verificar si ya existe una reserva para el mismo usuario, clase, día y hora.
        $existeReserva = Reserva::where('user_id', $userId)
                                ->where('clase', $request->clase)
                                ->whereDate('dia', $request->dia)
                                ->where('hora', $request->hora)
                                ->where('id', '!=', $reservaId) // Aseguramos que no sea la misma reserva
                                ->exists();

        if ($existeReserva) {
            return redirect()->back()->with('error', 'Ya tienes una reserva para esta clase, día y hora.')->withInput();
        }

        // Actualizar la reserva con los nuevos datos proporcionados en el formulario.
        $reserva->update([
            'clase' => $request->clase,
            'dia' => $request->dia,
            'hora' => $request->hora,
        ]);

        // Redirigir al cliente a su lista de reservas con un mensaje de éxito.
        return redirect()->route('mis-reservas.index', $userId)->with('success', 'Reserva actualizada correctamente.');
    }

    public function admin_index()
    {
        // Eliminar reservas vencidas antes de mostrar todas las reservas
        $this->eliminarReservasVencidas();

        $reservas = Reserva::orderBy('dia', 'asc')
                        ->orderBy('hora', 'asc') // Ordena también por hora
                        ->paginate(6);

        $reservas->getCollection()->transform(function ($reserva) {
            $reserva->dia = \Carbon\Carbon::parse($reserva->dia)->format('d/m/Y'); // Formatea la fecha
            return $reserva;
        });

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
            'dni' => 'required|string|size:9',  // El DNI debe tener exactamente 9 caracteres
            'clase' => 'required|string',  // La clase es obligatoria y debe ser un texto
            'dia' => 'required|date|after_or_equal:' . now()->toDateString(),  // El día debe ser hoy o una fecha posterior
            'hora' => 'required|string',  // La hora es obligatoria y debe ser un texto
        ], [
            'dni.required' => 'El DNI es obligatorio.',
            'dni.string' => 'El DNI debe ser un texto válido.',
            'dni.size' => 'El DNI debe tener exactamente 9 caracteres.',
        
            'clase.required' => 'La clase es obligatoria.',
            'clase.string' => 'La clase debe ser un texto válido.',
            
            'dia.required' => 'El día es obligatorio.',
            'dia.date' => 'El día debe ser una fecha válida.',
            'dia.after_or_equal' => 'El día debe ser hoy o una fecha posterior.',
        
            'hora.required' => 'La hora es obligatoria.',
            'hora.string' => 'La hora debe ser un texto válido.',
        ]);        

        // Buscar al usuario por DNI y asegurarse de que su tipo sea "cliente"
        $usuario = User::where('dni', $request->dni)->where('tipo_usuario', 'cliente')->first();

        if (!$usuario) {
            return redirect()->route('admin-reservas.create')->withErrors(['dni' => 'El usuario no existe o no es un cliente.']);
        }

        // Verificar si ya existe una reserva para el mismo usuario, clase, día y hora
        $existeReserva = Reserva::where('user_id', $usuario->id)
                                ->where('clase', $request->clase)
                                ->whereDate('dia', $request->dia)
                                ->where('hora', $request->hora)
                                ->exists();

        if ($existeReserva) {
            return redirect()->route('admin-reservas.create')->withErrors(['dni' => 'El usuario ya tiene una reserva para esta clase, día y hora.']);
        }

        // Validar si la hora elegida ya pasó en el día de la reserva
        $horaReserva = Carbon::parse($request->dia . ' ' . $request->hora);
        if ($horaReserva->isBefore(Carbon::now())) {
            return redirect()->back()->with('error', 'La fecha y hora seleccionada ya ha pasado.')->withInput();
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
            // Si el usuario tiene clases asociadas
            return response()->json(['usuario' => $usuario]);
        } else {
            // Si no se encuentra el usuario por el DNI, devolver respuesta vacía
            return response()->json(['usuario' => null], 404);
        }
    }

    public function admin_ver($userId, $reservaId)
    {
        $usuario = User::findOrFail($userId);
        $reserva = Reserva::findOrFail($reservaId);

        return view('reservas.admin.ver', compact('usuario', 'reserva'));
    }

    private function eliminarReservasVencidas()
    {
        $ahora = now();

        // Eliminar reservas pasadas (funcionalidad existente)
        Reserva::where('dia', '<', $ahora->toDateString())
            ->orWhere(function ($query) use ($ahora) {
                $query->where('dia', '=', $ahora->toDateString())
                    ->where('hora', '<', $ahora->format('H:i'));
            })
            ->delete();

        // Obtener los horarios no disponibles
        $horarios_no_disponibles = Horario::where('disponible', false)
            ->get(['dia', 'hora_inicio']);  // Asumimos que 'dia' es un varchar con el nombre del día (lunes, martes, etc.)

        // Recorremos las reservas
        $reservas = Reserva::all();

        foreach ($reservas as $reserva) {
            // Convertimos la fecha de la reserva en un nombre de día de la semana (lunes, martes, etc.)
            $dia_de_la_reserva = Carbon::parse($reserva->dia)->locale('es')->dayName; // 'dayName' devuelve el nombre completo del día en español (lunes, martes, etc.)

            // Ahora, verificamos si ese día está en los horarios no disponibles
            foreach ($horarios_no_disponibles as $horario) {
                if (strtolower($dia_de_la_reserva) == strtolower($horario->dia) && $reserva->hora == $horario->hora_inicio) {
                    // Si el día y la hora coinciden con un horario no disponible, eliminamos la reserva
                    $reserva->delete();
                    break;  // Si encontramos una coincidencia, ya no necesitamos seguir buscando
                }
            }
        }
    }

    public function admin_edit($id)
    {
        $reserva = Reserva::findOrFail($id);
        $usuarios = User::all(); // Para seleccionar otro usuario si es necesario

        // Obtenemos las clases que tiene el usuario
        $usuario = $reserva->user;
        $clasesUsuario = $usuario->clases; // Asumiendo que tienes una relación en el modelo User

        // Pasar el DNI del usuario que tiene la reserva
        $dniUsuario = $usuario->dni;

        return view('reservas.admin.edit', compact('reserva', 'usuarios', 'clasesUsuario', 'dniUsuario'));
    }

    public function admin_update(Request $request, $id)
    {
        // Validación de los datos del formulario con mensajes personalizados para los campos.
        $request->validate([
            'dni' => 'required|string|size:9',  // Validar que el DNI sea correcto
            'clase' => 'required|string',
            'dia' => 'required|date|after_or_equal:' . now()->toDateString(),  // La fecha debe ser actual o futura
            'hora' => 'required|string',
        ], [
            'dni.required' => 'El DNI es obligatorio.',
            'dni.string' => 'El DNI debe ser una cadena de texto.',
            'dni.size' => 'El DNI debe tener 9 caracteres.',
            'clase.required' => 'La clase es obligatoria.',
            'clase.string' => 'La clase debe ser una cadena de texto.',
            'dia.required' => 'La fecha es obligatoria.',
            'dia.date' => 'La fecha debe ser una fecha válida.',
            'dia.after_or_equal' => 'La fecha debe ser igual o posterior a la fecha actual.',
            'hora.required' => 'La hora es obligatoria.',
            'hora.string' => 'La hora debe ser una cadena de texto.',
        ]);

        // Comprobar que la fecha y hora no son del pasado
        $fechaHoraReserva = $request->dia . ' ' . $request->hora;
        if (strtotime($fechaHoraReserva) < time()) {
            return redirect()->back()->with('error', 'La fecha y hora seleccionada ya ha pasado.')->withInput();
        }

        // Buscar al usuario por DNI
        $usuario = User::where('dni', $request->dni)->first();

        // Verificar si el usuario existe y si es de tipo 'cliente'
        if (!$usuario) {
            return redirect()->back()->with('error_dni', 'Usuario no encontrado.')->withInput();
        }

        if ($usuario->tipo_usuario !== 'cliente') {
            return redirect()->back()->with('error_dni', 'El usuario debe ser de tipo cliente.')->withInput();
        }

        // Obtener las clases asociadas a ese usuario
        $clasesUsuario = $usuario->clases; // Asumiendo que 'clases' es una relación en el modelo User

        // Verificar si la clase seleccionada está disponible para ese usuario
        if (!in_array($request->clase, $clasesUsuario)) {
            return redirect()->back()->with('error_dni', 'La clase seleccionada no está disponible para este usuario.')->withInput();
        }

        // Verificar si ya existe una reserva para este usuario, clase, día y hora
        $existeReserva = Reserva::where('user_id', $usuario->id)
                                ->where('clase', $request->clase)
                                ->whereDate('dia', $request->dia)
                                ->where('hora', $request->hora)
                                ->where('id', '!=', $id)  // Aseguramos que no se compare con la misma reserva que estamos actualizando
                                ->exists();

        if ($existeReserva) {
            return redirect()->route('admin-reservas.edit', $id)->withErrors(['dni' => 'El usuario ya tiene una reserva para esta clase, día y hora.']);
        }

        // Actualizar la reserva con los nuevos datos
        $reserva = Reserva::findOrFail($id);
        $reserva->update([
            'user_id' => $usuario->id,  // Se actualiza con el ID del usuario encontrado
            'clase' => $request->clase,
            'dia' => $request->dia,
            'hora' => $request->hora,
        ]);

        // Redirigir al administrador a la lista de reservas con un mensaje de éxito.
        return redirect()->route('admin-reservas.index')->with('success', 'Reserva actualizada correctamente.');
    }

}
