<?php

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('lista los contactos del usuario autenticado', function () {

    $user = User::factory()->create();

    Sanctum::actingAs($user);

    Contact::factory()->count(3)->create([
        'user_id' => $user->id,
    ]);

    $response = $this->getJson('/api/contacts');

    $response->assertStatus(200);

    $response->assertJsonCount(3, 'data');
});