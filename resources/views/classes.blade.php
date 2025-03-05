@extends('layouts.layout')

@section('title', 'Clases')

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
                            <div class="hora-item rounded text-center py-5 px-3 {{ $hora->disponible ? 'bg-dark' : 'bg-danger' }}" 
                                id="hora-item-{{ $hora->id }}" > 

                                <h6 class="text-uppercase text-light mb-3">{{ $hora->hora_inicio }} - {{ $hora->hora_fin }}</h6>
                                <h5 class="text-uppercase {{ $hora->disponible ? 'text-primary' : 'text-white' }} titulo_clase">{{ $hora->clase }}</h5>

                                <p class="text-white">
                                    {{ $hora->disponible ? '' : 'No Disponible' }}
                                </p>

                                @if (Auth::check() && Auth::user()->tipo_usuario == 'admin') <!-- Solo visible para el administrador -->
                                    <form action="{{ route('cambiar_disponibilidad', $hora->id) }}" method="POST" class="d-inline" id="form-switch-{{ $hora->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" 
                                                id="switch_{{ $hora->id }}" 
                                                {{ $hora->disponible ? 'checked' : '' }}
                                                onchange="cambiarDisponibilidad({{ $hora->id }}, this)">
                                            <label class="form-check-label {{ $hora->disponible ? '' : 'text-white' }} disponibilidad" for="switch_{{ $hora->id }}">Disponibilidad</label>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    </div>
    <!-- Class Timetable End -->

    <!-- JavaScript para actualizar el color de fondo dinámicamente -->
    <script>
        function cambiarDisponibilidad(horaId, checkbox) {
            // Obtener el contenedor de la hora
            let horaItem = document.querySelector(`#hora-item-${horaId}`);
            let horaText = horaItem.querySelector('p');
            let tituloClase = horaItem.querySelector('.titulo_clase');
            let disponibilidadLabel = horaItem.querySelector('.form-check-label');

            // Cambiar dinámicamente las clases dependiendo de si está disponible o no
            if (checkbox.checked) {
                horaItem.classList.remove('bg-danger');
                horaItem.classList.add('bg-dark'); // Color de fondo cuando está disponible
                tituloClase.classList.remove('text-white');
                tituloClase.classList.add('text-primary'); // Cambiar el color del texto del título a color primario
                disponibilidadLabel.classList.remove('text-white');
                // Si está disponible, no mostrar el texto de 'No Disponible'
                horaText.textContent = ''; 
            } else {
                horaItem.classList.remove('bg-dark');
                horaItem.classList.add('bg-danger'); // Color de fondo cuando no está disponible
                tituloClase.classList.remove('text-primary');
                tituloClase.classList.add('text-white'); // Cambiar el color del texto del título a blanco
                disponibilidadLabel.classList.add('text-white'); // Cambiar el color del texto de disponibilidad a blanco
                // Mostrar 'No Disponible' cuando no lo esté
                horaText.textContent = 'No Disponible';
            }

            // Obtener el token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Realizar la solicitud AJAX para actualizar la disponibilidad
            fetch(`/cambiar-disponibilidad/${horaId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    disponible: checkbox.checked
                })
            })
            .then(response => response.json())
            .then(data => {
                // Verificar si la respuesta fue exitosa
                if (data.success) {
                    console.log('Disponibilidad actualizada correctamente.');
                } else {
                    console.error('Error al actualizar la disponibilidad');
                }
            })
            .catch(error => {
                console.error('Error en la solicitud:', error);
            });
        }
    </script>
@endsection