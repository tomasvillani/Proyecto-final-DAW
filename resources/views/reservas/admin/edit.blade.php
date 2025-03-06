@extends('layouts.layout')

@section('title', 'Editar Reserva para Usuario')

@section('content')
<div class="container-fluid p-5">
    <div class="mb-5 text-center">
        <h1 class="display-3 text-uppercase mb-0">Editar Reserva para Usuario</h1>
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

    <form action="{{ route('admin-reservas.update', $reserva->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Campo para el DNI del usuario -->
        <div class="mb-3">
            <label for="dni" class="form-label">DNI del Usuario</label>
            <input type="text" name="dni" id="dni" class="form-control" required value="{{ old('dni', $reserva->user->dni) }}">
            @error('dni')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Selección de Clase -->
        <div class="mb-3">
            <label for="clase" class="form-label">Clase</label>
            <select name="clase" id="clase" class="form-control" required>
                <option value="">Selecciona una clase</option>
                @foreach ($clasesUsuario as $clase)
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
                <option value="{{ $reserva->hora }}">{{ $reserva->hora }}</option>
            </select>
            @error('hora')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{{ route('admin-reservas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    // Cuando se ingresa el DNI del usuario
    $('#dni').on('change', function () {
        let dni = $(this).val();
        $('#clase').prop('disabled', true).html('<option value="">Selecciona una clase</option>');
        $('#dia').prop('disabled', true).html('<option value="">Selecciona un día</option>');
        $('#hora').prop('disabled', true).html('<option value="">Selecciona una hora</option>');

        // Limpiar cualquier mensaje de error previo
        $('#error-dni').remove();

        if (dni) {
            $.ajax({
                url: "{{ route('reservas.buscarUsuarioPorDNI') }}",
                type: "GET",
                data: { dni: dni },
                success: function (data) {
                    if (data.usuario) {
                        $('#clase').prop('disabled', false);
                        $('#dia').prop('disabled', false);
                        $('#hora').prop('disabled', false);
                        // Limpiar clases previas y agregar las nuevas
                        $('#clase').html('<option value="">Selecciona una clase</option>');
                        if(data.usuario.clases)
                        {
                            data.usuario.clases.forEach(function(clase) {
                                $('#clase').append(`<option value="${clase}">${clase}</option>`);
                            });
                        }
                    } else {
                        // Mostrar mensaje de error si no se encuentra el usuario
                        $('#dni').after('<div id="error-dni" class="alert alert-danger mt-2">Usuario no encontrado.</div>');
                    }
                }
            });
        }
    });

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

    $(document).ready(function() {
        $('#clase').trigger('change');
    });
</script>

@endsection
