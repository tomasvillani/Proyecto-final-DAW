<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Horario;
use App\Models\User;

test('cargar horarios correctamente', function () {
        // Crear horarios manualmente usando new Horario()
        $horario1 = new Horario();
        $horario1->hora_inicio = '08:00:00';
        $horario1->hora_fin = '10:00:00';
        $horario1->dia = 'Lunes';
        $horario1->clase = 'Matemáticas';
        $horario1->disponible = true;
        $horario1->save();

        $horario2 = new Horario();
        $horario2->hora_inicio = '10:00:00';
        $horario2->hora_fin = '12:00:00';
        $horario2->dia = 'Martes';
        $horario2->clase = 'Historia';
        $horario2->disponible = false;
        $horario2->save();

        // Hacer la petición a la ruta del controlador
        $response = $this->get('/classes');

        // Verificar que la respuesta sea exitosa
        $response->assertStatus(200);
        
        // Verificar que la vista tiene los datos correctos
        $response->assertViewHas('horarios');
        $response->assertViewHas('dias');
    });

    /** @test */
    test('cambiar disponibilidad de horario correctamente', function () {
        // Crear un horario manualmente usando new Horario()
        $horario = new Horario();
        $horario->hora_inicio = '14:00:00';
        $horario->hora_fin = '16:00:00';
        $horario->dia = 'Miércoles';
        $horario->clase = 'Ciencias';
        $horario->disponible = false;
        $horario->save();

        // Crear un usuario admin manualmente
        $admin = new User([
            'name' => 'Admin User',
            'surname' => 'Test',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'tipo_usuario' => 'admin', // Asumiendo que usas un campo 'tipo_usuario' para identificar el rol del usuario
            'dni' => '12345678',
        ]);
        $admin->save();

        // Autenticar al usuario admin
        $this->actingAs($admin);

        // Datos para actualizar
        $data = ['disponible' => true];

        // Hacer la petición PATCH al controlador
        $response = $this->patchJson("/cambiar-disponibilidad/{$horario->id}", $data);

        // Verificar que la respuesta es correcta
        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'disponible' => true]);

        // Verificar que el cambio se reflejó en la base de datos
        $this->assertDatabaseHas('horarios', [
            'id' => $horario->id,
            'disponible' => true
        ]);
    });

