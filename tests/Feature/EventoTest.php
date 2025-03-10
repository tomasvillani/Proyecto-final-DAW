<?php

use App\Models\User;
use App\Models\Evento;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

// Test para verificar que solo un admin puede crear un evento
test('store evento fails for non-admin user', function () {
    // Crear un usuario normal (no admin) con un DNI válido
    $user = new User();
    $user->name = 'Juan';
    $user->surname = 'Pérez';
    $user->email = 'juan.perez@example.com';
    $user->password = bcrypt('password'); // Contraseña cifrada
    $user->tipo_usuario = 'cliente';  // Tipo de usuario 'cliente' en lugar de 'admin'
    $user->dni = '12345678A';  // DNI español válido
    $user->save();

    // Autenticar al usuario
    $this->actingAs($user);

    $data = [
        'titulo' => 'Evento Test',
        'descripcion' => 'Descripción del evento',
        'fecha' => '2025-03-15',
        'hora' => '12:00',
        'imagen' => null,
    ];

    $response = $this->post('/eventos', $data);

    // Asegurarse de que no se guardó el evento
    $this->assertDatabaseMissing('eventos', [
        'titulo' => 'Evento Test',
        'descripcion' => 'Descripción del evento',
    ]);

    // Verificar que la respuesta es un código de estado 403
    $response->assertStatus(403); // Verifica que sea 403 como esperas
});

// Test para verificar que un admin puede crear un evento
test('store evento allows admin to create event', function () {
    // Crear un usuario admin con un DNI válido
    $user = new User();
    $user->name = 'Ana';
    $user->surname = 'López';
    $user->email = 'ana.lopez@example.com';
    $user->password = bcrypt('password'); // Contraseña cifrada
    $user->tipo_usuario = 'admin';  // Tipo de usuario 'admin'
    $user->dni = '87654321B';  // DNI español válido
    $user->save();

    // Autenticar al usuario admin
    $this->actingAs($user);

    $data = [
        'titulo' => 'Evento Test',
        'descripcion' => 'Descripción del evento',
        'fecha' => '2025-03-15',
        'hora' => '12:00',
        'imagen' => null,
    ];

    $response = $this->post('/eventos', $data);

    // Verificar que el evento se haya guardado en la base de datos
    $this->assertDatabaseHas('eventos', [
        'titulo' => 'Evento Test',
        'descripcion' => 'Descripción del evento',
        'fecha' => '2025-03-15',
        'hora' => '12:00',
    ]);

    // Verificar redirección y mensaje de éxito
    $response->assertRedirect(route('eventos.index'));
    $response->assertSessionHas('success', 'Evento creado con éxito.');
});

// Test para la actualización de un evento (update)
test('update evento updates the event data', function () {
    // Crear un evento con un usuario admin
    $user = new User();
    $user->name = 'Carlos';
    $user->surname = 'García';
    $user->email = 'carlos.garcia@example.com';
    $user->password = bcrypt('password');
    $user->tipo_usuario = 'admin';
    $user->dni = '11223344C';  // DNI español válido
    $user->save();

    $this->actingAs($user);

    $evento = Evento::create([
        'titulo' => 'Evento a actualizar',
        'descripcion' => 'Descripción original',
        'fecha' => '2025-03-15',
        'hora' => '12:00',
        'imagen' => null,
    ]);

    $data = [
        'titulo' => 'Evento actualizado',
        'descripcion' => 'Descripción actualizada',
        'fecha' => '2025-03-20',
        'hora' => '15:00',
        'imagen' => null,
    ];

    $response = $this->put("/eventos/{$evento->id}", $data);

    // Verificar que los datos se actualizaron correctamente
    $this->assertDatabaseHas('eventos', [
        'titulo' => 'Evento actualizado',
        'descripcion' => 'Descripción actualizada',
        'fecha' => '2025-03-20',
        'hora' => '15:00',
    ]);

    $response->assertRedirect(route('eventos.index'));
    $response->assertSessionHas('success', 'Evento actualizado con éxito.');
});

