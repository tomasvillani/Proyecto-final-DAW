@extends('layouts.layout')

@section('title', 'Registrarse')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="form-box p-4" style="max-width: 400px; width: 100%; margin-top: 20px;">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <h2 class="text-center">Registrarse</h2>
            <p class="text-center text-muted">Crea tu cuenta con tu DNI, nombre, correo y contraseña.</p>
            
            <div class="form-container mt-4">
                <!-- DNI -->
                <div class="mb-4 text-center mx-3">
                    <label for="dni" class="form-label">DNI</label>
                    <input id="dni" class="form-control input" type="text" name="dni" value="{{ old('dni') }}" required autofocus autocomplete="dni" placeholder="DNI">
                    @if ($errors->has('dni'))
                        <div class="text-danger mt-2">{{ $errors->first('dni') }}</div>
                    @endif
                </div>

                <!-- Name -->
                <div class="mb-4 text-center mx-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input id="name" class="form-control input" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Nombre completo">
                    @if ($errors->has('name'))
                        <div class="text-danger mt-2">{{ $errors->first('name') }}</div>
                    @endif
                </div>

                <!-- Surname -->
                <div class="mb-4 text-center mx-3">
                    <label for="surname" class="form-label">Apellido</label>
                    <input id="surname" class="form-control input" type="text" name="surname" value="{{ old('surname') }}" required autocomplete="surname" placeholder="Apellido completo">
                    @if ($errors->has('surname'))
                        <div class="text-danger mt-2">{{ $errors->first('surname') }}</div>
                    @endif
                </div>

                <!-- Email Address -->
                <div class="mb-4 text-center mx-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input id="email" class="form-control input" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Correo electrónico">
                    @if ($errors->has('email'))
                        <div class="text-danger mt-2">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <!-- Password -->
                <div class="mb-4 text-center mx-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input id="password" class="form-control input" type="password" name="password" required autocomplete="new-password" placeholder="Contraseña">
                    @if ($errors->has('password'))
                        <div class="text-danger mt-2">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <!-- Confirm Password -->
                <div class="mb-4 text-center mx-3">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input id="password_confirmation" class="form-control input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmar contraseña">
                    @if ($errors->has('password_confirmation'))
                        <div class="text-danger mt-2">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>

                <div class="d-flex justify-content-center align-items-center mt-4 mx-3">
                    <button type="submit" class="btn btn-primary">
                        Registrarse
                    </button>
                </div>
            </form>

            <div class="form-section mt-3 text-center">
                <p class="mt-3">¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
