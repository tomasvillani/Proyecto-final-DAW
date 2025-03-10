@extends('layouts.layout')

@section('title', 'Tarifa Requerida')

@section('content')
    <div class="container mt-5 text-center">
        <h1 class="display-3 text-uppercase mb-4">No tienes una tarifa activa</h1>
        <p class="lead">Para realizar una reserva, es necesario tener una tarifa asociada.</p>
        <a href="/" class="btn btn-primary mt-3">Volver a la página principal</a>
    </div>
@endsection