// Test para eliminar un evento (destroy)
test('delete evento deletes the event', function () {
    // Crear un evento con un usuario admin
    $user = new User();
    $user->name = 'Laura';
    $user->surname = 'Sánchez';
    $user->email = 'laura.sanchez@example.com';
    $user->password = bcrypt('password');
    $user->tipo_usuario = 'admin';
    $user->dni = '33445566D';  // DNI español válido
    $user->save();

    $this->actingAs($user);

    $evento = Evento::create([
        'titulo' => 'Evento a eliminar',
        'descripcion' => 'Descripción a eliminar',
        'fecha' => '2025-03-15',
        'hora' => '12:00',
        'imagen' => null,
    ]);

    $response = $this->delete("/eventos/{$evento->id}");

    // Verificar que el evento fue eliminado
    $this->assertDatabaseMissing('eventos', [
        'titulo' => 'Evento a eliminar',
        'descripcion' => 'Descripción a eliminar',
    ]);

    $response->assertRedirect(route('eventos.index'));
    $response->assertSessionHas('success', 'Evento eliminado con éxito.');
});

// Test para actualizar un evento con una nueva imagen
test('update evento updates with new image', function () {
    // Crear un evento con un usuario admin
    $user = new User();
    $user->name = 'Pedro';
    $user->surname = 'Fernández';
    $user->email = 'pedro.fernandez@example.com';
    $user->password = bcrypt('password');
    $user->tipo_usuario = 'admin';
    $user->dni = '99887766E';  // DNI español válido
    $user->save();

    $this->actingAs($user);

    $evento = Evento::create([
        'titulo' => 'Evento con imagen',
        'descripcion' => 'Descripción con imagen',
        'fecha' => '2025-03-15',
        'hora' => '12:00',
        'imagen' => null,
    ]);

    // Simular una imagen
    Storage::fake('public');
    $imagen = UploadedFile::fake()->image('evento.jpg');

    $data = [
        'titulo' => 'Evento actualizado con imagen',
        'descripcion' => 'Descripción con nueva imagen',
        'fecha' => '2025-03-20',
        'hora' => '15:00',
        'imagen' => $imagen,
    ];

    $response = $this->put("/eventos/{$evento->id}", $data);

    // Verificar que la imagen fue almacenada
    Storage::disk('public')->assertExists('eventos/' . $imagen->hashName());

    // Verificar que los datos del evento se actualizaron
    $this->assertDatabaseHas('eventos', [
        'titulo' => 'Evento actualizado con imagen',
        'descripcion' => 'Descripción con nueva imagen',
        'fecha' => '2025-03-20',
        'hora' => '15:00',
        'imagen' => 'eventos/' . $imagen->hashName(),
    ]);

    $response->assertRedirect(route('eventos.index'));
    $response->assertSessionHas('success', 'Evento actualizado con éxito.');
});

// Test para eliminar la imagen al actualizar un evento
test('update evento deletes the image if checkbox is checked', function () {
    // Crear un evento con un usuario admin
    $user = new User();
    $user->name = 'Sofia';
    $user->surname = 'Ruiz';
    $user->email = 'sofia.ruiz@example.com';
    $user->password = bcrypt('password');
    $user->tipo_usuario = 'admin';
    $user->dni = '11223344F';  // DNI español válido
    $user->save();

    $this->actingAs($user);

    $evento = Evento::create([
        'titulo' => 'Evento con imagen',
        'descripcion' => 'Descripción con imagen',
        'fecha' => '2025-03-15',
        'hora' => '12:00',
        'imagen' => 'eventos/imagen_existente.jpg',
    ]);

    // Simular la eliminación de la imagen
    $data = [
        'titulo' => 'Evento sin imagen',
        'descripcion' => 'Descripción sin imagen',
        'fecha' => '2025-03-20',
        'hora' => '15:00',
        'eliminar_imagen' => 'on',
    ];

    $response = $this->put("/eventos/{$evento->id}", $data);

    // Verificar que la imagen fue eliminada
    Storage::disk('public')->assertMissing('eventos/imagen_existente.jpg');

    // Verificar que los datos del evento se actualizaron correctamente
    $this->assertDatabaseHas('eventos', [
        'titulo' => 'Evento sin imagen',
        'descripcion' => 'Descripción sin imagen',
        'fecha' => '2025-03-20',
        'hora' => '15:00',
        'imagen' => null,
    ]);

    $response->assertRedirect(route('eventos.index'));
    $response->assertSessionHas('success', 'Evento actualizado con éxito.');
});
