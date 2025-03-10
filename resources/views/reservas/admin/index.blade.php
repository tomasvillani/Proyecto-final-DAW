@extends('layouts.layout')

@section('title', 'Lista de Reservas')

@section('content')
<div class="container-fluid p-5">
    <div class="mb-5 text-center">
        <h1 class="display-3 text-uppercase mb-0">Reservas</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>DNI</th>
                <th>Usuario</th>
                <th>Clase</th>
                <th>Fecha</th>
                <th>Hora</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservas as $reserva)
                <tr>
                    <td>
                        <a href="{{ route('admin-reservas.ver', ['userId' => $reserva->user->id, 'reservaId' => $reserva->id]) }}" class="btn btn-info">
                            <i class="bi bi-eye"></i> Ver
                        </a>
                        <a href="{{ route('admin-reservas.edit', $reserva->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                        <form action="{{ route('admin-reservas.destroy', $reserva->id) }}" method="POST" style="display:inline;" 
                            onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta reserva?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Eliminar</button>
                        </form>
                    </td>
                    <td>{{ $reserva->user->dni }}</td>
                    <td>{{ $reserva->user->name }} {{$reserva->user->surname}}</td>
                    <td>{{ $reserva->clase }}</td>
                    <td>{{ $reserva->dia }}</td>
                    <td>{{ $reserva->hora }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Agregar paginación -->
    <div class="col-12">
        {{ $reservas->links('pagination::bootstrap-4') }}
    </div>

    <a href="{{ route('admin-reservas.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Crear nueva reserva
    </a>
</div>

@endsection
