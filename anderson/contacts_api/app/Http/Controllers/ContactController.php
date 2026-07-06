<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class ContactController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(
    [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email'
        ],
    [
        'email.unique' => 'El contacto ya está registrado.',
    ]
);

        $user = Contact::create([
            'name' => $request->name,
            'email' => $request->email
            
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Contacto registrado correctamente',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
    public function update(Request $request)
{
    $user = $request->user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
    ]);

    $user->update($validated);

    return response()->json([
        'message' => 'Contacto actualizado correctamente.',
        'user' => $user
    ], 200);
}
}
