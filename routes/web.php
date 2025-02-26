<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactoController;
use Illuminate\Support\Facades\Route;

Route::post('/enviar-correo', [ContactoController::class, 'enviarFormulario'])->name('enviar_correo');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/classes', function () {
    return view('classes');
});

Route::get('/trainers', function () {
    return view('trainers');
});

Route::get('/blog', function () {
    return view('blog');
});

Route::get('/details', function () {
    return view('details');
});

Route::get('/testimonial', function () {
    return view('testimonial');
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
