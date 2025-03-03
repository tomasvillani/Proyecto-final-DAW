@extends('errors.layout') 

@section('title', 'Sesión Expirada')

@section('content')
<div class="container text-center mt-5">
    <h1 class="display-1 text-info">419</h1>
    <h3 class="mb-4">Sesión Expirada</h3>
    <p>Tu sesión ha expirado. Por favor, vuelve a iniciar sesión.</p>
    <a href="{{ route('login') }}" class="btn btn-primary">Iniciar sesión</a>
</div>
@endsection
