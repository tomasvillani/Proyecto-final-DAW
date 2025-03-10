<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'dni' => '12345678Z', // DNI válido
        'name' => 'Test User',
        'surname' => 'User', // Apellido agregado
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'tipo_usuario' => 'cliente', // Tipo de usuario agregado (cliente)
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));

    // Verifica que el nuevo usuario se haya guardado correctamente
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'dni' => '12345678Z',
        'name' => 'Test User',
        'surname' => 'User',
        'tipo_usuario' => 'cliente',
    ]);
});
