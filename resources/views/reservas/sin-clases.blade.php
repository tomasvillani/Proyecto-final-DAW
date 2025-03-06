@extends('layouts.layout')

@section('title', 'Sin clases')

@section('content')
    <div class="container mt-5 text-center">
        <h1 class="display-3 text-uppercase mb-4">No tienes clases contratadas</h1>
        <p class="lead">
            Actualmente tienes una tarifa activa, pero no has elegido las clases.
        </p>
        <p>
            Por favor, elige las clases que quieres contratar en el apartado 'Perfil'.
        </p>
        <a href="/" class="btn btn-primary mt-3">Volver a la página principal</a>
    </div>
@endsection
