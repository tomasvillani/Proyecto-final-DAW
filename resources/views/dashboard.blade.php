@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
<div class="py-5">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 text-center">
                <h2 class="fw-bold">¡Bienvenido al Dashboard, {{ Auth::user()->name }}!</h2>
                <p class="text-muted">Aquí puedes gestionar tu perfil y revisar información importante.</p>
            </div>
        </div>
    </div>
</div>
@endsection
