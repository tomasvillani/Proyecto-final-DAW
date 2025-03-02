@extends('layouts.layout')

@section('title', 'Clientes')

@section('content')
    <!-- Client List Start -->
    <div class="container-fluid p-5">
        <div class="mb-5 text-center">
            <h1 class="display-3 text-uppercase mb-0">Clientes Registrados</h1>
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
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Correo Electrónico</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr>
                        <td>
                            <!-- Botón de ver cliente -->
                            <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-info">
                                <i class="bi bi-eye"></i> Ver
                            </a>

                            <!-- Botón de editar con ícono -->
                            <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>

                            <!-- Formulario de eliminar con ícono y confirmación usando confirm() -->
                            <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $cliente->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $cliente->id }})">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                        <td>{{ $cliente->dni }}</td>
                        <td>{{ $cliente->name }} {{ $cliente->surname }}</td>
                        <td>{{ $cliente->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Botón para crear un nuevo cliente con ícono -->
        <div class="mb-4">
            <a href="{{ route('clientes.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nuevo Cliente
            </a>
        </div>
    </div>
    <!-- Client List End -->

    <!-- Agregar el script para confirmación con confirm() -->
    <script>
        function confirmDelete(clienteId) {
            // Mostrar ventana de confirmación usando confirm() nativo de JavaScript
            if (confirm("¿Estás seguro de que deseas eliminar a este cliente?")) {
                // Si el usuario confirma, enviar el formulario de eliminación
                document.getElementById('delete-form-' + clienteId).submit();
            }
        }
    </script>
@endsection
