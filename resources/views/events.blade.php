@extends('layouts.layout')

@section('title','Eventos')

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

    <!-- Blog Start -->
    <div class="container-fluid p-5">
        <div class="row g-5">
            <!-- Blog list Start -->
            <div class="col-lg-8">
                <div class="row g-5">
                    @foreach($eventos as $evento)
                        <div class="col-md-6">
                            <div class="blog-item">
                                <div class="position-relative overflow-hidden rounded-top">
                                    <img class="img-fluid" src="{{ asset('storage/' . $evento->imagen) }}" alt="{{ $evento->titulo }}">
                                </div>
                                <div class="bg-dark d-flex align-items-center rounded-bottom p-4">
                                    <div class="flex-shrink-0 text-center text-secondary border-end border-secondary pe-3 me-3">
                                        <span>{{ \Carbon\Carbon::parse($evento->fecha)->format('d') }}</span>
                                        <h6 class="text-light text-uppercase mb-0">{{ \Carbon\Carbon::parse($evento->fecha)->format('F') }}</h6>
                                        <span>{{ \Carbon\Carbon::parse($evento->fecha)->format('Y') }}</span>
                                    </div>
                                    <a class="h5 text-uppercase text-light" href="{{ route('eventos.show', $evento->id) }}">
                                        {{ $evento->titulo }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Paginación -->
                    <div class="col-12">
                        {{ $eventos->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            <!-- Blog list End -->
        </div>
    </div>
    <!-- Blog End -->
@endsection
