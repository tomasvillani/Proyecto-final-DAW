@extends('layouts.layout')

@section('title', 'Ver Reserva')

@section('content')
    <div class="container-fluid p-5">
        <div class="mb-5 text-center">
            <h1 class="display-3 text-uppercase mb-0">Ver Reserva de {{ $reserva->user->name }}</h1>
        </div>

        <div class="card">
            <div class="card-body">
                <form>
                    @csrf
                    @method('GET')

                    <div class="form-group">
                        <label for="clase">Clase</label>
                        <input type="text" class="form-control" id="clase" value="{{ $reserva->clase }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="dia">Día</label>
                        <input type="text" class="form-control" id="dia" value="{{ \Carbon\Carbon::parse($reserva->dia)->format('d/m/Y') }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="hora">Hora</label>
                        <input type="text" class="form-control" id="hora" value="{{ $reserva->hora }}" disabled>
                    </div>

                    <div class="form-group mt-4">
                        <a href="{{ route('mis-reservas.index', $reserva->user_id) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
