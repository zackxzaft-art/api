<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contacts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseIsUnprocessable;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $id)
    {

        $user = User::find($id);
        $contactos = $user->contactos;
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'usuario no encontrado'
            ], 404);
        } else {
            return response()->json([
                'success' => true,
                'user' => $user->name,
                'message' => 'lista de contactos',
                'data' => $contactos
            ], 200);
        }
    }
    public function listar_contactos(User $user)
    {
        $usuarioAutenticado = auth('sanctum')->user();

        // Verificar que el usuario solo pueda ver sus propios contactos
        if ($usuarioAutenticado->id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes ver los contactos del usuario seleccionado',
            ], 403);
        }

        $contactos = Contacts::where('user_id', $user->id)->get();

        return response()->json([
            'success' => true,
            'user' => $user->name,
            'message' => 'lista de contactos',
            'data' => $contactos,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8']
        ]);
        $resultado = $user->update([
            'name' => $datos['name'],
            'email' => $datos['email'],
            'password' => bcrypt($datos['password']),
        ]);
        if ($resultado === false) {
            return response()->json([
                'success' => false,
                'message' => 'usuario no se pudo actualizar',
            ], 404);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'usuario actualizado con exito',
                'data' => $user->refresh(),
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
