@extends('layouts.layout')

@section('title', 'Detalles del Cliente')

@section('content')
    <!-- Client Details Start -->
    <div class="container-fluid p-5">
        <div class="mb-5 text-center">
            <h5 class="text-primary text-uppercase">Detalles del Cliente</h5>
            <h1 class="display-3 text-uppercase mb-0">{{ $cliente->name }} {{ $cliente->surname }}</h1>
        </div>

        <form>
            <!-- DNI -->
            <div class="mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" class="form-control" id="dni" value="{{ $cliente->dni }}" disabled>
            </div>

            <!-- Nombre -->
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" value="{{ $cliente->name }}" disabled>
            </div>

            <!-- Apellidos -->
            <div class="mb-3">
                <label for="surname" class="form-label">Apellidos</label>
                <input type="text" class="form-control" id="surname" value="{{ $cliente->surname }}" disabled>
            </div>

            <!-- Correo Electrónico -->
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" value="{{ $cliente->email }}" disabled>
            </div>

            <!-- Botón para regresar a la lista -->
            <a href="{{ route('clientes.index') }}" class="btn btn-primary">Volver a la Lista</a>
        </form>
    </div>
    <!-- Client Details End -->
@endsection
