<?php

use App\Models\User;

test('confirm password screen can be rendered', function () {
    // Crear un usuario con los nuevos campos
    $user = new User();
    $user->dni = '87654321X'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = bcrypt('password'); // Asignar una contraseña válida
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado
    $user->save();

    // Asegurarse de que el usuario está autenticado
    $response = $this->actingAs($user)->get('/confirm-password');

    // Verificar que la pantalla de confirmación de contraseña se carga correctamente
    $response->assertStatus(200);
});

test('password can be confirmed', function () {
    // Crear un usuario con los nuevos campos
    $user = new User();
    $user->dni = '87654321X'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = bcrypt('password'); // Asignar una contraseña válida
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado
    $user->save();

    // Asegurarse de que el usuario está autenticado
    $response = $this->actingAs($user)->post('/confirm-password', [
        'password' => 'password', // La contraseña correcta
    ]);

    // Verificar que la redirección es correcta después de confirmar la contraseña
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});

test('password is not confirmed with invalid password', function () {
    // Crear un usuario con los nuevos campos
    $user = new User();
    $user->dni = '87654321X'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = bcrypt('password'); // Asignar una contraseña válida
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado
    $user->save();

    // Asegurarse de que el usuario está autenticado
    $response = $this->actingAs($user)->post('/confirm-password', [
        'password' => 'wrong-password', // Contraseña incorrecta
    ]);

    // Verificar que se ha producido un error de validación
    $response->assertSessionHasErrors();
});
