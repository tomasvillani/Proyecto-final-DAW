@extends('layouts.layout')  

@section('title', 'Página No Encontrada')

@section('content')
<div class="container text-center mt-5">
    <h1 class="display-1 text-danger">404</h1>
    <h3 class="mb-4">¡Oops! Página no encontrada.</h3>
    <p>La página que buscas no existe o ha sido movida.</p>
    <a href="/" class="btn btn-primary">Volver al inicio</a>
</div>
@endsection
