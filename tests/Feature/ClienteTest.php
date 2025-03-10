<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function un_administrador_puede_ver_la_lista_de_clientes()
    {
        $admin = new User();
        $admin->tipo_usuario = 'admin';
        $admin->dni = '12345678Z';
        $admin->name = 'Admin';
        $admin->surname = 'User';
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('password123');
        $admin->save();

        $this->actingAs($admin);

        $response = $this->get(route('clientes.index'));

        $response->assertStatus(200);
        $response->assertViewIs('clientes.index');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function se_puede_crear_un_cliente_correctamente()
    {
        $admin = new User();
        $admin->tipo_usuario = 'admin';
        $admin->dni = '87654321X';
        $admin->name = 'Admin';
        $admin->surname = 'User';
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('password123');
        $admin->save();

        $this->actingAs($admin);

        $response = $this->post(route('clientes.store'), [
            'dni' => '12345678Z',
            'name' => 'Juan',
            'surname' => 'Pérez',
            'email' => 'juan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('clientes.index'));
        $this->assertDatabaseHas('users', ['email' => 'juan@example.com']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function un_cliente_se_puede_editar_correctamente()
    {
        $admin = new User();
        $admin->tipo_usuario = 'admin';
        $admin->dni = '11223344A';
        $admin->name = 'Admin';
        $admin->surname = 'User';
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('password123');
        $admin->save();

        $cliente = new User();
        $cliente->tipo_usuario = 'cliente';
        $cliente->dni = '87654321X';
        $cliente->name = 'Cliente';
        $cliente->surname = 'Original';
        $cliente->email = 'cliente@example.com';
        $cliente->password = Hash::make('password123');
        $cliente->save();

        $this->actingAs($admin);

        $response = $this->put(route('clientes.update', $cliente->id), [
            'dni' => '87654321X',
            'name' => 'Carlos',
            'surname' => 'Gómez',
            'email' => 'carlos@example.com',
            'password' => '',
        ]);

        $response->assertRedirect(route('clientes.index'));
        $this->assertDatabaseHas('users', ['email' => 'carlos@example.com']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function un_cliente_puede_ser_eliminado_correctamente()
    {
        $admin = new User();
        $admin->tipo_usuario = 'admin';
        $admin->dni = '99887766M';
        $admin->name = 'Admin';
        $admin->surname = 'User';
        $admin->email = 'admin@example.com';
        $admin->password = Hash::make('password123');
        $admin->save();

        $cliente = new User();
        $cliente->tipo_usuario = 'cliente';
        $cliente->dni = '98765432L';
        $cliente->name = 'Cliente';
        $cliente->surname = 'Eliminar';
        $cliente->email = 'cliente@example.com';
        $cliente->password = Hash::make('password123');
        $cliente->save();

        $this->actingAs($admin);

        $response = $this->delete(route('clientes.destroy', $cliente->id));

        $response->assertRedirect(route('clientes.index'));
        $this->assertDatabaseMissing('users', ['id' => $cliente->id]);
    }
}
