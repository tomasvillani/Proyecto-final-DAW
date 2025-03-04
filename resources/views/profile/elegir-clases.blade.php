@extends('layouts.layout')

@section('title', 'Seleccionar Clases')

@section('content')
<div class="container">
    <h2>Selecciona tus clases</h2>

    <!-- Mostrar errores si los hay -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/profile/elegir-clases') }}" method="POST">
        @csrf
        <div class="form-check">
            @foreach(App\Models\Horario::all()->unique('clase') as $horario)
                <input type="checkbox" name="clases[]" value="{{ $horario->clase }}">
                {{ $horario->clase }}<br>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary mt-3">Guardar Clases</button>
    </form>
</div>
@endsection
