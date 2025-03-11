<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use App\Mail\ContactoMailable;
use Illuminate\Http\Request;
use App\Http\Controllers\ContactoController;

test('enviarFormulario envia el email correctamente', function () {
    // Configurar mailer para que use el log solo en pruebas
    Config::set('mail.default', 'log');  // Usar log en las pruebas

    // Simular los datos del formulario
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'subject' => 'Consulta',
        'message' => 'Este es un mensaje de prueba.',
    ];

    // Fake del correo
    Mail::fake();

    // Realizar la solicitud de tipo POST con los datos del formulario
    $response = $this->post('/enviar-correo', $data);

    // Verificar que el correo fue "enviado" (solo en el log)
    Mail::assertSent(ContactoMailable::class, function ($mail) use ($data) {
        // Verificamos que se haya enviado al correo correcto
        return $mail->hasTo('gymtinajo@gmail.com');
    });

    // Verificar la respuesta: la sesión debe contener el mensaje de éxito
    $response->assertSessionHas('success', 'Correo enviado correctamente');
});

test('enviarFormulario fails validation when missing fields', function () {
    // Configurar mailer para que use 'log' en las pruebas
    Config::set('mail.default', 'log');  // Esto asegura que usaremos log para las pruebas

    // Datos incompletos para la validación
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        // 'subject' y 'message' faltan
    ];

    // Fake del correo
    Mail::fake();

    // Simulamos el request y llamamos al método
    $response = $this->post('/enviar-correo', $data);

    // Esperamos que falle la validación
    $response->assertSessionHasErrors(['subject', 'message']);

    // Verificar que no se envió el correo
    Mail::assertNotSent(ContactoMailable::class);
});
