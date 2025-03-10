<?php

use App\Http\Controllers\ReservaController;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Tarifa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use function Pest\Laravel\{actingAs, get, post, delete, assertDatabaseHas, assertDatabaseMissing};

uses(RefreshDatabase::class);

test('cliente puede ver sus propias reservas', function () {
    $tarifa_personalizada = new Tarifa();
    $tarifa_personalizada->nombre = "Tarifa personalizada";
    $tarifa_personalizada->descripcion = "Elige las clases que tú quieras";
    $tarifa_personalizada->duracion = 30;
    $tarifa_personalizada->precio = 70;
    $tarifa_personalizada->save();

    $clienteUser = new User();
    $clienteUser->name = 'Juan';
    $clienteUser->surname = 'Pérez';
    $clienteUser->email = 'juan.perez@example.com';
    $clienteUser->tarifa_id = $tarifa_personalizada->id;
    $clienteUser->clases = ["Yoga", "Pilates"]; // Clases asignadas
    $clienteUser->password = Hash::make('password123');
    $clienteUser->tipo_usuario = 'cliente';
    $clienteUser->dni = '12345678A';
    $clienteUser->save();

    for ($i = 0; $i < 3; $i++) {
        $reserva = new Reserva();
        $reserva->user_id = $clienteUser->id;
        $reserva->clase = 'Yoga';
        $reserva->dia = Carbon::tomorrow()->format('Y-m-d');
        $reserva->hora = '10:00';
        $reserva->save();
    }

    actingAs($clienteUser);
    $response = get(route('mis-reservas.index', $clienteUser->id));

    $response->assertStatus(200);
    $response->assertViewIs('reservas.index');
    $response->assertViewHas('reservas');
    $response->assertViewHas('usuario');
});

test('cliente no puede ver reservas de otro usuario', function () {
    $clienteUser = new User();
    $clienteUser->name = 'Juan';
    $clienteUser->surname = 'Pérez';
    $clienteUser->email = 'juan.perez@example.com';
    $clienteUser->dni = '12345678A';
    $clienteUser->password = Hash::make('password123');
    $clienteUser->tipo_usuario = 'cliente';
    $clienteUser->save();

    $otroCliente = new User();
    $otroCliente->name = 'Pedro';
    $otroCliente->surname = 'González';
    $otroCliente->email = 'pedro.gonzalez@example.com';
    $otroCliente->dni = '87654321B';
    $otroCliente->password = Hash::make('password456');
    $otroCliente->tipo_usuario = 'cliente';
    $otroCliente->save();

    actingAs($clienteUser);

    $response = get(route('mis-reservas.index', $otroCliente->id));

    $response->assertStatus(403);
});

test('cliente puede crear una reserva', function () {
    $tarifa_personalizada = new Tarifa();
    $tarifa_personalizada->nombre = "Tarifa personalizada";
    $tarifa_personalizada->descripcion = "Elige las clases que tú quieras";
    $tarifa_personalizada->duracion = 30;
    $tarifa_personalizada->precio = 70;
    $tarifa_personalizada->save();

    $clienteUser = new User();
    $clienteUser->name = 'Juan';
    $clienteUser->surname = 'Pérez';
    $clienteUser->dni = '12345678A';
    $clienteUser->email = 'juan.perez@example.com';
    $clienteUser->tarifa_id = $tarifa_personalizada->id;
    $clienteUser->clases = ["Yoga", "Pilates"]; // Clases asignadas
    $clienteUser->password = Hash::make('password123');
    $clienteUser->tipo_usuario = 'cliente';
    $clienteUser->save();

    actingAs($clienteUser);

    $data = [
        'user_id' => $clienteUser->id,
        'clase' => 'Yoga',
        'dia' => Carbon::tomorrow()->format('Y-m-d'),
        'hora' => '10:00'
    ];

    $response = post(route('mis-reservas.store'), $data);

    $response->assertRedirect(route('mis-reservas.index', $clienteUser->id));
    $response->assertSessionHas('success');
    assertDatabaseHas('reservas', $data);
});

