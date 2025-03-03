@extends('layouts.layout')

@section('title', 'Eventos')

@section('content')
    <!-- Lista de Eventos -->
    <div class="container-fluid p-5">
        <div class="mb-5 text-center">
            <h1 class="display-3 text-uppercase mb-0">Eventos</h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($eventos as $evento)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $evento->titulo }}</td>
                        <td>{{ Str::limit($evento->descripcion, 50) }}</td>
                        <td>{{ $evento->fecha }}</td>
                        <td>{{ $evento->hora }}</td>
                        <td>
                            <!-- Botón de ver evento -->
                            <a href="{{ route('eventos.show', $evento->id) }}" class="btn btn-info">
                                <i class="bi bi-eye"></i> Ver
                            </a>

                            <!-- Botón de editar evento -->
                            <a href="{{ route('eventos.edit', $evento->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>

                            <!-- Formulario de eliminar evento -->
                            <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $evento->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $evento->id }})">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Botón para crear un nuevo evento -->
        <div class="mb-4">
            <a href="{{ route('eventos.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo Evento
            </a>
        </div>
    </div>
    <!-- Lista de Eventos End -->

    <!-- Script para confirmación de eliminación -->
    <script>
        function confirmDelete(eventoId) {
            if (confirm("¿Estás seguro de que deseas eliminar este evento?")) {
                document.getElementById('delete-form-' + eventoId).submit();
            }
        }
    </script>
@endsection