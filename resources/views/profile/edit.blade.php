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
        <div class="card shadow mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="m-0">Método de Pago</h5>
            </div>
        <div class="card-body">
            <form action="{{ route('perfil.actualizar-metodo-pago') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Método de Pago -->
                <div class="mb-3">
                    <label class="form-label">Método de Pago Actual:</label>
                    <p><strong>
                        @if(auth()->user()->metodo_pago === 'tarjeta')
                            Tarjeta de Crédito/Débito (**** **** **** {{ substr(auth()->user()->numero_tarjeta, -4) }})
                        @elseif(auth()->user()->metodo_pago === 'cuenta_bancaria')
                            Cuenta Bancaria (**** **** **** {{ substr(auth()->user()->cuenta_bancaria, -4) }})
                        @else
                            No registrado
                        @endif
                    </strong></p>
                </div>

                <div class="mb-3">
                    <label class="form-label">Seleccionar Nuevo Método de Pago:</label>
                    <select name="metodo_pago" id="metodo_pago" class="form-control" required onchange="toggleFields()">
                        <option value="tarjeta" {{ old('metodo_pago', auth()->user()->metodo_pago) == 'tarjeta' ? 'selected' : '' }}>Tarjeta de Crédito/Débito</option>
                        <option value="cuenta_bancaria" {{ old('metodo_pago', auth()->user()->metodo_pago) == 'cuenta_bancaria' ? 'selected' : '' }}>Cuenta Bancaria (IBAN)</option>
                    </select>
                    @error('metodo_pago')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Input para Tarjeta de Crédito -->
                <div class="mb-3" id="campo_tarjeta" style="{{ old('metodo_pago', auth()->user()->metodo_pago) == 'tarjeta' ? '' : 'display: none;' }}">
                    <label class="form-label">Número de Tarjeta:</label>
                    <input type="text" name="numero_tarjeta" id="numero_tarjeta" class="form-control" maxlength="19" placeholder="XXXX XXXX XXXX XXXX" value="{{ old('numero_tarjeta') }}">
                    @error('numero_tarjeta')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Input para Fecha de Caducidad -->
                <div class="mb-3" id="campo_caducidad" style="{{ old('metodo_pago', auth()->user()->metodo_pago) == 'tarjeta' ? '' : 'display: none;' }}">
                    <label class="form-label">Fecha de Caducidad:</label>
                    <input type="month" name="fecha_caducidad" id="fecha_caducidad" class="form-control" value="{{ old('fecha_caducidad') }}">
                    @error('fecha_caducidad')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Input para CVV -->
                <div class="mb-3" id="campo_cvv" style="{{ old('metodo_pago', auth()->user()->metodo_pago) == 'tarjeta' ? '' : 'display: none;' }}">
                    <label class="form-label">CVV:</label>
                    <input type="text" name="cvv" id="cvv" class="form-control" maxlength="4" placeholder="CVV" value="{{ old('cvv') }}">
                    @error('cvv')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Input para Cuenta Bancaria (IBAN) -->
                <div class="mb-3" id="campo_cuenta" style="{{ old('metodo_pago', auth()->user()->metodo_pago) == 'cuenta_bancaria' ? '' : 'display: none;' }}">
                    <label class="form-label">Número de Cuenta (IBAN):</label>
                    <input type="text" name="cuenta_bancaria" id="cuenta_bancaria" class="form-control" maxlength="34" placeholder="ES00 0000 0000 00 0000000000" value="{{ old('cuenta_bancaria') }}">
                    @error('cuenta_bancaria')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-secondary">Actualizar Método de Pago</button>
            </form>
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

<script>
    function toggleFields() {
        const metodoPago = document.getElementById('metodo_pago').value;
        
        // Campos de tarjeta
        const campoTarjeta = document.getElementById('campo_tarjeta');
        const campoCaducidad = document.getElementById('campo_caducidad');
        const campoCvv = document.getElementById('campo_cvv');
        
        // Campo de cuenta bancaria
        const campoCuenta = document.getElementById('campo_cuenta');
        
        // Mostrar los campos de tarjeta si elige "tarjeta", ocultarlos si elige "cuenta_bancaria"
        if (metodoPago === 'tarjeta') {
            campoTarjeta.style.display = 'block';
            campoCaducidad.style.display = 'block';
            campoCvv.style.display = 'block';
            campoCuenta.style.display = 'none';
        } else {
            campoTarjeta.style.display = 'none';
            campoCaducidad.style.display = 'none';
            campoCvv.style.display = 'none';
            campoCuenta.style.display = 'block';
        }
    }

    // Llamar a la función al cargar la página para verificar el valor inicial
    window.onload = toggleFields;
</script>
@endsection
