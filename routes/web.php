<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ClienteController;
use Illuminate\Support\Facades\Route;

Route::post('/enviar-correo', [ContactoController::class, 'enviarFormulario'])->name('enviar_correo');

Route::post('/inscribirse', [ContactoController::class, 'inscribirse'])->name('inscribirse');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/classes', [HorarioController::class,'cargar_horarios']);

Route::get('/trainers', function () {
    return view('trainers');
});

Route::get('/events', function () {
    return view('events');
});

Route::get('/details', function () {
    return view('details');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['admin'])->group(function () {
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
    
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
