@extends('layouts.layout')

@section('title','Clases')

@section('content')
    <!-- Hero Start -->
    <div class="container-fluid bg-primary p-5 bg-hero mb-5">
        <div class="row py-5">
            <div class="col-12 text-center">
                <h1 class="display-2 text-uppercase text-white mb-md-4">Clases</h1>
                <a href="/" class="btn btn-primary py-md-3 px-md-5 me-3">Inicio</a>
                <a href="/classes" class="btn btn-light py-md-3 px-md-5">Clases</a>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Class Timetable Start -->
    <div class="container-fluid p-5">
        <div class="mb-5 text-center">
            <h5 class="text-primary text-uppercase">Horario de clases</h5>
            <h1 class="display-3 text-uppercase mb-0">Horas</h1>
        </div>
        <div class="tab-class text-center">
        <ul class="nav nav-pills d-inline-flex justify-content-center bg-dark text-uppercase rounded-pill mb-5">
            @foreach ($dias as $index => $dia)
                <li class="nav-item">
                    <a class="nav-link rounded-pill text-white {{ $index == 0 ? 'active' : '' }}" 
                    data-bs-toggle="pill" 
                    href="#tab-{{ $index }}">
                    {{ $dia }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content">
            @foreach ($dias as $index => $dia)
                <div id="tab-{{ $index }}" class="tab-pane {{ $index == 0 ? 'active' : '' }} p-0">
                    <div class="row g-5">
                        @foreach ($horarios->where('dia', $dia) as $hora)  
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="bg-dark rounded text-center py-5 px-3 hora-item">
                                    <h6 class="text-uppercase text-light mb-3">{{ $hora->hora_inicio }} - {{ $hora->hora_fin }}</h6>
                                    <h5 class="text-uppercase text-primary">{{ $hora->clase }}</h5>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    </div>
    <!-- Class Timetable Start -->
@endsection