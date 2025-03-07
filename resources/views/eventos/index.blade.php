@extends('layouts.layout')

@section('title', 'Eventos')

@section('content')
<!-- Hero Start -->
<div class="container-fluid bg-primary p-5 bg-hero mb-5">
    <div class="row py-5">
        <div class="col-12 text-center">
            <h1 class="display-2 text-uppercase text-white mb-md-4">Eventos</h1>
            <a href="/" class="btn btn-primary py-md-3 px-md-5 me-3">Inicio</a>
            <a href="{{ route('eventos.index') }}" class="btn btn-light py-md-3 px-md-5">Eventos</a>
        </div>
    </div>
</div>
<!-- Hero End -->

<!-- Eventos con Tarjetas Desplegables -->
<div class="container-fluid p-5">

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-5">

        @foreach($eventos as $evento)
            <div class="col-lg-4 col-md-6">
                <div class="blog-item">
                    @if($evento->imagen)
                        <div class="position-relative overflow-hidden rounded-top" style="cursor: pointer;" onclick="toggleDescripcion({{ $evento->id }})">
                            <img class="img-fluid evento-img" src="{{ asset('storage/' . $evento->imagen) }}" alt="{{ $evento->titulo }}">
                        </div>
                    @endif

                    <div class="bg-dark d-flex flex-column rounded-bottom p-4">
                        <div class="d-flex align-items-center">
                            <div class="text-center text-secondary border-end border-secondary pe-3 me-3">
                                <span>{{ \Carbon\Carbon::parse($evento->fecha)->format('d') }}</span>
                                <h6 class="text-light text-uppercase mb-0">
                                    {{ \Carbon\Carbon::parse($evento->fecha)->translatedFormat('F') }}
                                </h6>
                                <span>{{ \Carbon\Carbon::parse($evento->fecha)->format('Y') }}</span>

                                <!-- Hora con icono alineados en fila -->
                                <div class="d-flex justify-content-center align-items-center gap-2 mt-2">
                                    <i class="bi bi-clock text-warning"></i>
                                    <span class="text-warning fw-bold">
                                        {{ \Carbon\Carbon::parse($evento->hora)->format('H:i') }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <!-- Titulo de evento, con clic para mostrar la descripción si no tiene imagen -->
                                <a class="h5 text-uppercase text-light" href="javascript:void(0);" onclick="toggleDescripcion({{ $evento->id }})">
                                    {{ $evento->titulo }}
                                </a>
                            </div>

                            @if (Auth::check() && Auth::user()->tipo_usuario == 'admin')
                                <div class="d-flex gap-2">
                                    <!-- Botón Editar -->
                                    <a href="{{ route('eventos.edit', $evento->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <!-- Formulario Eliminar -->
                                    <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $evento->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $evento->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <!-- Descripción Desplegable -->
                        <div class="evento-descripcion mt-3 text-light" id="evento-{{ $evento->id }}" style="display: none;">
                            <p>{{ $evento->descripcion }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Paginación -->
        <div class="col-12">
            {{ $eventos->links('pagination::bootstrap-4') }}
        </div>

        <!-- Botón Crear Evento solo para Admin -->
        @if (Auth::check() && Auth::user()->tipo_usuario == 'admin')
            <div class="text-start mb-4">
                <a href="{{ route('eventos.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Nuevo Evento
                </a>
            </div>
        @endif
    </div>
</div>

<script>
    // Mostrar la descripción al hacer click en la imagen o en el título
    function toggleDescripcion(eventoId) {
        const descripcion = document.querySelector(`#evento-${eventoId}`);

        // Mostrar/Ocultar la descripción con efecto
        if (descripcion.style.display === 'none' || descripcion.style.display === '') {
            descripcion.style.display = 'block';
        } else {
            descripcion.style.display = 'none';
        }
    }

    // Confirmación para eliminar
    function confirmDelete(eventoId) {
        if (confirm("¿Estás seguro de eliminar este evento?")) {
            document.querySelector('#delete-form-' + eventoId).submit();
        }
    }
</script>
@endsection
