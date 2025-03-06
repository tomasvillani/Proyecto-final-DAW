@extends('layouts.layout')

@section('title', Auth::user()->tipo_usuario == "admin" ? "Perfil del Administrador" : "Perfil del Usuario")

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">
        {{ Auth::user()->tipo_usuario == "admin" ? "Perfil del Administrador" : "Perfil del Usuario" }}
    </h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (Auth::user()->tipo_usuario == "cliente")
        <div class="card shadow mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="m-0">Tarifa Actual</h5>
            </div>
            <div class="card-body">
                @if (Auth::user()->tarifa_id)
                    @php
                        // Recuperamos la tarifa_id y la fecha de expiración del usuario
                        $tarifaId = Auth::user()->tarifa_id;
                        $fechaExpiracion = Auth::user()->fecha_expiracion;
                        $clases = Auth::user()->clases ?? [];

                        // Verificamos si es un array válido
                        if (!is_array($clases)) {
                            $clases = [];
                        }

                        // Verificamos si la tarifa ha vencido
                        $tarifaVencida = $fechaExpiracion < now();
                    @endphp

                    <p><strong>Tarifa Actual:</strong> {{ $tarifaId ? App\Models\Tarifa::find($tarifaId)->nombre : 'No disponible' }}</p>
                    <p><strong>Precio:</strong> {{ $tarifaId ? App\Models\Tarifa::find($tarifaId)->precio : 'No disponible' }}€</p>
                    <p><strong>Fecha de Inicio:</strong> {{ \Carbon\Carbon::parse(Auth::user()->fecha_inicio)->format('d-m-Y') }}</p>
                    <p><strong>Fecha de Expiración:</strong> {{ \Carbon\Carbon::parse($fechaExpiracion)->format('d-m-Y') }}</p>

                    @if (count($clases) > 0)
                        <strong>Clases Seleccionadas:</strong>
                        <ul>
                            @foreach($clases as $clase)
                                <li>{{ $clase }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-warning">
                            No tienes clases seleccionadas actualmente.
                        </div>
                    @endif

                    <!-- Mostrar formulario de cambio de tarifa solo si la tarifa ha vencido -->
                    @if ($tarifaVencida)
                        <!-- Cambio de Tarifa -->
                        <form action="{{ route('perfil.cambiar-tarifa') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Selecciona una Tarifa:</label>
                                <select name="tarifa_id" class="form-control" required>
                                    @foreach(App\Models\Tarifa::all() as $tarifa)
                                        <option value="{{ $tarifa->id }}" {{ $tarifaId == $tarifa->id ? 'selected' : '' }}>
                                            {{ $tarifa->nombre }} - {{ $tarifa->precio }}€
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info">Cambiar Tarifa</button>
                        </form>
                    @else
                        @if(Auth::user()->clases)
                            <div class="alert alert-info">
                                No puedes cambiar tu tarifa hasta que la actual haya vencido.
                            </div>
                        @else
                            <form action="/profile/elegir-clases" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">Seleccionar Clases</button>
                            </form>
                        @endif
                    @endif
                @else
                    <div class="alert alert-warning text-center">
                        No tienes tarifa asignada actualmente.
                    </div>

                    <!-- Selección de primera tarifa -->
                    <form action="{{ route('perfil.cambiar-tarifa') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Selecciona tu primera Tarifa:</label>
                            <select name="tarifa_id" class="form-control" required>
                                @foreach(App\Models\Tarifa::all() as $tarifa)
                                    <option value="{{ $tarifa->id }}">{{ $tarifa->nombre }} - {{ $tarifa->precio }}€</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Seleccionar Tarifa</button>
                    </form>
                @endif
            </div>
        </div>
    @endif

    <!-- Otras secciones del perfil (actualizar información, cambiar contraseña, etc.) -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Actualizar Información -->
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
