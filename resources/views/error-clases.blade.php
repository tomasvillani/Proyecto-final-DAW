@extends('layouts.layout')

@section('title', 'Cambio de Clases No Permitido')

@section('content')
    <div class="container mt-5 text-center">
        <h1 class="display-3 text-uppercase mb-4">No es posible cambiar las clases</h1>
        <p class="lead">
            Actualmente tienes una tarifa activa, por lo que no es posible modificar las clases seleccionadas hasta que tu tarifa haya vencido.
        </p>
        <a href="/" class="btn btn-primary mt-3">Volver a la página principal</a>
    </div>
@endsection
