@extends('layouts.layout')

@section('title', 'Restablecer Contraseña')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="form-box p-4" style="max-width: 400px; width: 100%; margin-top: 20px;">
        <form method="POST" action="{{ route('password.email') }}" class="form">
            @csrf
            <h2 class="text-center">Restablecer Contraseña</h2>
            <p class="text-center text-muted">Introduce tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>
            
            <div class="form-container mt-4">
                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input id="email" class="form-control input" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Correo electrónico">
                    @if ($errors->has('email'))
                        <div class="text-danger mt-2">{{ $errors->first('email') }}</div>
                    @endif
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <button type="submit" class="btn btn-primary">
                    Enviar enlace de restablecimiento
                </button>
            </div>
        </form>

        <div class="form-section mt-3">
            <p class="text-center">¿Ya tienes cuenta? <a href="{{ route('login') }}">Iniciar sesión</a></p>
        </div>
    </div>
</div>
@endsection
