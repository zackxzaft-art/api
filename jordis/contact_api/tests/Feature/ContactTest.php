<?php

use App\Models\User;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('Crea un contacto', function() {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/contacts',[
        'name' => 'Carlos Lopez',
        'phone_number' => '3001234567',
    ]);

    $response->assertOk()
        ->assertJson([
            'message' =>'Contacto creado correctamente',
        ]);

    $this->assertDatabaseHas('contacts',[
        'name' => 'Carlos Lopez',
        'phone_number' => '3001234567',
        'user_id' => $user->id,
    ]);
});

it('Lista los contactos del usuario autenticado', function() {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Contact::factory()->create([
        'user_id' => $user->id,
        'name' => 'Contacto Propio',
    ]);

    Contact::factory()->create([
        'user_id' => $otherUser->id,
        'name' => 'Contacto Ajeno',
    ]);

    $response = $this->actingAs($user, 'sanctum')->getJson('/api/contacts');

    $response->assertOk()
        ->assertJsonFragment([
            'name' => 'Contacto Propio',
        ])
        ->assertJsonMissing([
            'name' => 'Contacto Ajeno',
        ]);
});

it('No permite crear un contacto con el mismo telefono', function (){
    $user = User::factory()->create();

    Contact::factory()->create([
        'user_id' => $user->id,
        'phone_number' => '3001234567',
    ]);

    $response = $this->actingAs($user, 'sanctum')->postJson('/api/contacts',[
        'name' => 'Comtacto Repetido',
        'phone_number' => '3001234567',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['phone_number']);
});

it('No permite ver contacto de otro usuario', function (){
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $contact = Contact::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $response = $this->actingAs($user, 'sanctum')->getJson("/api/contacts/{$contact->id}");
    
    $response->assertForbidden();
});
