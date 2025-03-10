@extends('layouts.layout')

@section('title', 'Ver Reserva')

@section('content')
<div class="container-fluid p-5">
    <div class="mb-5 text-center">
        <h1 class="display-3 text-uppercase mb-0">Ver Reserva</h1>
    </div>

    <form>
        @csrf

        <!-- Campo para el DNI del usuario -->
        <div class="mb-3">
            <label for="dni" class="form-label">DNI del Usuario</label>
            <input type="text" name="dni" id="dni" class="form-control" value="{{ $usuario->dni }}" disabled>
        </div>

        <!-- Campo Nombre del Usuario -->
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $usuario->name }} {{$reserva->user->surname}}" disabled>
        </div>

        <!-- Selección de Clase -->
        <div class="mb-3">
            <label for="clase" class="form-label">Clase</label>
            <input type="text" name="clase" id="clase" class="form-control" value="{{ $reserva->clase }}" disabled>
        </div>

        <!-- Días disponibles -->
        <div class="mb-3">
            <label for="dia" class="form-label">Día</label>
            <input type="text" name="dia" id="dia" class="form-control" value="{{ \Carbon\Carbon::parse($reserva->dia)->format('d/m/Y') }}" disabled>
        </div>

        <!-- Horas disponibles -->
        <div class="mb-3">
            <label for="hora" class="form-label">Hora</label>
            <input type="text" name="hora" id="hora" class="form-control" value="{{ $reserva->hora }}" disabled>
        </div>

        <a href="{{ route('admin-reservas.index') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
