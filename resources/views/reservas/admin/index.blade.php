@extends('layouts.layout')

@section('title', 'Lista de Reservas')

@section('content')
<h1>Reservas</h1>

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
                    <form action="{{ route('admin-reservas.destroy', $reserva->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
                <td>{{ $reserva->user->dni }}</td>
                <td>{{ $reserva->user->name }}</td>
                <td>{{ $reserva->clase }}</td>
                <td>{{ $reserva->dia }}</td>
                <td>{{ $reserva->hora }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('admin-reservas.create') }}" class="btn btn-success"><i class="bi bi-plus-circle"></i> Crear nueva reserva</a>

@endsection
