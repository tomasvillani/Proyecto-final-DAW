@extends('layouts.layout')

@section('title', Auth::user()->tipo_usuario == "admin" ? "Perfil del Administrador" : "Perfil del Usuario")

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">{{ Auth::user()->tipo_usuario == "admin" ? "Perfil del Administrador" : "Perfil del Usuario" }}</h2>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Actualizar Información del Perfil -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0">Actualizar Información</h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Actualizar Contraseña -->
            <div class="card shadow mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="m-0">Actualizar Contraseña</h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Eliminar Cuenta -->
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="m-0">Eliminar Cuenta</h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection