<?php

namespace Tests\Unit;

use App\Http\Controllers\ReservaController;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Horario;
use App\Models\Tarifa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\Session;

class ReservaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;
    protected $clienteUser;
    protected $adminUser;
    protected $tarifa;

    public function setUp(): void
    {
        parent::setUp();
        
        // Crear el controlador
        $this->controller = new ReservaController();
        
        // Crear usuarios de prueba
        $this->clienteUser = new User();
        $this->clienteUser->tipo_usuario = 'cliente';
        $this->clienteUser->dni = '12345678A';
        $this->clienteUser->clases = ['Yoga', 'Pilates'];
        $this->clienteUser->save();

        $this->adminUser = new User();
        $this->adminUser->tipo_usuario = 'admin';
        $this->adminUser->dni = '87654321Z';
        $this->adminUser->save();

        $this->tarifa = new Tarifa();
        $this->tarifa->id = 1;
        $this->tarifa->nombre = 'Tarifa Básica';
        $this->tarifa->precio = 30.00;
        $this->tarifa->duracion = 30;
        $this->tarifa->descripcion = 'Tarifa básica para clientes';
        $this->tarifa->save();
        
        // Crear una tarifa y asignarla al usuario cliente
        $this->clienteUser->tarifa()->associate($this->tarifa);
        $this->clienteUser->save();
        

        $horario1 = new Horario();
        $horario1->hora_inicio = '10:00:00';
        $horario1->hora_fin = '11:00:00';
        $horario1->dia = 'Lunes';
        $horario1->clase = 'Yoga';
        $horario1->disponible = true;
        $horario1->save();

        $horario2 = new Horario();
        $horario2->hora_inicio = '16:00:00';
        $horario2->hora_fin = '17:00:00';
        $horario2->dia = 'Martes';
        $horario2->clase = 'Pilates';
        $horario2->disponible = true;
        $horario2->save();
    }

    /** @test */
    public function cliente_puede_ver_sus_propias_reservas()
    {
        // Autenticar como cliente
        $this->actingAs($this->clienteUser);
        
        // Crear algunas reservas para el cliente
        Reserva::factory()->count(3)->create([
            'user_id' => $this->clienteUser->id,
            'clase' => 'Yoga',
            'dia' => Carbon::tomorrow()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ]);
        
        // Hacer la solicitud
        $response = $this->get(route('mis-reservas.index', $this->clienteUser->id));
        
        // Verificar respuesta
        $response->assertStatus(200);
        $response->assertViewIs('reservas.index');
        $response->assertViewHas('reservas');
        $response->assertViewHas('usuario');
    }
    
    /** @test */
    public function cliente_no_puede_ver_reservas_de_otro_usuario()
    {
        // Autenticar como cliente
        $this->actingAs($this->clienteUser);
        
        // Crear otro usuario cliente
        $otroCliente = User::factory()->create(['tipo_usuario' => 'cliente']);
        
        // Hacer la solicitud para ver las reservas del otro cliente
        $response = $this->get(route('mis-reservas.index', $otroCliente->id));
        
        // Verificar que se deniega el acceso
        $response->assertStatus(403);
    }
    
    /** @test */
    public function cliente_puede_crear_una_reserva()
    {
        // Autenticar como cliente
        $this->actingAs($this->clienteUser);
        
        // Datos para la nueva reserva
        $data = [
            'user_id' => $this->clienteUser->id,
            'clase' => 'Yoga',
            'dia' => Carbon::tomorrow()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ];
        
        // Hacer la solicitud para crear una reserva
        $response = $this->post(route('mis-reservas.store'), $data);
        
        // Verificar redirección y creación en la base de datos
        $response->assertRedirect(route('mis-reservas.index', $this->clienteUser->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reservas', [
            'user_id' => $this->clienteUser->id,
            'clase' => 'Yoga',
            'dia' => Carbon::tomorrow()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ]);
    }
    
    /** @test */
    public function cliente_no_puede_crear_reserva_para_fecha_pasada()
    {
        // Autenticar como cliente
        $this->actingAs($this->clienteUser);
        
        // Datos para la reserva con fecha pasada
        $data = [
            'user_id' => $this->clienteUser->id,
            'clase' => 'Yoga',
            'dia' => Carbon::yesterday()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ];
        
        // Hacer la solicitud
        $response = $this->post(route('mis-reservas.store'), $data);
        
        // Verificar que hay error y no se creó la reserva
        $response->assertSessionHasErrors(['dia']);
        $this->assertDatabaseMissing('reservas', [
            'user_id' => $this->clienteUser->id,
            'dia' => Carbon::yesterday()->format('Y-m-d')
        ]);
    }
    
    /** @test */
    public function cliente_no_puede_crear_reserva_duplicada()
    {
        // Autenticar como cliente
        $this->actingAs($this->clienteUser);
        
        // Crear una reserva existente
        $reservaExistente = Reserva::factory()->create([
            'user_id' => $this->clienteUser->id,
            'clase' => 'Yoga',
            'dia' => Carbon::tomorrow()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ]);
        
        // Intentar crear la misma reserva
        $data = [
            'user_id' => $this->clienteUser->id,
            'clase' => 'Yoga',
            'dia' => Carbon::tomorrow()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ];
        
        // Hacer la solicitud
        $response = $this->post(route('mis-reservas.store'), $data);
        
        // Verificar que hay error y no se duplicó la reserva
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals(1, Reserva::where([
            'user_id' => $this->clienteUser->id,
            'clase' => 'Yoga',
            'dia' => Carbon::tomorrow()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ])->count());
    }
    
    /** @test */
    public function cliente_puede_eliminar_su_reserva()
    {
        // Autenticar como cliente
        $this->actingAs($this->clienteUser);
        
        // Crear una reserva para el cliente
        $reserva = Reserva::factory()->create([
            'user_id' => $this->clienteUser->id,
            'clase' => 'Yoga',
            'dia' => Carbon::tomorrow()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ]);
        
        // Hacer la solicitud para eliminar la reserva
        $response = $this->delete(route('mis-reservas.destroy', $reserva->id));
        
        // Verificar redirección y eliminación de la base de datos
        $response->assertRedirect(route('mis-reservas.index', $this->clienteUser->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('reservas', ['id' => $reserva->id]);
    }
    
    /** @test */
    public function cliente_no_puede_eliminar_reserva_de_otro_usuario()
    {
        // Autenticar como cliente
        $this->actingAs($this->clienteUser);
        
        // Crear otro usuario cliente
        $otroCliente = User::factory()->create(['tipo_usuario' => 'cliente']);
        
        // Crear una reserva para el otro cliente
        $reservaOtroCliente = Reserva::factory()->create([
            'user_id' => $otroCliente->id,
            'clase' => 'Yoga',
            'dia' => Carbon::tomorrow()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ]);
        
        // Hacer la solicitud para eliminar la reserva del otro cliente
        $response = $this->delete(route('mis-reservas.destroy', $reservaOtroCliente->id));
        
        // Verificar que se deniega el acceso
        $response->assertStatus(403);
        $this->assertDatabaseHas('reservas', ['id' => $reservaOtroCliente->id]);
    }
    
    /** @test */
    public function admin_puede_ver_todas_las_reservas()
    {
        // Autenticar como administrador
        $this->actingAs($this->adminUser);
        
        // Crear algunas reservas
        Reserva::factory()->count(5)->create();
        
        // Hacer la solicitud
        $response = $this->get(route('admin-reservas.index'));
        
        // Verificar respuesta
        $response->assertStatus(200);
        $response->assertViewIs('reservas.admin.index');
        $response->assertViewHas('reservas');
    }
    
    /** @test */
    public function admin_puede_crear_reserva_para_cualquier_cliente()
    {
        // Autenticar como administrador
        $this->actingAs($this->adminUser);
        
        // Datos para la nueva reserva
        $data = [
            'dni' => $this->clienteUser->dni,
            'clase' => 'Yoga',
            'dia' => Carbon::tomorrow()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ];
        
        // Hacer la solicitud
        $response = $this->post(route('admin-reservas.store'), $data);
        
        // Verificar redirección y creación en la base de datos
        $response->assertRedirect(route('admin-reservas.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reservas', [
            'user_id' => $this->clienteUser->id,
            'clase' => 'Yoga',
            'dia' => Carbon::tomorrow()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ]);
    }
    
    /** @test */
    public function admin_puede_eliminar_cualquier_reserva()
    {
        // Autenticar como administrador
        $this->actingAs($this->adminUser);
        
        // Crear una reserva para un cliente
        $reserva = Reserva::factory()->create([
            'user_id' => $this->clienteUser->id,
            'clase' => 'Yoga',
            'dia' => Carbon::tomorrow()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ]);
        
        // Hacer la solicitud para eliminar la reserva
        $response = $this->delete(route('admin-reservas.destroy', $reserva->id));
        
        // Verificar redirección y eliminación de la base de datos
        $response->assertRedirect(route('admin-reservas.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('reservas', ['id' => $reserva->id]);
    }
    
    /** @test */
    public function metodo_getDiasDisponibles_devuelve_dias_correctos()
    {
        // Autenticar como cliente
        $this->actingAs($this->clienteUser);
        
        // Crear una solicitud con la clase
        $request = new Request(['clase' => 'Yoga']);
        
        // Llamar al método
        $response = $this->controller->getDiasDisponibles($request);
        
        // Verificar que la respuesta es JSON y contiene días
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('dias', $content);
        $this->assertNotEmpty($content['dias']);
        
        // Verificar que al menos un día es lunes (donde tenemos horario de Yoga)
        $tieneHorarioLunes = false;
        foreach ($content['dias'] as $dia) {
            if ($dia['nombreDia'] === 'lunes') {
                $tieneHorarioLunes = true;
                break;
            }
        }
        $this->assertTrue($tieneHorarioLunes);
    }
    
    /** @test */
    public function metodo_getHorasDisponibles_devuelve_horas_correctas()
    {
        // Autenticar como cliente
        $this->actingAs($this->clienteUser);
        
        // Crear una solicitud con la clase y día
        $request = new Request([
            'clase' => 'Yoga',
            'dia' => 'Lunes'
        ]);
        
        // Llamar al método
        $response = $this->controller->getHorasDisponibles($request);
        
        // Verificar que la respuesta es JSON y contiene horas
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('horas', $content);
        $this->assertNotEmpty($content['horas']);
        $this->assertContains('10:00 - 11:00', $content['horas']);
    }
    
    /** @test */
    public function cliente_puede_editar_su_reserva()
    {
        // Autenticar como cliente
        $this->actingAs($this->clienteUser);
        
        // Crear una reserva para el cliente
        $reserva = Reserva::factory()->create([
            'user_id' => $this->clienteUser->id,
            'clase' => 'Yoga',
            'dia' => Carbon::tomorrow()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ]);
        
        // Datos para actualizar la reserva
        $data = [
            'clase' => 'Pilates',
            'dia' => Carbon::tomorrow()->addDay()->format('Y-m-d'),
            'hora' => '16:00 - 17:00'
        ];
        
        // Hacer la solicitud para actualizar la reserva
        $response = $this->put(route('mis-reservas.update', [
            'userId' => $this->clienteUser->id,
            'reservaId' => $reserva->id
        ]), $data);
        
        // Verificar redirección y actualización en la base de datos
        $response->assertRedirect(route('mis-reservas.index', $this->clienteUser->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reservas', [
            'id' => $reserva->id,
            'user_id' => $this->clienteUser->id,
            'clase' => 'Pilates',
            'dia' => Carbon::tomorrow()->addDay()->format('Y-m-d'),
            'hora' => '16:00 - 17:00'
        ]);
    }
    
    /** @test */
    public function admin_puede_editar_cualquier_reserva()
    {
        // Autenticar como administrador
        $this->actingAs($this->adminUser);
        
        // Crear una reserva para un cliente
        $reserva = Reserva::factory()->create([
            'user_id' => $this->clienteUser->id,
            'clase' => 'Yoga',
            'dia' => Carbon::tomorrow()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ]);
        
        // Datos para actualizar la reserva
        $data = [
            'dni' => $this->clienteUser->dni,
            'clase' => 'Pilates',
            'dia' => Carbon::tomorrow()->addDay()->format('Y-m-d'),
            'hora' => '16:00 - 17:00'
        ];
        
        // Hacer la solicitud para actualizar la reserva
        $response = $this->put(route('admin-reservas.update', $reserva->id), $data);
        
        // Verificar redirección y actualización en la base de datos
        $response->assertRedirect(route('admin-reservas.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reservas', [
            'id' => $reserva->id,
            'user_id' => $this->clienteUser->id,
            'clase' => 'Pilates',
            'dia' => Carbon::tomorrow()->addDay()->format('Y-m-d'),
            'hora' => '16:00 - 17:00'
        ]);
    }
    
    /** @test */
    public function eliminarReservasVencidas_elimina_reservas_pasadas()
    {
        // Crear reservas pasadas
        Reserva::factory()->create([
            'user_id' => $this->clienteUser->id,
            'clase' => 'Yoga',
            'dia' => Carbon::yesterday()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ]);
        
        // Crear reservas futuras
        $reservaFutura = Reserva::factory()->create([
            'user_id' => $this->clienteUser->id,
            'clase' => 'Yoga',
            'dia' => Carbon::tomorrow()->format('Y-m-d'),
            'hora' => '10:00 - 11:00'
        ]);
        
        // Autenticar como cliente y acceder a una ruta que ejecute eliminarReservasVencidas
        $this->actingAs($this->clienteUser);
        $this->get(route('mis-reservas.index', $this->clienteUser->id));
        
        // Verificar que las reservas pasadas se eliminaron y las futuras permanecen
        $this->assertDatabaseMissing('reservas', [
            'dia' => Carbon::yesterday()->format('Y-m-d')
        ]);
        $this->assertDatabaseHas('reservas', [
            'id' => $reservaFutura->id
        ]);
    }
    
    /** @test */
    public function buscarUsuarioPorDNI_encuentra_usuario_correcto()
    {
        // Autenticar como administrador
        $this->actingAs($this->adminUser);
        
        // Crear una solicitud con el DNI
        $request = new Request(['dni' => $this->clienteUser->dni]);
        
        // Llamar al método
        $response = $this->controller->buscarUsuarioPorDNI($request);
        
        // Verificar que la respuesta es JSON y contiene el usuario correcto
        $this->assertEquals(200, $response->getStatusCode());
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('usuario', $content);
        $this->assertEquals($this->clienteUser->id, $content['usuario']['id']);
        $this->assertEquals($this->clienteUser->dni, $content['usuario']['dni']);
    }
    
    /** @test */
    public function buscarUsuarioPorDNI_devuelve_404_si_no_encuentra_usuario()
    {
        // Autenticar como administrador
        $this->actingAs($this->adminUser);
        
        // Crear una solicitud con un DNI que no existe
        $request = new Request(['dni' => '00000000X']);
        
        // Llamar al método
        $response = $this->controller->buscarUsuarioPorDNI($request);
        
        // Verificar que la respuesta es 404
        $this->assertEquals(404, $response->getStatusCode());
    }
}