<?php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('registra un usuario', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Juan Perez',
        'email' => 'juan@example.com',
        'password' => 'password123',
    ]);

    $response->assertCreated()
        ->assertJsonStructure([
            'message',
            'user',
            'token',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'juan@example.com',
        ]);
});

it('actualiza la informacion del usuario', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->putJson('/api/user', [
        'name' => 'Nombre Actualizado',
        'email' => 'nuevo@example.com',
    ]);

    $response->assertOk()
        ->assertJson([
            'message' => 'Usuario actualizado correctamente',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nombre Actualizado',
            'email' => 'nuevo@example.com',
        ]);
});

it('No permite reguistrar un usuario con email ya registrado', function () {
    User::factory()->create([
        'email' => 'juan@example.com',
    ]);

    $response = $this->postJson('/api/register',[
        'name' => 'Otro Usuario',
        'email' => 'juan@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});