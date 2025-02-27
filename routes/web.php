<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\HorarioController;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