test('cliente no puede crear reserva para fecha pasada', function () {
    $tarifa_personalizada = new Tarifa();
    $tarifa_personalizada->nombre = "Tarifa personalizada";
    $tarifa_personalizada->descripcion = "Elige las clases que tú quieras";
    $tarifa_personalizada->duracion = 30;
    $tarifa_personalizada->precio = 70;
    $tarifa_personalizada->save();
    
    $clienteUser = new User();
    $clienteUser->name = 'Juan';
    $clienteUser->surname = 'Pérez';
    $clienteUser->email = 'juan.perez@example.com';
    $clienteUser->dni = '12345678A';
    $clienteUser->tarifa_id = $tarifa_personalizada->id;
    $clienteUser->clases = ["Yoga", "Pilates"]; // Clases asignadas
    $clienteUser->password = Hash::make('password123');
    $clienteUser->tipo_usuario = 'cliente';
    $clienteUser->save();

    actingAs($clienteUser);

    $data = [
        'user_id' => $clienteUser->id,
        'clase' => 'Yoga',
        'dia' => Carbon::yesterday()->format('Y-m-d'),
        'hora' => '10:00'
    ];

    $response = post(route('mis-reservas.store'), $data);

    $response->assertSessionHasErrors(['dia']);
    assertDatabaseMissing('reservas', [
        'user_id' => $clienteUser->id,
        'dia' => Carbon::yesterday()->format('Y-m-d')
    ]);
});

test('cliente no puede crear reserva duplicada', function () {
    $tarifa_personalizada = new Tarifa();
    $tarifa_personalizada->nombre = "Tarifa personalizada";
    $tarifa_personalizada->descripcion = "Elige las clases que tú quieras";
    $tarifa_personalizada->duracion = 30;
    $tarifa_personalizada->precio = 70;
    $tarifa_personalizada->save();
    
    $clienteUser = new User();
    $clienteUser->name = 'Juan';
    $clienteUser->surname = 'Pérez';
    $clienteUser->email = 'juan.perez@example.com';
    $clienteUser->dni = '12345678A';
    $clienteUser->tarifa_id = $tarifa_personalizada->id;
    $clienteUser->clases = ["Yoga", "Pilates"]; // Clases asignadas
    $clienteUser->password = Hash::make('password123');
    $clienteUser->tipo_usuario = 'cliente';
    $clienteUser->save();

    actingAs($clienteUser);

    $reservaExistente = new Reserva();
    $reservaExistente->user_id = $clienteUser->id;
    $reservaExistente->clase = 'Yoga';
    $reservaExistente->dia = Carbon::tomorrow()->format('Y-m-d');
    $reservaExistente->hora = '10:00';
    $reservaExistente->save();

    $data = [
        'user_id' => $clienteUser->id,
        'clase' => 'Yoga',
        'dia' => Carbon::tomorrow()->format('Y-m-d'),
        'hora' => '10:00'
    ];

    $response = post(route('mis-reservas.store'), $data);

    $response->assertRedirect();
    $response->assertSessionHas('error');
    $this->assertEquals(1, Reserva::where([
        'user_id' => $clienteUser->id,
        'clase' => 'Yoga',
        'dia' => Carbon::tomorrow()->format('Y-m-d'),
        'hora' => '10:00'
    ])->count());
});

test('cliente puede eliminar su reserva', function () {
    $clienteUser = new User();
    $clienteUser->name = 'Juan';
    $clienteUser->surname = 'Pérez';
    $clienteUser->email = 'juan.perez@example.com';
    $clienteUser->dni = '12345678A';
    $clienteUser->password = Hash::make('password123');
    $clienteUser->tipo_usuario = 'cliente';
    $clienteUser->save();

    actingAs($clienteUser);

    $reserva = new Reserva();
    $reserva->user_id = $clienteUser->id;
    $reserva->clase = 'Yoga';
    $reserva->dia = Carbon::tomorrow()->format('Y-m-d');
    $reserva->hora = '10:00';
    $reserva->save();

    $response = delete(route('mis-reservas.destroy', $reserva->id));

    $response->assertRedirect(route('mis-reservas.index', $clienteUser->id));
    $response->assertSessionHas('success');
    assertDatabaseMissing('reservas', ['id' => $reserva->id]);
});

