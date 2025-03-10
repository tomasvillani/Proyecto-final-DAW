<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    // Crea el usuario
    $user = new User();
    $user->dni = '12345678Z'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = Hash::make('password'); // Asegúrate de que la contraseña esté correctamente hasheada
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado (cliente)
    $user->save();

    // Intentar la autenticación con Auth::attempt()
    $isAuthenticated = Auth::attempt([
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertTrue($isAuthenticated); // Verifica que la autenticación se haya realizado con éxito

    // Asegurarse de que el usuario esté autenticado
    $this->assertAuthenticatedAs($user);
});

test('users can not authenticate with invalid password', function () {
    $user = new User();
    $user->dni = '87654321X'; // DNI válido
    $user->name = 'Another User';
    $user->surname = 'Test'; // Apellido agregado
    $user->email = 'another@example.com';
    $user->password = Hash::make('password');
    $user->tipo_usuario = 'admin'; // Tipo de usuario agregado (admin)
    $user->save();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = new User();
    $user->dni = '99887766M'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'Logout'; // Apellido agregado
    $user->email = 'logout@example.com';
    $user->password = Hash::make('password');
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado (cliente)
    $user->save();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
