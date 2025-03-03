@extends('layouts.layout')

@section('title', 'Editar Evento')

@section('content')
    <!-- Editar Evento -->
    <div class="container-fluid p-5">
        <div class="mb-5 text-center">
            <h1 class="display-3 text-uppercase mb-0">Editar Evento</h1>
        </div>

        <form action="{{ route('eventos.update', $evento->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Título -->
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" value="{{ old('titulo', $evento->titulo) }}" required>
                @error('titulo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion', $evento->descripcion) }}</textarea>
                @error('descripcion')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Fecha -->
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha</label>
                <input type="date" class="form-control @error('fecha') is-invalid @enderror" id="fecha" name="fecha" value="{{ old('fecha', $evento->fecha) }}" required max="9999-12-31">
                @error('fecha')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Hora -->
            <div class="mb-3">
                <label for="hora" class="form-label">Hora</label>
                <input type="time" class="form-control @error('hora') is-invalid @enderror" id="hora" name="hora" value="{{ old('hora', \Carbon\Carbon::parse($evento->hora)->format('H:i')) }}" required>
                @error('hora')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Imagen -->
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen</label>
                <input type="file" class="form-control @error('imagen') is-invalid @enderror" id="imagen" name="imagen">
                
                <!-- Mostrar imagen actual solo si tiene imagen asociada -->
                @if($evento->imagen)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $evento->imagen) }}" alt="Imagen del evento" class="img-fluid" style="max-width: 200px;">
                    </div>
                    <!-- Opción para eliminar la imagen actual solo si tiene imagen -->
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="eliminar_imagen" id="eliminar_imagen">
                        <label class="form-check-label" for="eliminar_imagen">
                            Eliminar imagen actual
                        </label>
                    </div>
                @endif

                @error('imagen')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Evento</button>
        </form>
    </div>
    <!-- Editar Evento End -->
@endsection
