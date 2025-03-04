@extends('layouts.layout')

@section('title', "Reservas de $usuario->name")

@section('content')
    <!-- Reservation List Start -->
    <div class="container-fluid p-5">
        <div class="mb-5 text-center">
            <h1 class="display-3 text-uppercase mb-0">Reservas de {{ $usuario->name }}</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Clase</th>
                    <th>Día</th>
                    <th>Hora</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservas as $reserva)
                    <tr>
                        <td>
                            <!-- Ver reserva -->
                            <a href="{{ route('mis-reservas.ver', ['userId' => $usuario->id, 'reservaId' => $reserva->id]) }}" class="btn btn-info">
                                <i class="bi bi-eye"></i> Ver
                            </a>

                            <!-- Eliminar reserva con confirmación -->
                            <form action="{{ route('mis-reservas.destroy', $reserva->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $reserva->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $reserva->id }})">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                        <td>{{ $reserva->clase }}</td>
                        <td>{{ \Carbon\Carbon::parse($reserva->dia)->format('d/m/Y') }}</td>
                        <td>{{ $reserva->hora }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Botón para crear una nueva reserva -->
        <div class="mb-4">
            <a href="{{ route('mis-reservas.create', $usuario->id) }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nueva Reserva
            </a>
        </div>
    </div>
    <!-- Reservation List End -->

    <!-- Script de confirmación para eliminar -->
    <script>
        function confirmDelete(reservaId) {
            if (confirm("¿Estás seguro de que deseas eliminar esta reserva?")) {
                document.getElementById('delete-form-' + reservaId).submit();
            }
        }
    </script>
@endsection
