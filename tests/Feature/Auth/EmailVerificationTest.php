<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

test('Se muestra la pantalla de verificación de email', function () {
    // Crear un usuario con los nuevos campos
    $user = new User();
    $user->dni = '87654321X'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = bcrypt('password');
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado
    $user->save();

    // Asegurarse de que el usuario no esté verificado
    $this->assertFalse($user->hasVerifiedEmail());

    // Realizar la solicitud de la página de verificación de correo
    $response = $this->actingAs($user)->get('/verify-email');

    // Verificar que la página de verificación de correo se carga correctamente
    $response->assertStatus(200);
});

test('El email puede ser verficado', function () {
    // Crear un usuario no verificado
    $user = new User();
    $user->dni = '87654321X'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = bcrypt('password');
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado
    $user->save();

    // Asegurarse de que el usuario no esté verificado
    $this->assertFalse($user->hasVerifiedEmail());

    // Falsificar los eventos para comprobar que se dispara el evento de verificación
    Event::fake();

    // Crear una URL de verificación firmada
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify', // Nombre de la ruta para la verificación
        now()->addMinutes(60),  // Expira en 60 minutos
        ['id' => $user->id, 'hash' => sha1($user->email)] // El hash debe ser el sha1 del email
    );

    // Acceder a la URL de verificación
    $response = $this->actingAs($user)->get($verificationUrl);

    // Verificar que el evento Verified fue disparado
    Event::assertDispatched(Verified::class);

    // Asegurarse de que el usuario ahora tiene el correo verificado
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();

    // Verificar la redirección al dashboard (u otra página de destino configurada)
    $response->assertRedirect(route('dashboard', absolute: false).'?verified=1');
});

test('El email no se verifica con un hash inválido', function () {
    // Crear un usuario no verificado
    $user = new User();
    $user->dni = '87654321X'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = bcrypt('password');
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado
    $user->save();

    // Asegurarse de que el usuario no esté verificado
    $this->assertFalse($user->hasVerifiedEmail());

    // Crear una URL de verificación firmada con un hash incorrecto
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')] // El hash debe ser incorrecto
    );

    // Acceder a la URL de verificación con el hash incorrecto
    $this->actingAs($user)->get($verificationUrl);

    // Verificar que el correo no fue verificado
    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});