test('cliente no puede eliminar reserva de otro usuario', function () {
    $clienteUser = new User();
    $clienteUser->name = 'Juan';
    $clienteUser->surname = 'Pérez';
    $clienteUser->email = 'juan.perez@example.com';
    $clienteUser->dni = '12345678A';
    $clienteUser->password = Hash::make('password123');
    $clienteUser->tipo_usuario = 'cliente';
    $clienteUser->save();

    $otroCliente = new User();
    $otroCliente->name = 'Pedro';
    $otroCliente->surname = 'González';
    $otroCliente->email = 'pedro.gonzalez@example.com';
    $otroCliente->dni = '87654321B';
    $otroCliente->password = Hash::make('password456');
    $otroCliente->tipo_usuario = 'cliente';
    $otroCliente->save();

    $reservaOtroCliente = new Reserva();
    $reservaOtroCliente->user_id = $otroCliente->id;
    $reservaOtroCliente->clase = 'Yoga';
    $reservaOtroCliente->dia = Carbon::tomorrow()->format('Y-m-d');
    $reservaOtroCliente->hora = '10:00';
    $reservaOtroCliente->save();

    actingAs($clienteUser);

    $response = delete(route('mis-reservas.destroy', $reservaOtroCliente->id));

    $response->assertStatus(403);
    assertDatabaseHas('reservas', ['id' => $reservaOtroCliente->id]);
});

test('admin puede ver todas las reservas', function () {
    // Crear un administrador
    $adminUser = new User();
    $adminUser->tipo_usuario = 'admin';
    $adminUser->name = 'Admin';
    $adminUser->surname = 'Usuario';
    $adminUser->email = 'admin@example.com';
    $adminUser->dni = '12345678A'; // DNI del administrador
    $adminUser->password = Hash::make('password123');
    $adminUser->save();

    // Crear reservas para clientes
    $cliente = new User();
    $cliente->tipo_usuario = 'cliente';
    $cliente->name = 'Cliente';
    $cliente->surname = 'Prueba';
    $cliente->email = 'cliente@example.com';
    $cliente->dni = '87654321B'; // DNI del cliente
    $cliente->password = Hash::make('password123');
    $cliente->save();

    $reserva = new Reserva();
    $reserva->user_id = $cliente->id;
    $reserva->clase = 'Yoga';
    $reserva->dia = Carbon::tomorrow()->format('Y-m-d');
    $reserva->hora = '10:00';
    $reserva->save();

    actingAs($adminUser);

    $response = get(route('admin-reservas.index'));

    $response->assertStatus(200);
    $response->assertViewIs('reservas.admin.index');
    $response->assertViewHas('reservas');
});

test('admin puede crear una reserva', function () {
    $adminUser = new User();
    $adminUser->tipo_usuario = 'admin';
    $adminUser->name = 'Admin';
    $adminUser->surname = 'Usuario';
    $adminUser->email = 'admin@example.com';
    $adminUser->dni = '12345678A'; // DNI del administrador
    $adminUser->password = Hash::make('password123');
    $adminUser->save();

    $cliente = new User();
    $cliente->tipo_usuario = 'cliente';
    $cliente->name = 'Cliente';
    $cliente->surname = 'Prueba';
    $cliente->email = 'cliente@example.com';
    $cliente->dni = '87654321B'; // DNI del cliente
    $cliente->password = Hash::make('password123');
    $cliente->save();

    actingAs($adminUser);

    // Buscar el user_id del cliente usando su dni
    $clienteId = User::where('dni', $cliente->dni)->first()->id;

    $data = [
        'user_id' => $clienteId,  // Usar el user_id obtenido
        'dni'   => $cliente->dni,
        'clase' => 'Pilates',
        'dia' => Carbon::tomorrow()->format('Y-m-d'),
        'hora' => '11:00'
    ];

    $response = post(route('admin-reservas.store'), $data);

    $response->assertRedirect(route('admin-reservas.index'));
    $response->assertSessionHas('success');
    assertDatabaseHas('reservas', [
        'user_id' => $clienteId, // Verificar que la reserva se haya guardado con el user_id correcto
        'clase' => 'Pilates',
        'dia' => Carbon::tomorrow()->format('Y-m-d'),
        'hora' => '11:00'
    ]);
});

