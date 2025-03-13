@extends('layouts.layout')  <!-- Usa 'layouts.layout' en lugar de 'layouts/layout' si es el nombre correcto de tu plantilla -->

@section('title', 'Iniciar sesión')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="form-box p-4" style="max-width: 400px; width: 100%; margin-top: 20px;">
        <form method="POST" action="{{ route('login') }}" class="form">
            @csrf
            <h2 class="text-center">Iniciar sesión</h2>
            <p class="text-center text-muted">Accede a tu cuenta con tu DNI.</p>
            
            <div class="form-container mt-4">
                <div class="mb-3">
                    <label for="dni" class="form-label">DNI</label>
                    <input id="dni" class="form-control input" type="text" name="dni" value="{{ old('dni') }}" required autocomplete="username" placeholder="DNI">
                    @if ($errors->has('dni'))
                        <div class="text-danger mt-2">{{ $errors->first('dni') }}</div>
                    @endif
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input id="password" class="form-control input" type="password" name="password" required autocomplete="current-password" placeholder="Contraseña">
                    @if ($errors->has('password'))
                        <div class="text-danger mt-2">{{ $errors->first('password') }}</div>
                    @endif
                </div>
                
                <!-- Remember Me -->
                <div class="mb-3 form-check d-flex align-items-center justify-content-center">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label class="form-check-label ms-2 mb-0" for="remember_me">Recordarme</label>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                @if (Route::has('password.request'))
                    <a class="text-muted" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif

                <button type="submit" class="btn btn-primary">
                    Iniciar sesión
                </button>
            </div>
        </form>

        <div class="form-section mt-3">
            <p class="text-center">¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a></p>
        </div>
    </div>
</div>
@endsection
