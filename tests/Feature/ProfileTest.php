<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Tarifa;
use App\Models\Horario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

test('página del perfil se muestra correctamente', function () {
    $user = new User();
    $user->dni = '12345678Z'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User';  // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = Hash::make('password');
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado (cliente)
    $user->save();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('la información del perfil se puede actualizar', function () {
    $user = new User();
    $user->dni = '87654321X'; // DNI válido
    $user->name = 'Old Name';
    $user->surname = 'Old Surname'; // Apellido agregado
    $user->email = 'old@example.com';
    $user->password = Hash::make('password');
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado (cliente)
    $user->save();

    // Actualiza el perfil
    $response = $this->actingAs($user)->patch('/profile', [
        'name' => 'Test User',
        'surname' => 'User', // Apellido actualizado
        'email' => 'test@example.com',
    ]);

    // Verifica que no haya errores y que redirija al perfil
    $response->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    // Refresca el usuario y revisa los valores
    $user->refresh();

    // Depuración para verificar qué valores tiene el usuario

    // Verifica que el nombre y apellido se hayan actualizado
    $this->assertSame('Test User', trim($user->name));
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);  // Verifica que 'email_verified_at' esté en null
});

test('La verificación del correo electrónico no cambia cuando la dirección de correo electrónico no se ha modificado.', function () {
    $user = new User();
    $user->dni = '11223344A'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = Hash::make('password');
    $user->email_verified_at = now();
    $user->tipo_usuario = 'cliente'; // Tipo de usuario agregado (cliente)
    $user->save();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'surname' => 'User', // Apellido no cambiado
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('El usuario puede eliminar su cuenta', function () {
    $user = new User();
    $user->dni = '99887766M'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = Hash::make('password');
    $user->tipo_usuario = 'admin'; // Tipo de usuario agregado (admin)
    $user->save();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('El usuario debe poner la contraseña correcta para eliminar su cuenta', function () {
    $user = new User();
    $user->dni = '98765432L'; // DNI válido
    $user->name = 'Test User';
    $user->surname = 'User'; // Apellido agregado
    $user->email = 'test@example.com';
    $user->password = Hash::make('password');
    $user->tipo_usuario = 'admin'; // Tipo de usuario agregado (admin)
    $user->save();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});

test('El perfil muestra los datos correctos para el usuario con una tarifa expirada.', function () {
    $user = new User([
        'name' => 'Juan',
        'surname' => 'Pérez',
        'email' => 'juan.perez@example.com',
        'password' => bcrypt('password'),
        'tipo_usuario' => 'cliente',  // Tipo de usuario
        'dni' => '12345678A',  // DNI
        'tarifa_id' => 1,
        'fecha_expiracion' => now()->subDays(1), // Tarifa vencida
    ]);
    $user->save();
    
    $tarifa = new Tarifa([
        'id' => 1,
        'nombre' => 'Tarifa Test',
        'precio' => 50.00,  // Agregado precio
        'duracion' => 30,   // Duración de 30 días
        'descripcion' => 'Descripción de la tarifa',  // Descripción agregada
    ]);
    $tarifa->save();
    $user->tarifa()->associate($tarifa);
    $user->save();

    // Actuar como el usuario
    $this->actingAs($user);

    // Realizamos la solicitud GET para cargar el perfil
    $response = $this->get('/profile'); // Asegúrate de que la ruta sea la correcta

    // Verificamos que el estado de la respuesta sea correcto
    $response->assertStatus(200);

    // Verificamos que la variable tarifaVencida esté como true (indica que la tarifa ha vencido)
    $response->assertViewHas('tarifaVencida', true);

    // Verificamos que el usuario se pase a la vista correctamente
    $response->assertViewHas('user', $user);

    // Verificamos que la tarifa haya sido eliminada de la relación en el usuario
    $user->refresh(); // Recargamos el usuario para obtener los datos actualizados
    $this->assertNull($user->tarifa_id);
    $this->assertNull($user->fecha_inicio);
    $this->assertNull($user->fecha_expiracion);
    $this->assertEmpty($user->clases);  // Verificamos que las clases asociadas hayan sido eliminadas
});

test('no permitir cambiar tarifa cuando ya tiene una asignada', function () {
    // Crear una tarifa
    $tarifa = new Tarifa();
    $tarifa->id = 1;
    $tarifa->nombre = 'Tarifa Básica';
    $tarifa->precio = 30.00;
    $tarifa->duracion = 30;
    $tarifa->descripcion = 'Tarifa básica para clientes';
    $tarifa->save();

    // Crear un usuario con una tarifa asignada
    $user = new User();
    $user->name = 'Carlos';
    $user->surname = 'López';
    $user->email = 'carlos.lopez@example.com';
    $user->password = bcrypt('password');
    $user->tipo_usuario = 'cliente';
    $user->dni = '23456789C';
    $user->tarifa_id = 1;
    $user->save();

    // Actuar como el usuario
    $this->actingAs($user)->from('/profile');

    // Intentar cambiar la tarifa
    $response = $this->post('/profile/cambiar-tarifa', [
        'tarifa_id' => $tarifa->id,
    ]);

    $user->refresh(); // Recargar el usuario

    // Verificar que la tarifa no ha cambiado
    $this->assertEquals(1, $user->tarifa_id);  // La tarifa debería seguir siendo la inicial (ID 1)

    // Verificar que se ha redirigido al perfil con un mensaje de error
    $response->assertRedirect(route('profile.edit'));  // Usar route() para redirigir correctamente
});

test('cambiar tarifa cuando no tiene una asignada', function () {
    // Crear un usuario sin tarifa asignada
    $user = new User();
    $user->name = 'Carlos';
    $user->surname = 'López';
    $user->email = 'carlos.lopez@example.com';
    $user->password = bcrypt('password');
    $user->tipo_usuario = 'cliente';
    $user->dni = '23456789C';
    $user->tarifa_id = null; // Sin tarifa asignada inicialmente
    $user->save();

    // Crear la tarifa
    $tarifa = new Tarifa();
    $tarifa->id = 1;
    $tarifa->nombre = 'Tarifa Básica';
    $tarifa->precio = 30.00;
    $tarifa->duracion = 30;
    $tarifa->descripcion = 'Tarifa básica para clientes';
    $tarifa->save();

    // Actuar como el usuario
    $this->actingAs($user)->from('/profile');

    // Intentar cambiar la tarifa
    $response = $this->post('/profile/cambiar-tarifa', [
        'tarifa_id' => $tarifa->id,
    ]);

    $user->refresh(); // Recargar el usuario

    // Verificar que la tarifa se ha cambiado
    $this->assertEquals($tarifa->id, $user->tarifa_id);
    $this->assertEquals($tarifa->precio, $user->tarifa->precio);
    $this->assertEquals($tarifa->descripcion, $user->tarifa->descripcion);

    // Verificar que se ha redirigido al perfil con un mensaje de éxito
    $response->assertRedirect(route('profile.edit'));  // Usar route() para redirigir correctamente
});