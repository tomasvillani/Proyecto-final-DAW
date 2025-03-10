<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('La contraseña se actualiza', function () {
    // Crear un usuario con los nuevos campos
    $user = new User();
    $user->dni = '87654321X'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = Hash::make('password'); // Contraseña original
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado
    $user->save();

    // Realizar la solicitud para cambiar la contraseña
    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->put('/password', [
            'current_password' => 'password', // Contraseña actual
            'password' => 'new-password', // Nueva contraseña
            'password_confirmation' => 'new-password', // Confirmación de la nueva contraseña
        ]);

    // Verificar que no haya errores en la sesión y que la redirección sea correcta
    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    // Verificar que la contraseña se haya actualizado correctamente
    $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
});

test('La contraseña debe ser correcta para cambiarla', function () {
    // Crear un usuario con los nuevos campos
    $user = new User();
    $user->dni = '87654321X'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = Hash::make('password'); // Contraseña original
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado
    $user->save();

    // Realizar la solicitud con una contraseña incorrecta
    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->put('/password', [
            'current_password' => 'wrong-password', // Contraseña incorrecta
            'password' => 'new-password', // Nueva contraseña
            'password_confirmation' => 'new-password', // Confirmación de la nueva contraseña
        ]);

    // Verificar que el campo 'current_password' tiene errores de validación
    $response
        ->assertSessionHasErrorsIn('updatePassword', 'current_password')
        ->assertRedirect('/profile');
});
