<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ReservaController;
use App\Models\Evento;
use Illuminate\Support\Facades\Route;

Route::post('/enviar-correo', [ContactoController::class, 'enviarFormulario'])->name('enviar_correo');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/classes', [HorarioController::class, 'cargar_horarios'])->name('cargar_horarios');

Route::get('/trainers', function () {
    return view('trainers');
});

Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['admin'])->group(function () {
    // Rutas para clientes
    Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
    Route::get('/clientes/{cliente}', [ClienteController::class, 'show'])->name('clientes.show');
    Route::patch('/cambiar-disponibilidad/{id}', [HorarioController::class, 'cambiarDisponibilidad'])->name('cambiar_disponibilidad');

    // Rutas para eventos
    Route::get('/eventos/create', [EventoController::class, 'create'])->name('eventos.create');
    Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
    Route::get('/eventos/{evento}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
    Route::put('/eventos/{evento}', [EventoController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{evento}', [EventoController::class, 'destroy'])->name('eventos.destroy');

    Route::get('/reservas', [ReservaController::class, 'admin_index'])->name('admin-reservas.index'); // Ver todas las reservas
    Route::get('/reservas/create', [ReservaController::class, 'admin_create'])->name('admin-reservas.create'); // Crear una nueva reserva
    Route::post('/reservas', [ReservaController::class, 'admin_store'])->name('admin-reservas.store'); // Almacenar reserva
    Route::delete('/reservas/{id}', [ReservaController::class, 'admin_destroy'])->name('admin-reservas.destroy'); // Eliminar reserva
    Route::get('/buscar-usuario-dni', [ReservaController::class, 'buscarUsuarioPorDNI'])->name('reservas.buscarUsuarioPorDNI');
    Route::get('/admin/reservas/{userId}/ver/{reservaId}', [ReservaController::class, 'admin_ver'])->name('admin-reservas.ver');
    Route::get('/admin/reservas/{id}/edit', [ReservaController::class, 'admin_edit'])->name('admin-reservas.edit');
    Route::put('/admin/reservas/{id}', [ReservaController::class, 'admin_update'])->name('admin-reservas.update');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile/elegir-clases', [ProfileController::class, 'mostrarClases'])->name('perfil.elegir-clases');
    Route::post('/profile/elegir-clases', [ProfileController::class, 'guardarClases']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/cambiar-tarifa', [ProfileController::class, 'cambiarTarifa'])->name('perfil.cambiar-tarifa');
    Route::put('/profile/metodo-pago', [ProfileController::class, 'actualizarMetodoPago'])->name('perfil.actualizar-metodo-pago');
    
    // Rutas para las reservas
    Route::get('/mis-reservas/{userId}', [ReservaController::class, 'index'])->name('mis-reservas.index');
    Route::get('/reservas/dias', [ReservaController::class, 'getDiasDisponibles'])->name('reservas.getDiasDisponibles');
    Route::get('/reservas/horas', [ReservaController::class, 'getHorasDisponibles'])->name('reservas.getHorasDisponibles');

    // Mostrar el formulario para crear una nueva reserva
    Route::get('/mis-reservas/crear/{userId}', [ReservaController::class, 'create'])->name('mis-reservas.create');

    // Guardar una nueva reserva
    Route::post('/mis-reservas', [ReservaController::class, 'store'])->name('mis-reservas.store');

    // Ruta para ver una reserva
    Route::get('/mis-reservas/{userId}/ver/{reservaId}', [ReservaController::class, 'verReserva'])->name('mis-reservas.ver');

    // Eliminar una reserva
    Route::delete('/mis-reservas/{reserva}', [ReservaController::class, 'destroy'])->name('mis-reservas.destroy');

    Route::get('mis-reservas/{userId}/edit/{reservaId}', [ReservaController::class, 'edit'])->name('mis-reservas.editar');
    Route::put('mis-reservas/{userId}/update/{reservaId}', [ReservaController::class, 'update'])->name('mis-reservas.update');

    Route::get('/sin-tarifa', function () {
        return view('reservas.sin-tarifa');  // Vista que muestra el mensaje de error
    })->name('sin-tarifa');
});

require __DIR__.'/auth.php';
