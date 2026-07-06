<?php

namespace Tests\Feature;



use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;
use App\Models\Contacts;
use App\Models\User;
use Faker\Factory;

class ContactoTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
    }
    public function test_register()
    {
        $this->withoutExceptionHandling();
        $datos = [
            'name' => $this->faker()->firstName(),
            'email' => $this->faker()->safeEmail(),
            'password' => '12345678',
        ];
        $response = $this->postJson('/api/register', $datos);
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'usuario registrado con exito',
        ], 201);
        $this->assertDatabaseHas('users', [
            'email' => $datos['email']
        ]);
    }
    public function test_login()
    {

        $user = User::factory()->create();

        $datos = [
            'email' => $user->email,
            'password' => 'password',
        ];


        $response = $this->postJson('/api/login', $datos);


        $response->assertJson([
            'success' => true,
            'message' => 'Usuario ha iniciado sesión con éxito',
        ], 200);


        $this->assertNotEmpty($response->json('token'));


        $this->assertEquals(
            $user->email,
            $response->json('data.email')
        );
    }
    public function test_crear_contacto()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $datos = [
            'name' => $this->faker()->firstName(),
            'user_id' => $user->id,
            'phone_number' => $this->faker()->numerify('##########')
        ];
        $response = $this->postJson('/api/contacts', $datos);
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'contacto creado con exito',
            'data' => $datos
        ]);
    }
    public function test_actualizacion_contacto()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $contacto = Contacts::factory()->create([
            'user_id' => $user->id,
        ]);
        $datosActualizados = [
            'name' => fake()->firstName(),
            'user_id' => $user->id,
            'phone_number' => fake()->numerify('##########'),
        ];
        $response = $this->putJson('/api/contacts/' . $contacto->id, $datosActualizados);
        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'message' => 'contacto actualizado con exito',
            'data' => $datosActualizados
        ]);
        $this->assertDatabaseHas('contacts', [
            'id' => $contacto->id,
            'name' => $datosActualizados['name'],
            'user_id' => $datosActualizados['user_id'],
            'phone_number' => $datosActualizados['phone_number'],
        ]);
    }
    public function test_actualizacion_informacion_de_usuario()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $datos = [
            'name' => $this->faker()->firstName(),
            'email' => $this->faker()->safeEmail(),
            'password' => '12345678'
        ];
        $usuarioActualizado = [
            'name' => $datos['name'],
            'email' => $datos['email'],
        ];
        $response = $this->putJson('/api/users/' . $user->id, $datos);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'usuario actualizado con exito',
            'data' => $usuarioActualizado
        ], 200);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $datos['name'],
            'email' => $datos['email'],
        ]);
    }
    public function test_listar_contactos()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $contactos = Contacts::factory()->count(5)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson("/api/users/{$user->id}/contactos");

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'message' => 'lista de contactos',
            'user' => $user->name,
        ]);

        $response->assertJsonCount(5, 'data');

        foreach ($contactos as $contacto) {
            $response->assertJsonFragment([
                'id' => $contacto->id,
                'name' => $contacto->name,
                'user_id' => $contacto->user_id,
                'phone_number' => $contacto->phone_number,
            ]);
        }
    }
    public function test_no_permite_ver_los_contactos_de_otro_usuario()
    {
        $user = User::factory()->create();

        $otroUsuario = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        Contacts::factory()->count(5)->create([
            'user_id' => $otroUsuario->id,
        ]);

        $response = $this->getJson("/api/users/{$otroUsuario->id}/contacts");

        $response->assertStatus(403);

        $response->assertJson([
            'success' => false,
            'message' => 'No puedes ver los contactos del usuario seleccionado',
        ]);
    }
    public function test_example()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
