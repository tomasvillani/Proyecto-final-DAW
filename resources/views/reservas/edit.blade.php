@extends('layouts.layout')

@section('title', 'Editar Reserva')

@section('content')
<div class="container-fluid p-5">
    <div class="mb-5 text-center">
        <h1 class="display-3 text-uppercase mb-0">Editar Reserva</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('mis-reservas.update', ['userId' => $usuario->id, 'reservaId' => $reserva->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="user_id" value="{{ $usuario->id }}">

        <!-- Selección de Clase -->
        <div class="mb-3">
            <label for="clase" class="form-label">Clase</label>
            <select name="clase" id="clase" class="form-control" required>
                <option value="">Selecciona una clase</option>
                @foreach ($usuario->clases as $clase)
                    <option value="{{ $clase }}" {{ $reserva->clase == $clase ? 'selected' : '' }}>{{ $clase }}</option>
                @endforeach
            </select>
            @error('clase')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Días disponibles -->
        <div class="mb-3">
            <label for="dia" class="form-label">Día</label>
            <select name="dia" id="dia" class="form-control" required>
                <option value="">Selecciona un día</option>
            </select>
            @error('dia')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Horas disponibles -->
        <div class="mb-3">
            <label for="hora" class="form-label">Hora</label>
            <select name="hora" id="hora" class="form-control" required>
                <option value="">Selecciona una hora</option>
            </select>
            @error('hora')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Reserva</button>
    </form>
</div>

<script>
    // Cuando seleccionas la clase
    $('#clase').on('change', function () {
        let clase = $(this).val();
        if (clase) {
            $.ajax({
                url: "{{ route('reservas.getDiasDisponibles') }}",
                type: "GET",
                data: { clase: clase },
                success: function (data) {
                    $('#dia').html('<option value="">Selecciona un día</option>');
                    data.dias.forEach(dia => {
                        let fechaFormateada = new Date(dia.fecha).toLocaleDateString('es-ES');  // Formato d/m/Y
                        $('#dia').append(`<option value="${dia.fecha}" ${dia.fecha == '{{ $reserva->dia }}' ? 'selected' : ''}>${dia.nombreDia} - ${fechaFormateada}</option>`);
                    });
                    $('#dia').trigger('change');
                }
            });
        }
    });

    // Cuando seleccionas el día
    $('#dia').on('change', function () {
        let clase = $('#clase').val();
        let fecha = $(this).val();
        if (clase && fecha) {
            let fechaObj = new Date(fecha);
            let opciones = { weekday: 'long' };
            let diaSemana = fechaObj.toLocaleDateString('es-ES', opciones);

            $.ajax({
                url: "{{ route('reservas.getHorasDisponibles') }}",
                type: "GET",
                data: { clase: clase, dia: diaSemana },
                success: function (data) {
                    $('#hora').html('<option value="">Selecciona una hora</option>');
                    if (data.horas && data.horas.length > 0) {
                        data.horas.forEach(function(hora) {
                            let horas = hora.split(' - ');
                            let horaInicio = horas[0];
                            let horaFin = horas[1];
                            $('#hora').append(`<option value="${horaInicio}" ${horaInicio == '{{ $reserva->hora }}' ? 'selected' : ''}>${horaInicio} - ${horaFin}</option>`);
                        });
                    } else {
                        $('#hora').html('<option value="">No hay horas disponibles</option>');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error al obtener las horas disponibles. Intenta nuevamente.');
                }
            });
        }
    });

    // Inicializa los valores de días y horas
    $(document).ready(function() {
        $('#clase').trigger('change');
    });
</script>

@endsection
