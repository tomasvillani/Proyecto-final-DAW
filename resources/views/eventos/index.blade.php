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

<!-- Eventos con Nuevo Diseño de Tarjetas -->
<div class="container-fluid p-5">

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-5">
        @foreach($eventos as $evento)
            <div class="col-lg-4 col-md-6">
                <div class="event-card">
                    @if($evento->imagen)
                        <img class="img-fluid evento-img" src="{{ asset('storage/' . $evento->imagen) }}" alt="{{ $evento->titulo }}">
                        <!--src="{{ asset('storage/' . $evento->imagen) }}"-->
                    @endif
                    <div class="card__content">
                        <p class="card__title">{{ $evento->titulo }}</p>
                        <p class="card__description">{{ $evento->descripcion }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-center">
                                <span class="d-block">{{ \Carbon\Carbon::parse($evento->fecha)->format('d') }}</span>
                                <h6 class="text-light text-uppercase mb-0">
                                    {{ \Carbon\Carbon::parse($evento->fecha)->translatedFormat('F') }}
                                </h6>
                                <span class="d-block">{{ \Carbon\Carbon::parse($evento->fecha)->format('Y') }}</span>
                            </div>

                            <!-- Columna derecha: Hora -->
                            <div class="text-end">
                                <span class="fw-bold hora">
                                    <i class="bi bi-clock hora"></i>
                                    {{ \Carbon\Carbon::parse($evento->hora)->format('H:i') }}
                                </span>
                            </div>
                            @if (Auth::check() && Auth::user()->tipo_usuario == 'admin')
                                <div class="d-flex gap-2">
                                    <a href="{{ route('eventos.edit', $evento->id) }}" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
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
    function confirmDelete(eventoId) {
        if (confirm("¿Estás seguro de eliminar este evento?")) {
            document.querySelector('#delete-form-' + eventoId).submit();
        }
    }
</script>
@endsection