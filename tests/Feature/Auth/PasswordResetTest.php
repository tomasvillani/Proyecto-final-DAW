<?php

use App\Models\User;
use App\Notifications\CustomResetPassword;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Hash;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');
    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    // Crear usuario con los nuevos campos
    $user = new User();
    $user->dni = '12345678Z'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = Hash::make('password');
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado
    $user->save();

    // Realizar solicitud de restablecimiento de contraseña
    $response = $this->post('/forgot-password', ['email' => $user->email]);

    // Verificar que la respuesta es una redirección (302)
    $response->assertStatus(302);

    // Verificar la redirección hacia la URL correcta (si es la página de inicio, usa '/')
    $response->assertRedirect('/'); // Asegúrate de que esta sea la URL correcta

    // Verificar que se envió la notificación de restablecimiento de contraseña
    Notification::assertSentTo($user, \App\Notifications\CustomResetPassword::class, function ($notification) use ($user) {
        // Verificar que el token de la notificación es válido
        $this->assertNotEmpty($notification->token);

        return true;
    });

    // Verificar si la notificación fue realmente enviada
    $this->assertNotNull($user->fresh());
    $this->assertNotEmpty($user->email);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    // Crear usuario con los nuevos campos
    $user = new User();
    $user->dni = '87654321X'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = Hash::make('password');
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado
    $user->save();

    // Solicitar el link de restablecimiento de contraseña
    $this->post('/forgot-password', ['email' => $user->email]);

    // Verificar que la notificación de restablecimiento fue enviada
    Notification::assertSentTo($user, CustomResetPassword::class, function ($notification) {
        // Acceder a la pantalla de restablecimiento de contraseña
        $response = $this->get('/reset-password/'.$notification->token);
        $response->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    // Crear usuario con los nuevos campos
    $user = new User();
    $user->dni = '11223344A'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = Hash::make('password');
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado
    $user->save();

    // Solicitar el link de restablecimiento de contraseña
    $this->post('/forgot-password', ['email' => $user->email]);

    // Verificar que la notificación fue enviada
    Notification::assertSentTo($user, CustomResetPassword::class, function ($notification) use ($user) {
        // Verificamos que el token existe en la notificación
        $this->assertNotEmpty($notification->token);

        // Usamos el token para hacer la solicitud de restablecimiento de contraseña
        $response = $this->post('/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        // Verificar que no haya errores y redirige correctamente
        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'));

        // Asegurarse de que la contraseña se ha actualizado correctamente
        // Usamos `refresh()` para obtener el usuario más reciente desde la base de datos
        $user->refresh();
        $this->assertTrue(Hash::check('new-password', $user->password));

        return true;
    });
});
