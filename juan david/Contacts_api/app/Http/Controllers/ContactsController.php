<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactsController extends Controller

{
    public function index()
    {
        $userId = auth()->id();

        $misContactos = Contacts::where('user_id', $userId)->get();

        return response()->json([
            'contacts' => $misContactos
        ]);
    }

    // crear un nuevo contacto
    public function store(Request $request)
    {
        $userId = auth()->id();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error al crear el contacto',
                'errors' => $validator->errors()
            ], 422);
        }

        $existe = Contacts::where('user_id', $userId)
            ->where('phone_number', $request->phone_number)
            ->exists();

        if ($existe) {
            return response()->json([
                'message' => 'Ya tienes un contacto con ese numero de telefono'
            ], 422);
        }
        $contacto = new contacts();
        $contacto-> name =$request->name;
        $contacto->phone_number =$request->phone_number;
        $contacto->user_id =$request->user_id;
        $contacto->save();

        return response()->json([
            'message'=> 'Contacto creado ',
            'contact'=> $contacto
        ],201);

        
    }
    }
