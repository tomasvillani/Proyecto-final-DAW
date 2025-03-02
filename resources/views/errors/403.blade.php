@extends('layouts.layout')  

@section('title', 'Acceso Denegado')

@section('content')
<div class="container text-center mt-5">
    <h1 class="display-1 text-warning">403</h1>
    <h3 class="mb-4">Acceso Denegado</h3>
    <p>No tienes permiso para acceder a esta página.</p>
    <a href="/" class="btn btn-primary">Volver al inicio</a>
</div>
@endsection