test('admin no puede crear una reserva duplicada', function () {
    $adminUser = new User();
    $adminUser->tipo_usuario = 'admin';
    $adminUser->name = 'Admin';
    $adminUser->surname = 'Usuario';
    $adminUser->email = 'admin@example.com';
    $adminUser->dni = '12345678A'; // DNI del administrador
    $adminUser->password = Hash::make('password123');
    $adminUser->save();

    $cliente = new User();
    $cliente->tipo_usuario = 'cliente';
    $cliente->name = 'Cliente';
    $cliente->surname = 'Prueba';
    $cliente->email = 'cliente@example.com';
    $cliente->dni = '87654321B'; // DNI del cliente
    $cliente->password = Hash::make('password123');
    $cliente->save();

    // Crear la primera reserva
    $reserva = new Reserva();
    $reserva->user_id = $cliente->id;
    $reserva->clase = 'Yoga';
    $reserva->dia = Carbon::tomorrow()->format('Y-m-d');
    $reserva->hora = '10:00';
    $reserva->save();

    actingAs($adminUser);

    // Buscar el user_id del cliente usando su dni
    $clienteId = User::where('dni', $cliente->dni)->first()->id;

    // Intentar crear una reserva duplicada con la misma clase, día y hora
    $data = [
        'user_id' => $clienteId,  // Usar el user_id obtenido
        'dni'   => $cliente->dni,
        'clase' => 'Yoga',
        'dia' => Carbon::tomorrow()->format('Y-m-d'),
        'hora' => '10:00'
    ];

    $response = post(route('admin-reservas.store'), $data);

    // Esperamos que la reserva no se cree y que haya un error
    $response->assertRedirect(); // Redirige normalmente, pero con un error en la sesión
    $response->assertSessionHasErrors(['dni']); // Validación de error por duplicación
    $this->assertEquals(1, Reserva::count()); // Solo una reserva debe existir
});

test('admin no puede crear una reserva con hora pasada', function () {
    $adminUser = new User();
    $adminUser->tipo_usuario = 'admin';
    $adminUser->name = 'Admin';
    $adminUser->surname = 'Usuario';
    $adminUser->email = 'admin@example.com';
    $adminUser->dni = '12345678A'; // DNI del administrador
    $adminUser->password = Hash::make('password123');
    $adminUser->save();

    $cliente = new User();
    $cliente->tipo_usuario = 'cliente';
    $cliente->name = 'Cliente';
    $cliente->surname = 'Prueba';
    $cliente->email = 'cliente@example.com';
    $cliente->dni = '87654321B'; // DNI del cliente
    $cliente->password = Hash::make('password123');
    $cliente->save();

    actingAs($adminUser);

    $data = [
        'user_id' => $cliente->id,
        'clase' => 'Pilates',
        'dia' => Carbon::yesterday()->format('Y-m-d'),
        'hora' => '10:00'
    ];

    $response = post(route('admin-reservas.store'), $data);

    $response->assertRedirect();
    $response->assertSessionHasErrors();
    assertDatabaseMissing('reservas', $data);
});

test('admin puede eliminar una reserva', function () {
    $adminUser = new User();
    $adminUser->tipo_usuario = 'admin';
    $adminUser->name = 'Admin';
    $adminUser->surname = 'Usuario';
    $adminUser->email = 'admin@example.com';
    $adminUser->dni = '12345678A'; // DNI del administrador
    $adminUser->password = Hash::make('password123');
    $adminUser->save();

    $cliente = new User();
    $cliente->tipo_usuario = 'cliente';
    $cliente->name = 'Cliente';
    $cliente->surname = 'Prueba';
    $cliente->email = 'cliente@example.com';
    $cliente->dni = '87654321B'; // DNI del cliente
    $cliente->password = Hash::make('password123');
    $cliente->save();

    // Crear la reserva
    $reserva = new Reserva();
    $reserva->user_id = $cliente->id;
    $reserva->clase = 'Yoga';
    $reserva->dia = Carbon::tomorrow()->format('Y-m-d');
    $reserva->hora = '10:00';
    $reserva->save();

    actingAs($adminUser)->from("/reservas");

    // Eliminar la reserva usando la ruta con el id
    $response = delete(route('admin-reservas.destroy', $reserva->id));

    // Ajustar la URL para que coincida con el formato que se usa en el redireccionamiento
    $response->assertRedirect(route('admin-reservas.index', $reserva->user_id));

    // Verificar que la reserva ha sido eliminada de la base de datos
    $response->assertSessionHas('success'); // La sesión debería tener el mensaje de éxito
    assertDatabaseMissing('reservas', ['id' => $reserva->id]); // Verifica que la reserva ya no está en la base de datos
});

