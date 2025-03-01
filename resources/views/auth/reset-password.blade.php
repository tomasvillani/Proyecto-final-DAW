@extends('layouts.layout')

@section('title', 'Restablecer Contraseña')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="form-box p-4" style="max-width: 400px; width: 100%; margin-top: 20px;">
        <form method="POST" action="{{ route('password.store') }}" class="form">
            @csrf
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <h2 class="text-center">Restablecer Contraseña</h2>
            <p class="text-center text-muted">Introduce tu nueva contraseña y confirma la nueva contraseña.</p>
            
            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input id="email" class="form-control input" type="email" name="email" value="{{ old('email', request()->email) }}" required autocomplete="email" placeholder="Correo electrónico">
                @if ($errors->has('email'))
                    <div class="text-danger mt-2">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <!-- Password -->
            <div class="mb-3 mt-4">
                <label for="password" class="form-label">Nueva Contraseña</label>
                <input id="password" class="form-control input" type="password" name="password" required autocomplete="new-password" placeholder="Nueva Contraseña">
                @if ($errors->has('password'))
                    <div class="text-danger mt-2">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <!-- Confirm Password -->
            <div class="mb-3 mt-4">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                <input id="password_confirmation" class="form-control input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmar Contraseña">
                @if ($errors->has('password_confirmation'))
                    <div class="text-danger mt-2">{{ $errors->first('password_confirmation') }}</div>
                @endif
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    Restablecer Contraseña
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
