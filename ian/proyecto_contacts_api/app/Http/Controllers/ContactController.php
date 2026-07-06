<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User
use App\Models\contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactController extends Controller
{
     public function index(Request $request)
    {
        $contacts = $request->user()->contacts()->paginate(10); 

        return response()->json($contacts);
    }
  

      public function store(Request $request)
   {
       $validated = $request->validate([
           'name' => 'required|string|max:255',
           'phone_number' => [
               'required',
               'string',
               'max:20',
               // Único, pero SOLO dentro de los contactos de ESTE usuario
               Rule::unique('contacts')->where(fn ($query) => $query->where('user_id', $request->user()->id)),
            ],
       ]);

    $contact = $request->user()->contacts()->create($validated);

    return response()->json($contact, 201);
   }
}