test('admin puede editar una reserva', function () {
    $tarifa_personalizada = new Tarifa();
    $tarifa_personalizada->nombre = "Tarifa personalizada";
    $tarifa_personalizada->descripcion = "Elige las clases que tú quieras";
    $tarifa_personalizada->duracion = 30;
    $tarifa_personalizada->precio = 70;
    $tarifa_personalizada->save();

    $adminUser = new User();
    $adminUser->tipo_usuario = 'admin';
    $adminUser->name = 'Admin';
    $adminUser->surname = 'Usuario';
    $adminUser->email = 'admin@example.com';
    $adminUser->dni = '12345678A'; // DNI del administrador
    $adminUser->password = Hash::make('password123');
    $adminUser->save();

    $cliente = new User();
    $cliente->tipo_usuario = 'cliente';
    $cliente->name = 'Cliente';
    $cliente->tarifa_id = $tarifa_personalizada->id;
    $cliente->clases = ["Yoga", "Pilates"];
    $cliente->surname = 'Prueba';
    $cliente->email = 'cliente@example.com';
    $cliente->dni = '87654321B'; // DNI del cliente
    $cliente->password = Hash::make('password123');
    $cliente->save();

    // Crear una reserva
    $reserva = new Reserva();
    $reserva->user_id = $cliente->id;
    $reserva->clase = 'Yoga';
    $reserva->dia = Carbon::tomorrow()->format('Y-m-d');
    $reserva->hora = '10:00';
    $reserva->save();

    actingAs($adminUser)->from('/reservas');

    // Datos para actualizar la reserva (incluyendo el 'dni')
    $data = [
        'dni' => $cliente->dni,  // Mantener el 'dni' para validación
        'clase' => 'Pilates', // Nuevo valor para la clase
        'dia' => Carbon::tomorrow()->format('Y-m-d'),
        'hora' => '11:00' // Nueva hora para la reserva
    ];

    // Usamos 'post' (o 'put' con _method) para simular la solicitud PUT
    $response = post(route('admin-reservas.update', $reserva->id), array_merge($data, ['_method' => 'PUT']));

    $response->assertRedirect(route('admin-reservas.index'));
    $response->assertSessionHas('success');
    assertDatabaseHas('reservas', [
        'user_id' => $cliente->id, // Verificar que la reserva se haya guardado con el user_id correcto
        'clase' => 'Pilates',
        'dia' => Carbon::tomorrow()->format('Y-m-d'),
        'hora' => '11:00'
    ]);
});

test('admin no puede actualizar reserva con fecha pasada', function () {
    $adminUser = new User();
    $adminUser->tipo_usuario = 'admin';
    $adminUser->name = 'Admin';
    $adminUser->surname = 'Usuario';
    $adminUser->email = 'admin@example.com';
    $adminUser->dni = '12345678A'; // DNI del administrador
    $adminUser->password = Hash::make('password123');
    $adminUser->save();

    $cliente = new User();
    $cliente->tipo_usuario = 'cliente';
    $cliente->name = 'Cliente';
    $cliente->surname = 'Prueba';
    $cliente->email = 'cliente@example.com';
    $cliente->dni = '87654321B'; // DNI del cliente
    $cliente->password = Hash::make('password123');
    $cliente->save();

    $reserva = new Reserva();
    $reserva->user_id = $cliente->id;
    $reserva->clase = 'Yoga';
    $reserva->dia = Carbon::tomorrow()->format('Y-m-d');
    $reserva->hora = '10:00';
    $reserva->save();

    actingAs($adminUser);

    $data = [
        'dni' => $cliente->dni,
        'clase' => 'Pilates',
        'dia' => Carbon::yesterday()->format('Y-m-d'),  // Fecha pasada
        'hora' => '11:00'
    ];

    $response = post(route('admin-reservas.update', $reserva->id), array_merge($data, ['_method' => 'PUT']));

    $response->assertRedirect();
    $response->assertSessionHasErrors();  // Debe tener errores de validación
    assertDatabaseHas('reservas', [
        'user_id' => $cliente->id, // Verificar que la reserva NO se haya actualizado
        'clase' => 'Yoga',  // La clase debe seguir siendo 'Yoga', no 'Pilates'
        'dia' => Carbon::tomorrow()->format('Y-m-d'),
        'hora' => '10:00',
    ]);
});
