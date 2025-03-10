@extends('layouts.layout')

@section('title', 'Detalles del Evento')

@section('content')
    <!-- Detalles del Evento -->
    <div class="container-fluid p-5">
        <div class="mb-5 text-center">
            <h5 class="text-primary text-uppercase">Detalles del Evento</h5>
            <h1 class="display-3 text-uppercase mb-0">{{ $evento->titulo }}</h1>
        </div>

        <form>
            <!-- Título -->
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" value="{{ $evento->titulo }}" disabled>
            </div>

            <!-- Descripción -->
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" rows="4" disabled>{{ $evento->descripcion }}</textarea>
            </div>

            <!-- Fecha -->
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" value="{{ $evento->fecha }}" disabled>
            </div>

            <!-- Hora -->
            <div class="mb-3">
                <label for="hora" class="form-label">Hora</label>
                <input type="time" class="form-control" id="hora" value="{{ $evento->hora }}" disabled>
            </div>

            <!-- Imagen -->
            @if ($evento->imagen)
                <div class="mb-3 text-center">
                    <img src="{{ asset('storage/' . $evento->imagen) }}" alt="Imagen del evento" class="img-fluid rounded" style="max-width: 400px;">
                </div>
            @endif

            <!-- Botón para regresar a la lista -->
            <a href="{{ route('eventos.index') }}" class="btn btn-primary">Volver a la Lista</a>
        </form>
    </div>
    <!-- Detalles del Evento End -->
@endsection