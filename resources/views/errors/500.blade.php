@extends('errors.layout')  

@section('title', 'Error del Servidor')

@section('content')
<div class="container text-center mt-5">
    <h1 class="display-1 text-danger">500</h1>
    <h3 class="mb-4">¡Algo salió mal!</h3>
    <p>Hubo un error en el servidor. Por favor, intenta nuevamente más tarde.</p>
    <a href="/" class="btn btn-primary">Volver al inicio</a>
</div>
@endsection
